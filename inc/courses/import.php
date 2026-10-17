<?php
/**
 * Импорт курсов из Excel, выгруженного скриптом tools/getcourse-export.js.
 *
 * Админка: Курсы → Импорт из GetCourse. Файл читается в браузере (SheetJS),
 * строки отправляются пачками в admin-ajax — без PHP-библиотек и долгих запросов.
 *
 * Курс ищется по offer_id, затем по названию + часам. Пустые ячейки не затирают
 * то, что уже заполнено на сайте (краткое/полное описание, теги и т.п.).
 * Картинки (в т.ч. образцы документов) скачиваются один раз на URL, параллельно
 * по пачке, уменьшаются и сохраняются в WebP.
 */

defined('ABSPATH') || exit;

const RCPK_IMPORT_MAX_WIDTH = 1920;

add_action('admin_menu', function () {
    add_submenu_page('edit.php?post_type=course', 'Импорт из GetCourse', 'Импорт из GetCourse', 'edit_posts', 'rcpk-course-import', 'rcpk_course_import_page');
});

function rcpk_course_import_page()
{
?>
    <div class="wrap">
        <h1>Импорт курсов из GetCourse</h1>
        <ol>
            <li>На GetCourse (centr.rcpk62.ru) откройте консоль браузера (F12 → Console), вставьте скрипт выгрузки и нажмите Enter.</li>
            <li>Загрузите сюда скачанный файл <code>getcourse-courses-…xlsx</code>.</li>
        </ol>
        <p><input type="file" id="rcpk-import-file" accept=".xlsx,.xls"></p>
        <p>
            <label><input type="checkbox" id="rcpk-import-images" checked> Загружать картинки (курсы, фоны направлений, образцы документов)</label>
        </p>
        <p><button type="button" class="button button-primary" id="rcpk-import-start" disabled>Импортировать</button></p>
        <div id="rcpk-import-progress" style="display:none;max-width:600px;">
            <progress id="rcpk-import-bar" value="0" max="100" style="width:100%;"></progress>
            <p id="rcpk-import-status"></p>
        </div>
        <pre id="rcpk-import-log" style="max-height:400px;overflow:auto;background:#fff;padding:10px;display:none;"></pre>
        <div id="rcpk-import-orphans" style="display:none;max-width:900px;">
            <h2>Опубликованы на сайте, но в файле их нет</h2>
            <p>Курс убрали с GetCourse или переименовали. Импорт ничего не удаляет — решите по каждому.
                Если файл был частичным (не все курсы), это нормально: ничего делать не нужно.</p>
            <table class="widefat striped"><tbody id="rcpk-import-orphans-list"></tbody></table>
        </div>
    </div>
<?php
}

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'course_page_rcpk-course-import') {
        return;
    }
    wp_enqueue_script('sheetjs', 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js', array(), '0.18.5', true);
    wp_add_inline_script('sheetjs', 'window.rcpkImport = ' . wp_json_encode(array(
        'ajax'  => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rcpk_course_import'),
    )) . ';' . <<<'JS'
(function () {
    var BATCH = 20;
    var fileInput = document.getElementById('rcpk-import-file');
    var startBtn = document.getElementById('rcpk-import-start');
    var bar = document.getElementById('rcpk-import-bar');
    var status = document.getElementById('rcpk-import-status');
    var logEl = document.getElementById('rcpk-import-log');
    var book = null;

    function log(line) {
        logEl.style.display = 'block';
        logEl.textContent += line + '\n';
        logEl.scrollTop = logEl.scrollHeight;
    }

    fileInput.addEventListener('change', function () {
        var file = fileInput.files[0];
        if (!file) { return; }
        file.arrayBuffer().then(function (buf) {
            book = XLSX.read(buf, { type: 'array' });
            if (!book.Sheets['Курсы']) {
                alert('В файле нет листа «Курсы». Нужен файл из скрипта выгрузки GetCourse.');
                book = null;
                return;
            }
            startBtn.disabled = false;
        });
    });

    function send(action, rows) {
        var body = new FormData();
        body.append('action', action);
        body.append('nonce', rcpkImport.nonce);
        body.append('images', document.getElementById('rcpk-import-images').checked ? '1' : '');
        body.append('rows', JSON.stringify(rows));
        return fetch(rcpkImport.ajax, { method: 'POST', body: body, credentials: 'same-origin' })
            .then(function (r) { return r.text(); })
            .then(function (text) {
                var r;
                try { r = JSON.parse(text); } catch (e) {
                    throw new Error('сервер ответил не JSON: ' + text.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 300));
                }
                if (!r.success) { throw new Error(r.data || 'Ошибка сервера'); }
                return r.data;
            });
    }

    startBtn.addEventListener('click', async function () {
        startBtn.disabled = true;
        logEl.textContent = '';
        document.getElementById('rcpk-import-progress').style.display = 'block';
        var dirs = book.Sheets['Направления'] ? XLSX.utils.sheet_to_json(book.Sheets['Направления'], { defval: '' }) : [];
        var rows = XLSX.utils.sheet_to_json(book.Sheets['Курсы'], { defval: '', raw: false });
        var total = { created: 0, updated: 0, skipped: 0 };
        var seen = [];
        document.getElementById('rcpk-import-orphans').style.display = 'none';
        try {
            // По одному направлению за запрос: фон может качаться долго
            bar.max = dirs.length || 1;
            for (var j = 0; j < dirs.length; j++) {
                status.textContent = 'Направления: ' + (j + 1) + ' из ' + dirs.length + ' — ' + dirs[j]['Направление'];
                await send('rcpk_import_directions', [dirs[j]]);
                bar.value = j + 1;
            }
            if (dirs.length) { log('Направлений обработано: ' + dirs.length); }
            bar.value = 0;
            bar.max = rows.length;
            for (var i = 0; i < rows.length; i += BATCH) {
                var res = await send('rcpk_import_courses', rows.slice(i, i + BATCH));
                total.created += res.created;
                total.updated += res.updated;
                total.skipped += res.skipped;
                res.messages.forEach(log);
                seen = seen.concat(res.ids);
                bar.value = Math.min(i + BATCH, rows.length);
                status.textContent = 'Курсы: ' + bar.value + ' из ' + rows.length;
            }
            status.textContent = 'Готово. Создано: ' + total.created + ', обновлено: ' + total.updated + ', пропущено: ' + total.skipped + '.';
            showOrphans(await send('rcpk_import_orphans', seen));
        } catch (e) {
            status.textContent = 'Остановлено: ' + e.message + '. Можно запустить импорт ещё раз — уже загруженные курсы обновятся, а не задублируются.';
        }
        startBtn.disabled = false;
    });

    // Опубликованные курсы, которых не было в файле: по кнопке каждый уходит в черновик
    function showOrphans(list) {
        var box = document.getElementById('rcpk-import-orphans');
        var tbody = document.getElementById('rcpk-import-orphans-list');
        tbody.textContent = '';
        if (!list.length) { return; }
        list.forEach(function (c) {
            var tr = document.createElement('tr');
            var td = document.createElement('td');
            var a = document.createElement('a');
            a.href = c.edit;
            a.target = '_blank';
            a.textContent = c.title + (c.hours ? ' (' + c.hours + ' ч)' : '');
            td.appendChild(a);
            var td2 = document.createElement('td');
            td2.style.width = '120px';
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'button';
            btn.textContent = 'В черновик';
            btn.addEventListener('click', function () {
                btn.disabled = true;
                send('rcpk_import_draft', [c.id]).then(function () {
                    td2.textContent = 'Черновик';
                }, function (e) {
                    btn.disabled = false;
                    alert(e.message);
                });
            });
            td2.appendChild(btn);
            tr.appendChild(td);
            tr.appendChild(td2);
            tbody.appendChild(tr);
        });
        box.style.display = 'block';
        log('Опубликовано на сайте, но нет в файле: ' + list.length + ' (список ниже)');
    }
})();
JS
    );
});

