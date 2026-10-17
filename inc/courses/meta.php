<?php
/**
 * Поля курса и SEO-поля направлений/категорий в админке.
 * Название, краткое и полное описание — стандартные заголовок, отрывок и контент записи.
 */

defined('ABSPATH') || exit;

function rcpk_course_fields()
{
    return array(
        'hours' => array(
            'label' => 'Объём, академических часов',
            'type'  => 'number',
        ),
        'document' => array(
            'label'       => 'Выдаваемый документ',
            'type'        => 'text',
            'placeholder' => 'Удостоверение о повышении квалификации',
        ),
        'document_file' => array(
            'label' => 'Образцы документов (файлы из «Медиафайлов»)',
            'type'  => 'files',
        ),
        'validity' => array(
            'label'       => 'Срок действия документа',
            'type'        => 'text',
            'placeholder' => 'Бессрочно / 3 года',
        ),
        'requirements' => array(
            'label' => 'Требования к слушателям',
            'type'  => 'textarea',
        ),
        'price' => array(
            'label' => 'Стоимость, ₽',
            'type'  => 'number',
        ),
        'gc_offer_id' => array(
            'label'       => 'GetCourse: ID предложения (offer_id)',
            'type'        => 'number',
            'description' => 'Нужен для перехода на оплату. ID предложения смотрите в GetCourse: Продажи → Предложения.',
        ),
    );
}

function rcpk_course_meta($key, $post_id = null)
{
    return get_post_meta($post_id ?: get_the_ID(), '_rcpk_' . $key, true);
}

add_action('add_meta_boxes_course', function () {
    add_meta_box('rcpk_course_details', 'Параметры курса', 'rcpk_course_details_box', 'course', 'normal', 'high');
});

function rcpk_course_details_box($post)
{
    wp_nonce_field('rcpk_course_save', 'rcpk_course_nonce');
    echo '<p class="description">Краткое описание — поле «Отрывок», полное — основной текст записи.</p>';
    echo '<table class="form-table"><tbody>';
    foreach (rcpk_course_fields() as $key => $field) {
        $id    = 'rcpk_' . $key;
        $value = get_post_meta($post->ID, '_rcpk_' . $key, true);
        $ph    = isset($field['placeholder']) ? ' placeholder="' . esc_attr($field['placeholder']) . '"' : '';
        echo '<tr><th><label for="' . esc_attr($id) . '">' . esc_html($field['label']) . '</label></th><td>';
        if ($field['type'] === 'textarea') {
            echo '<textarea class="large-text" rows="5" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '"' . $ph . '>' . esc_textarea($value) . '</textarea>';
        } elseif ($field['type'] === 'files') {
            $ids = array_filter(array_map('absint', explode(',', (string) $value)));
            echo '<input type="hidden" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr(implode(',', $ids)) . '">';
            echo '<ul id="' . esc_attr($id) . '_preview" class="rcpk-file-preview">';
            foreach ($ids as $file_id) {
                echo '<li data-id="' . esc_attr($file_id) . '">' . wp_get_attachment_link($file_id, 'thumbnail', false, false)
                    . ' <a href="#" class="rcpk-file-item-remove" data-target="' . esc_attr($id) . '" data-id="' . esc_attr($file_id) . '">Убрать</a></li>';
            }
            echo '</ul>';
            echo '<p><button type="button" class="button rcpk-file-select" data-target="' . esc_attr($id) . '">Добавить файлы</button></p>';
        } else {
            $step = $field['type'] === 'number' ? ' min="0" step="1"' : '';
            echo '<input class="regular-text" type="' . esc_attr($field['type']) . '" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($value) . '"' . $ph . $step . '>';
        }
        if (!empty($field['description'])) {
            echo '<p class="description">' . esc_html($field['description']) . '</p>';
        }
        echo '</td></tr>';
    }
    echo '</tbody></table>';
}

