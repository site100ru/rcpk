<?php
/**
 * Поиск, фильтр и сортировка курсов (обычная загрузка страницы + AJAX), SEO листингов.
 *
 * GET-параметры: q — поисковая строка, dir[] — слаги направлений/категорий, sort — порядок.
 */

defined('ABSPATH') || exit;

const RCPK_COURSES_PER_PAGE = 12;

function rcpk_course_sort_options()
{
    return array(
        ''           => 'По умолчанию',
        'title'      => 'По названию',
        'price_asc'  => 'Сначала дешевле',
        'price_desc' => 'Сначала дороже',
        'hours_asc'  => 'Меньше часов',
        'hours_desc' => 'Больше часов',
    );
}

function rcpk_course_filters_from_request($source = null)
{
    $source = $source ?? $_GET;
    $q      = isset($source['q']) ? sanitize_text_field(wp_unslash($source['q'])) : '';
    $dirs   = isset($source['dir']) ? array_map('sanitize_title', (array) wp_unslash($source['dir'])) : array();
    $sort   = isset($source['sort']) ? sanitize_key($source['sort']) : '';

    return array(
        'q'    => mb_substr($q, 0, 100),
        'dir'  => array_values(array_filter($dirs)),
        'sort' => array_key_exists($sort, rcpk_course_sort_options()) ? $sort : '',
    );
}

function rcpk_course_has_filters($filters)
{
    return $filters['q'] !== '' || !empty($filters['dir']) || $filters['sort'] !== '';
}

function rcpk_course_filter_args($filters)
{
    $args = array();

    if ($filters['q'] !== '') {
        $args['s'] = $filters['q'];
    }

    if (!empty($filters['dir'])) {
        $args['tax_query'] = array(array(
            'taxonomy'         => 'course_direction',
            'field'            => 'slug',
            'terms'            => $filters['dir'],
            'include_children' => true,
        ));
    }

    $sort_map = array(
        'title'      => array('orderby' => 'title', 'order' => 'ASC'),
        'price_asc'  => array('meta_key' => '_rcpk_price', 'orderby' => 'meta_value_num', 'order' => 'ASC'),
        'price_desc' => array('meta_key' => '_rcpk_price', 'orderby' => 'meta_value_num', 'order' => 'DESC'),
        'hours_asc'  => array('meta_key' => '_rcpk_hours', 'orderby' => 'meta_value_num', 'order' => 'ASC'),
        'hours_desc' => array('meta_key' => '_rcpk_hours', 'orderby' => 'meta_value_num', 'order' => 'DESC'),
    );
    if (isset($sort_map[$filters['sort']])) {
        $args = array_merge($args, $sort_map[$filters['sort']]);
    }

    return $args;
}

add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    $is_listing = $query->is_post_type_archive('course') || $query->is_tax('course_direction') || $query->get('rcpk_course_search');
    if (!$is_listing) {
        return;
    }

    $query->set('posts_per_page', RCPK_COURSES_PER_PAGE);

    foreach (rcpk_course_filter_args(rcpk_course_filters_from_request()) as $key => $value) {
        if ($key === 'tax_query') {
            $value = array_merge((array) $query->get('tax_query'), $value);
        }
        $query->set($key, $value);
    }
});

// Страница поиска без запроса не должна выводить весь каталог
add_filter('posts_where', function ($where, $query) {
    if ($query->is_main_query() && $query->get('rcpk_course_search') && $query->get('s') === '') {
        $where .= ' AND 1=0';
    }
    return $where;
}, 10, 2);

// Поиск курсов — только по заголовку, а не по всему тексту/анонсу
add_filter('posts_search', function ($search, $query) {
    global $wpdb;
    if ($search === '' || $query->get('post_type') !== 'course' || $query->get('s') === '') {
        return $search;
    }
    $term = $query->get('s');
    return $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s ", '%' . $wpdb->esc_like($term) . '%');
}, 10, 2);

/* ---------- AJAX ---------- */