function rcpk_import_check()
{
    check_ajax_referer('rcpk_course_import', 'nonce');
    if (!current_user_can('edit_posts') || !current_user_can('upload_files')) {
        wp_send_json_error('Недостаточно прав');
    }
    $rows = json_decode(wp_unslash(isset($_POST['rows']) ? $_POST['rows'] : '[]'), true);
    return is_array($rows) ? $rows : array();
}

/**
 * Скачивает картинку один раз на URL, уменьшает и сохраняет в WebP (если сервер умеет).
 * @return int ID вложения или 0
 */
function rcpk_import_image($url, $title, $parent_id = 0)
{
    $url = esc_url_raw(trim((string) $url));
    if (!$url) {
        return 0;
    }
    $map = get_option('rcpk_gc_image_map', array());
    if (!empty($map[$url]) && get_post($map[$url])) {
        return (int) $map[$url];
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = isset($GLOBALS['rcpk_import_prefetched'][$url]) ? $GLOBALS['rcpk_import_prefetched'][$url] : download_url($url, 60);
    unset($GLOBALS['rcpk_import_prefetched'][$url]);
    if (is_wp_error($tmp) || !$tmp) {
        return 0;
    }

    $base = sanitize_title($title) ?: 'getcourse';
    $ext  = 'png';
    $info = @getimagesize($tmp);
    if ($info && !empty($info['mime'])) {
        $ext = str_replace(array('image/', 'jpeg'), array('', 'jpg'), $info['mime']);
    }

    $editor = wp_get_image_editor($tmp);
    if (!is_wp_error($editor)) {
        $size = $editor->get_size();
        if ($size['width'] > RCPK_IMPORT_MAX_WIDTH) {
            $editor->resize(RCPK_IMPORT_MAX_WIDTH, null);
        }
        $editor->set_quality(80);
        $mime  = $editor->supports_mime_type('image/webp') ? 'image/webp' : null;
        $saved = $editor->save($tmp . '-opt' . ($mime ? '.webp' : '.' . $ext), $mime);
        if (!is_wp_error($saved)) {
            @unlink($tmp);
            $tmp = $saved['path'];
            $ext = $mime ? 'webp' : $ext;
        }
    }

    $file = array('name' => $base . '.' . $ext, 'tmp_name' => $tmp);
    $id   = media_handle_sideload($file, $parent_id, $title);
    if (is_wp_error($id)) {
        @unlink($tmp);
        return 0;
    }
    $map[$url] = $id;
    update_option('rcpk_gc_image_map', $map, false);
    return (int) $id;
}

/**
 * Скачивает новые картинки пачки параллельно (по одной они качаются секунды):
 * rcpk_import_image потом берёт готовый файл вместо download_url.
 */
function rcpk_import_prefetch($urls)
{
    $map  = get_option('rcpk_gc_image_map', array());
    $urls = array_unique(array_filter(array_map(function ($url) {
        return esc_url_raw(trim((string) $url));
    }, $urls)));
    $urls = array_filter($urls, function ($url) use ($map) {
        return empty($map[$url]) || !get_post($map[$url]);
    });
    $requests_class = class_exists('\WpOrg\Requests\Requests') ? '\WpOrg\Requests\Requests' : 'Requests';
    if (!$urls || !class_exists($requests_class)) {
        return;
    }
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $requests = array();
    foreach ($urls as $url) {
        $tmp = wp_tempnam($url);
        if ($tmp) {
            $requests[$url] = array('url' => $url, 'options' => array('filename' => $tmp, 'timeout' => 60));
        }
    }
    try {
        $responses = $requests_class::request_multiple($requests);
    } catch (Exception $e) {
        return;
    }
    foreach ($responses as $url => $response) {
        $file = $requests[$url]['options']['filename'];
        if (is_object($response) && !empty($response->success) && filesize($file)) {
            $GLOBALS['rcpk_import_prefetched'][$url] = $file;
        } else {
            @unlink($file);
        }
    }
}

function rcpk_import_direction_term($name)
{
    $name = trim((string) $name);
    if ($name === '') {
        return 0;
    }
    // Сначала рубрика, у которой в поле «Название на GetCourse» стоит это имя, потом по названию
    $by_gc = get_terms(array(
        'taxonomy'   => 'course_direction',
        'hide_empty' => false,
        'fields'     => 'ids',
        'number'     => 1,
        'meta_key'   => '_rcpk_gc_name',
        'meta_value' => $name,
    ));
    if (!is_wp_error($by_gc) && $by_gc) {
        return (int) $by_gc[0];
    }
    $term = get_term_by('name', $name, 'course_direction');
    if ($term) {
        return (int) $term->term_id;
    }
    $created = wp_insert_term($name, 'course_direction');
    return is_wp_error($created) ? 0 : (int) $created['term_id'];
}

add_action('wp_ajax_rcpk_import_directions', function () {
    $rows   = rcpk_import_check();
    $images = !empty($_POST['images']);
    $done   = 0;
    foreach ($rows as $row) {
        $term_id = rcpk_import_direction_term(isset($row['Направление']) ? $row['Направление'] : '');
        if (!$term_id) {
            continue;
        }
        // Фон ставим, только если в админке его ещё не выбрали вручную
        if ($images && !get_term_meta($term_id, '_rcpk_image', true) && !empty($row['Фон (URL)'])) {
            $image_id = rcpk_import_image($row['Фон (URL)'], $row['Направление']);
            if ($image_id) {
                update_term_meta($term_id, '_rcpk_image', $image_id);
            }
        }
        $done++;
    }
    wp_send_json_success(array('done' => $done));
});

function rcpk_import_find_course($offer_id, $title, $hours)
{
    // Сначала название + часы: одно предложение на GetCourse может стоять у разных курсов.
    // По offer_id — если название на GetCourse поменяли (часы должны совпасть)
    $base = array('post_type' => 'course', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids');
    foreach (get_posts($base + array('title' => $title)) as $id) {
        if ((string) get_post_meta($id, '_rcpk_hours', true) === (string) $hours) {
            return (int) $id;
        }
    }
    if ($offer_id) {
        foreach (get_posts($base + array('meta_key' => '_rcpk_gc_offer_id', 'meta_value' => $offer_id)) as $id) {
            $h = (string) get_post_meta($id, '_rcpk_hours', true);
            if ($h === '' || $h === (string) $hours) {
                return (int) $id;
            }
        }
    }
    return 0;
}

add_action('wp_ajax_rcpk_import_courses', function () {
    $rows   = rcpk_import_check();
    $images = !empty($_POST['images']);
    $result = array('created' => 0, 'updated' => 0, 'skipped' => 0, 'messages' => array(), 'ids' => array());
    if ($images) {
        rcpk_import_prefetch(array_merge(
            wp_list_pluck($rows, 'Образец документа (URL)'),
            wp_list_pluck($rows, 'Картинка (URL)')
        ));
    }

    $col = function ($row, $key) {
        return isset($row[$key]) ? trim((string) $row[$key]) : '';
    };

    foreach ($rows as $row) {
        $title    = $col($row, 'Название');
        $offer_id = preg_replace('/\D/', '', $col($row, 'ID предложения (offer_id)'));
        $hours    = preg_replace('/\D/', '', $col($row, 'Часы'));
        if ($title === '') {
            $result['skipped']++;
            continue;
        }

        $post_id = rcpk_import_find_course($offer_id, $title, $hours);
        $postarr = array('post_type' => 'course', 'post_title' => $title);
        if ($col($row, 'Краткое описание') !== '') {
            $postarr['post_excerpt'] = sanitize_textarea_field($col($row, 'Краткое описание'));
        }
        if ($col($row, 'Полное описание') !== '') {
            $postarr['post_content'] = wp_kses_post($col($row, 'Полное описание'));
        }

        // Без offer_id оплата не сработает — такие курсы держим черновиками (с пометкой,
        // чтобы опубликовать обратно, когда предложение появится; ручные черновики не трогаем)
        if ($post_id) {
            $postarr['ID'] = $post_id;
            $status = get_post_status($post_id);
            if (!$offer_id && $status === 'publish') {
                $postarr['post_status'] = 'draft';
                update_post_meta($post_id, '_rcpk_import_draft', 1);
            } elseif ($offer_id && $status === 'draft' && get_post_meta($post_id, '_rcpk_import_draft', true)) {
                $postarr['post_status'] = 'publish';
                delete_post_meta($post_id, '_rcpk_import_draft');
            }
            $saved = wp_update_post($postarr, true);
        } else {
            $postarr['post_status'] = $offer_id ? 'publish' : 'draft';
            $saved = wp_insert_post($postarr, true);
            if (!is_wp_error($saved) && !$offer_id) {
                update_post_meta($saved, '_rcpk_import_draft', 1);
            }
        }
        if (is_wp_error($saved)) {
            $result['skipped']++;
            $result['messages'][] = 'Ошибка «' . $title . '»: ' . $saved->get_error_message();
            continue;
        }
        $post_id ? $result['updated']++ : $result['created']++;
        $post_id = (int) $saved;
        $result['ids'][] = $post_id;

        $meta = array(
            'gc_offer_id'  => $offer_id,
            'hours'        => $hours,
            'document'     => sanitize_text_field($col($row, 'Документ')),
            'validity'     => sanitize_text_field($col($row, 'Срок действия')),
            'requirements' => sanitize_textarea_field($col($row, 'Требования')),
            'price'        => preg_replace('/\D/', '', $col($row, 'Стоимость')),
        );
        foreach ($meta as $key => $value) {
            if ($value !== '') {
                update_post_meta($post_id, '_rcpk_' . $key, $value);
            }
        }
        // offer_id — данные GetCourse, а не ручная правка: пустой в файле значит «предложения нет»
        if ($offer_id === '') {
            delete_post_meta($post_id, '_rcpk_gc_offer_id');
        }

        $term_ids = array_filter(array_map('rcpk_import_direction_term', preg_split('/\s*;\s*/', $col($row, 'Направление'))));
        if ($term_ids) {
            wp_set_object_terms($post_id, array_values($term_ids), 'course_direction', true);
        }
        if ($col($row, 'Теги') !== '') {
            wp_set_object_terms($post_id, preg_split('/\s*[;,]\s*/', $col($row, 'Теги'), -1, PREG_SPLIT_NO_EMPTY), 'course_tag', true);
        }

        if ($images && !has_post_thumbnail($post_id) && $col($row, 'Картинка (URL)') !== '') {
            $image_id = rcpk_import_image($col($row, 'Картинка (URL)'), $title, $post_id);
            if ($image_id) {
                set_post_thumbnail($post_id, $image_id);
            }
        }

        // Образец документа: ставим, если поле пустое или в нём прежний импортированный образец
        if ($images && $col($row, 'Образец документа (URL)') !== '') {
            $current = (string) get_post_meta($post_id, '_rcpk_document_file', true);
            if ($current === '' || in_array((int) $current, array_map('intval', get_option('rcpk_gc_image_map', array())), true)) {
                $sample_id = rcpk_import_image($col($row, 'Образец документа (URL)'), 'Образец документа — ' . $title, $post_id);
                if ($sample_id) {
                    update_post_meta($post_id, '_rcpk_document_file', (string) $sample_id);
                }
            }
        }

        if ($col($row, 'Сверка с GetCourse') !== '') {
            $result['messages'][] = '«' . $title . '» (' . ($offer_id ?: 'без offer_id') . '): ' . $col($row, 'Сверка с GetCourse');
        }
    }
    // Скачанное заранее, но не понадобившееся (картинку уже выбрали вручную)
    foreach (isset($GLOBALS['rcpk_import_prefetched']) ? $GLOBALS['rcpk_import_prefetched'] : array() as $file) {
        @unlink($file);
    }
    wp_send_json_success($result);
});

// Опубликованные курсы, которых импорт не касался: убраны с GetCourse или переименованы
add_action('wp_ajax_rcpk_import_orphans', function () {
    $seen = array_map('intval', rcpk_import_check());
    $ids  = get_posts(array(
        'post_type'      => 'course',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post__not_in'   => $seen ?: array(0),
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));
    $list = array();
    foreach ($ids as $id) {
        $list[] = array(
            'id'    => $id,
            'title' => get_the_title($id),
            'hours' => get_post_meta($id, '_rcpk_hours', true),
            'edit'  => get_edit_post_link($id, 'raw'),
        );
    }
    wp_send_json_success($list);
});

// Кнопка «В черновик» в списке выше. Пометка _rcpk_import_draft: если курс снова
// появится в файле с offer_id, импорт опубликует его обратно
add_action('wp_ajax_rcpk_import_draft', function () {
    $ids = array_map('intval', rcpk_import_check());
    foreach ($ids as $id) {
        if (get_post_type($id) !== 'course' || !current_user_can('edit_post', $id)) {
            wp_send_json_error('Нет доступа к курсу ' . $id);
        }
        $saved = wp_update_post(array('ID' => $id, 'post_status' => 'draft'), true);
        if (is_wp_error($saved)) {
            wp_send_json_error($saved->get_error_message());
        }
        update_post_meta($id, '_rcpk_import_draft', 1);
    }
    wp_send_json_success(array('done' => count($ids)));
});