add_action('save_post_course', function ($post_id) {
    if (!isset($_POST['rcpk_course_nonce']) || !wp_verify_nonce($_POST['rcpk_course_nonce'], 'rcpk_course_save')) {
        return;
    }
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach (rcpk_course_fields() as $key => $field) {
        $raw = isset($_POST['rcpk_' . $key]) ? wp_unslash($_POST['rcpk_' . $key]) : '';
        switch ($field['type']) {
            case 'number':
                $value = $raw === '' ? '' : (string) absint($raw);
                break;
            case 'url':
                $value = esc_url_raw($raw);
                break;
            case 'files':
                $ids   = array_filter(array_map('absint', explode(',', (string) $raw)));
                $value = implode(',', $ids);
                break;
            case 'textarea':
                $value = sanitize_textarea_field($raw);
                break;
            default:
                $value = sanitize_text_field($raw);
        }
        if ($value === '') {
            delete_post_meta($post_id, '_rcpk_' . $key);
        } else {
            update_post_meta($post_id, '_rcpk_' . $key, $value);
        }
    }
});

/* ---------- SEO-поля направлений и категорий ---------- */

function rcpk_direction_seo_fields()
{
    return array(
        'seo_title'       => array('label' => 'SEO title', 'type' => 'text'),
        'seo_description' => array('label' => 'SEO description', 'type' => 'textarea'),
        'h1'              => array('label' => 'Заголовок H1 (если пусто — название)', 'type' => 'text'),
        'intro'           => array('label' => 'SEO-текст страницы', 'type' => 'textarea'),
        'gc_name'         => array('label' => 'Название на GetCourse (для импорта, если рубрика названа иначе)', 'type' => 'text'),
    );
}

function rcpk_direction_meta($term_id, $key)
{
    return get_term_meta($term_id, '_rcpk_' . $key, true);
}

function rcpk_direction_image_field($term_id = 0)
{
    $image_id = $term_id ? (int) rcpk_direction_meta($term_id, 'image') : 0;
    $preview  = $image_id ? wp_get_attachment_image($image_id, 'medium') : '';
    ob_start();
?>
    <input type="hidden" name="rcpk_image" id="rcpk_image" value="<?php echo esc_attr($image_id); ?>">
    <div id="rcpk_image_preview" style="margin-bottom:10px;"><?php echo $preview; ?></div>
    <button type="button" class="button" id="rcpk_image_select">Выбрать изображение</button>
    <button type="button" class="button" id="rcpk_image_remove" <?php echo $image_id ? '' : 'style="display:none;"'; ?>>Удалить</button>
<?php
    return ob_get_clean();
}

add_action('course_direction_add_form_fields', function () {
    wp_nonce_field('rcpk_direction_save', 'rcpk_direction_nonce');
    foreach (rcpk_direction_seo_fields() as $key => $field) {
        echo '<div class="form-field"><label for="rcpk_' . esc_attr($key) . '">' . esc_html($field['label']) . '</label>';
        echo $field['type'] === 'textarea'
            ? '<textarea id="rcpk_' . esc_attr($key) . '" name="rcpk_' . esc_attr($key) . '" rows="4"></textarea>'
            : '<input type="text" id="rcpk_' . esc_attr($key) . '" name="rcpk_' . esc_attr($key) . '">';
        echo '</div>';
    }
    echo '<div class="form-field"><label>Фоновое изображение hero-блока</label>' . rcpk_direction_image_field(0) . '</div>';
});

add_action('course_direction_edit_form_fields', function ($term) {
    wp_nonce_field('rcpk_direction_save', 'rcpk_direction_nonce');
    foreach (rcpk_direction_seo_fields() as $key => $field) {
        $value = rcpk_direction_meta($term->term_id, $key);
        echo '<tr class="form-field"><th><label for="rcpk_' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th><td>';
        echo $field['type'] === 'textarea'
            ? '<textarea id="rcpk_' . esc_attr($key) . '" name="rcpk_' . esc_attr($key) . '" rows="5">' . esc_textarea($value) . '</textarea>'
            : '<input type="text" id="rcpk_' . esc_attr($key) . '" name="rcpk_' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
        echo '</td></tr>';
    }
    echo '<tr class="form-field"><th><label>Фоновое изображение hero-блока</label></th><td>' . rcpk_direction_image_field($term->term_id) . '</td></tr>';
});