add_action('wp_ajax_rcpk_courses', 'rcpk_courses_ajax');
add_action('wp_ajax_nopriv_rcpk_courses', 'rcpk_courses_ajax');
function rcpk_courses_ajax()
{
    $filters = rcpk_course_filters_from_request($_POST);
    $term_id = isset($_POST['term']) ? absint($_POST['term']) : 0;
    $paged   = isset($_POST['paged']) ? max(1, absint($_POST['paged'])) : 1;
    $base    = isset($_POST['base']) ? esc_url_raw(wp_unslash($_POST['base'])) : '';

    if (wp_parse_url($base, PHP_URL_HOST) !== wp_parse_url(home_url(), PHP_URL_HOST)) {
        $base = get_post_type_archive_link('course');
    }

    $args = array_merge(array(
        'post_type'      => 'course',
        'post_status'    => 'publish',
        'posts_per_page' => RCPK_COURSES_PER_PAGE,
        'paged'          => $paged,
    ), rcpk_course_filter_args($filters));

    if ($term_id && term_exists($term_id, 'course_direction')) {
        $args['tax_query']   = isset($args['tax_query']) ? $args['tax_query'] : array();
        $args['tax_query'][] = array(
            'taxonomy'         => 'course_direction',
            'field'            => 'term_id',
            'terms'            => $term_id,
            'include_children' => true,
        );
    }

    $query = new WP_Query($args);

    ob_start();
    get_template_part('template-parts/course/results', null, array(
        'query'   => $query,
        'filters' => $filters,
        'base'    => $base,
    ));
    wp_reset_postdata();

    wp_send_json_success(array(
        'html'  => ob_get_clean(),
        'found' => (int) $query->found_posts,
    ));
}

add_action('wp_enqueue_scripts', function () {
    if (!rcpk_is_course_listing()) {
        return;
    }
    wp_enqueue_script('rcpk-courses-filter', get_template_directory_uri() . '/assets/js/courses-filter.js', array(), '1.0', true);
    wp_localize_script('rcpk-courses-filter', 'rcpkCourses', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ));
});

/* ---------- Пагинация ---------- */

function rcpk_course_pagination($query, $filters, $base = '')
{
    if ($query->max_num_pages < 2) {
        return '';
    }
    $base = $base ?: get_pagenum_link(1, false);
    $base = trailingslashit(preg_replace('#/page/\d+/?$#', '', strtok($base, '?')));

    $add_args = array();
    if ($filters['q'] !== '') {
        $add_args['q'] = $filters['q'];
    }
    if (!empty($filters['dir'])) {
        $add_args['dir'] = $filters['dir'];
    }
    if ($filters['sort'] !== '') {
        $add_args['sort'] = $filters['sort'];
    }

    return paginate_links(array(
        'base'     => $base . '%_%',
        'format'   => 'page/%#%/',
        'current'  => max(1, (int) $query->get('paged')),
        'total'    => $query->max_num_pages,
        'add_args' => $add_args,
        'type'     => 'list',
    ));
}

/* ---------- SEO ---------- */

function rcpk_seo_plugin_active()
{
    return defined('WPSEO_VERSION') || class_exists('RankMath');
}

add_filter('pre_get_document_title', function ($title) {
    if (rcpk_seo_plugin_active()) {
        return $title;
    }
    if (is_tax('course_direction')) {
        $seo_title = rcpk_direction_meta(get_queried_object_id(), 'seo_title');
        if ($seo_title) {
            return $seo_title;
        }
    }
    if (get_query_var('rcpk_course_search')) {
        $q = get_query_var('s');
        return ($q !== '' ? 'Поиск: ' . $q . ' — ' : 'Поиск курсов — ') . get_bloginfo('name');
    }
    return $title;
});

add_action('wp_head', function () {
    if (rcpk_seo_plugin_active()) {
        return;
    }
    $description = '';
    if (is_tax('course_direction')) {
        $term        = get_queried_object();
        $description = rcpk_direction_meta($term->term_id, 'seo_description') ?: $term->description;
    } elseif (is_singular('course')) {
        $description = get_post_field('post_excerpt', get_queried_object_id());
    }
    $description = trim(wp_strip_all_tags($description));
    if ($description !== '') {
        echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 30, '…')) . '">' . "\n";
    }
}, 1);

// Отфильтрованные варианты и поиск не индексируем, чтобы не плодить дубли
add_filter('wp_robots', function ($robots) {
    if (!rcpk_is_course_listing()) {
        return $robots;
    }
    // chasy/teg — фильтры страницы направления (taxonomy-course_direction.php)
    if (get_query_var('rcpk_course_search') || rcpk_course_has_filters(rcpk_course_filters_from_request())
        || !empty($_GET['chasy']) || !empty($_GET['teg'])) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
    }
    return $robots;
});