add_action('admin_enqueue_scripts', function ($hook) {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'course' || !in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
    }
    wp_enqueue_media();
    wp_add_inline_script('jquery-core', "
jQuery(function($){
    var frame;

    function addItem(target, attachment){
        var icon = (attachment.sizes && attachment.sizes.thumbnail) ? attachment.sizes.thumbnail.url : attachment.icon;
        var ids  = $('#' + target).val().split(',').filter(Boolean);
        if (ids.indexOf(String(attachment.id)) !== -1) { return; }
        ids.push(attachment.id);
        $('#' + target).val(ids.join(','));
        $('#' + target + '_preview').append(
            '<li data-id=\"' + attachment.id + '\"><a href=\"' + attachment.url + '\" target=\"_blank\" rel=\"noopener\"><img src=\"' + icon + '\" style=\"max-width:64px;height:auto;vertical-align:middle;\"> ' +
            (attachment.filename || attachment.title) + '</a> <a href=\"#\" class=\"rcpk-file-item-remove\" data-target=\"' + target + '\" data-id=\"' + attachment.id + '\">Убрать</a></li>'
        );
    }

    $(document).on('click', '.rcpk-file-select', function(e){
        e.preventDefault();
        var target = $(this).data('target');
        frame = wp.media({ title: 'Выберите файлы', button: { text: 'Использовать' }, multiple: true });
        frame.on('select', function(){
            frame.state().get('selection').each(function(attachment){
                addItem(target, attachment.toJSON());
            });
        });
        frame.open();
    });

    $(document).on('click', '.rcpk-file-item-remove', function(e){
        e.preventDefault();
        var target = $(this).data('target');
        var id     = String($(this).data('id'));
        var ids    = $('#' + target).val().split(',').filter(function(v){ return v && v !== id; });
        $('#' + target).val(ids.join(','));
        $(this).closest('li').remove();
    });
});
    ");
});

add_action('admin_enqueue_scripts', function ($hook) {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || empty($screen->taxonomy) || $screen->taxonomy !== 'course_direction') {
        return;
    }
    wp_enqueue_media();
    wp_add_inline_script('jquery-core', "
jQuery(function($){
    var frame;
    $(document).on('click', '#rcpk_image_select', function(e){
        e.preventDefault();
        if (frame) { frame.open(); return; }
        frame = wp.media({ title: 'Выберите изображение', button: { text: 'Использовать' }, multiple: false });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $('#rcpk_image').val(attachment.id);
            var url = (attachment.sizes && attachment.sizes.medium) ? attachment.sizes.medium.url : attachment.url;
            $('#rcpk_image_preview').html('<img src=\"' + url + '\" style=\"max-width:300px;height:auto;\">');
            $('#rcpk_image_remove').show();
        });
        frame.open();
    });
    $(document).on('click', '#rcpk_image_remove', function(e){
        e.preventDefault();
        $('#rcpk_image').val('');
        $('#rcpk_image_preview').html('');
        $(this).hide();
    });
});
    ");
});

add_action('created_course_direction', 'rcpk_direction_save');
add_action('edited_course_direction', 'rcpk_direction_save');
function rcpk_direction_save($term_id)
{
    if (!isset($_POST['rcpk_direction_nonce']) || !wp_verify_nonce($_POST['rcpk_direction_nonce'], 'rcpk_direction_save')) {
        return;
    }
    if (!current_user_can('manage_categories')) {
        return;
    }
    foreach (rcpk_direction_seo_fields() as $key => $field) {
        $raw   = isset($_POST['rcpk_' . $key]) ? wp_unslash($_POST['rcpk_' . $key]) : '';
        $value = $field['type'] === 'textarea' ? sanitize_textarea_field($raw) : sanitize_text_field($raw);
        if ($value === '') {
            delete_term_meta($term_id, '_rcpk_' . $key);
        } else {
            update_term_meta($term_id, '_rcpk_' . $key, $value);
        }
    }

    $image_id = isset($_POST['rcpk_image']) ? absint($_POST['rcpk_image']) : 0;
    if ($image_id) {
        update_term_meta($term_id, '_rcpk_image', $image_id);
    } else {
        delete_term_meta($term_id, '_rcpk_image');
    }
}
