<?php
/**
 * Курсы: тип записи, таксономия направлений/категорий, URL-структура.
 *
 * /kursy/                                  — все курсы
 * /kursy/napravlenie/{направление}/        — все курсы направления
 * /kursy/napravlenie/{направление}/{кат}/  — категория направления (SEO-страница)
 * /kursy/kurs/{курс}/                      — страница курса
 * /kursy/poisk/?q=...                      — страница поиска
 */

defined('ABSPATH') || exit;

const RCPK_COURSES_REWRITE_VERSION = '3';

add_action('init', 'rcpk_courses_register', 5);
function rcpk_courses_register()
{
    register_post_type('course', array(
        'labels' => array(
            'name'               => 'Курсы',
            'singular_name'      => 'Курс',
            'add_new'            => 'Добавить курс',
            'add_new_item'       => 'Добавить новый курс',
            'edit_item'          => 'Редактировать курс',
            'new_item'           => 'Новый курс',
            'view_item'          => 'Посмотреть курс',
            'search_items'       => 'Найти курс',
            'not_found'          => 'Курсов не найдено',
            'not_found_in_trash' => 'В корзине курсов не найдено',
            'menu_name'          => 'Курсы',
        ),
        'public'       => true,
        'has_archive'  => 'kursy',
        'rewrite'      => array('slug' => 'kursy/kurs', 'with_front' => false),
        'menu_icon'    => 'dashicons-welcome-learn-more',
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));

    // Верхний уровень — направление, дочерние термины — категории (учителя математики и т.п.)
    register_taxonomy('course_direction', 'course', array(
        'labels' => array(
            'name'              => 'Направления и категории',
            'singular_name'     => 'Направление / категория',
            'search_items'      => 'Найти',
            'all_items'         => 'Все направления',
            'parent_item'       => 'Направление',
            'parent_item_colon' => 'Направление:',
            'edit_item'         => 'Редактировать',
            'update_item'       => 'Обновить',
            'add_new_item'      => 'Добавить направление или категорию',
            'new_item_name'     => 'Название',
            'menu_name'         => 'Направления',
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => array(
            'slug'         => 'kursy/napravlenie',
            'with_front'   => false,
            'hierarchical' => true,
        ),
    ));

    register_taxonomy('course_tag', 'course', array(
        'labels' => array(
            'name'          => 'Теги',
            'singular_name' => 'Тег',
            'search_items'  => 'Найти тег',
            'all_items'     => 'Все теги',
            'edit_item'     => 'Редактировать тег',
            'update_item'   => 'Обновить тег',
            'add_new_item'  => 'Добавить тег',
            'new_item_name' => 'Название тега',
            'menu_name'     => 'Теги',
        ),
        'hierarchical'      => false,
        'public'            => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => array(
            'slug'       => 'kursy/tag',
            'with_front' => false,
        ),
    ));

    add_rewrite_rule('^kursy/poisk/?$', 'index.php?post_type=course&rcpk_course_search=1', 'top');
    add_rewrite_rule('^kursy/poisk/page/([0-9]+)/?$', 'index.php?post_type=course&rcpk_course_search=1&paged=$matches[1]', 'top');

    // /kursy/napravlenie/ без конкретного направления — тот же экран, что и /kursy/ (без реального термина WP отдаёт 404)
    add_rewrite_rule('^kursy/napravlenie/?$', 'index.php?post_type=course&rcpk_direction_root=1', 'top');
}

add_filter('query_vars', function ($vars) {
    $vars[] = 'rcpk_course_search';
    $vars[] = 'rcpk_direction_root';
    return $vars;
});

// Тема не получает register_activation_hook, поэтому сбрасываем правила один раз при смене версии
add_action('init', function () {
    if (get_option('rcpk_courses_rewrite_version') !== RCPK_COURSES_REWRITE_VERSION) {
        flush_rewrite_rules(false);
        update_option('rcpk_courses_rewrite_version', RCPK_COURSES_REWRITE_VERSION);
    }
}, 99);

add_filter('template_include', function ($template) {
    if (get_query_var('rcpk_course_search')) {
        $search = locate_template('search-course.php');
        if ($search) {
            return $search;
        }
    }
    if (get_query_var('rcpk_direction_root')) {
        $archive = locate_template('archive-course.php');
        if ($archive) {
            return $archive;
        }
    }
    return $template;
});

// /kursy/napravlenie/ выводит то же самое, что и /kursy/ — не дублируем это в поиске
add_filter('wp_robots', function ($robots) {
    if (get_query_var('rcpk_direction_root')) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
    }
    return $robots;
});
add_action('wp_head', function () {
    if (get_query_var('rcpk_direction_root')) {
        echo '<link rel="canonical" href="' . esc_url(get_post_type_archive_link('course')) . '">' . "\n";
    }
}, 1);

function rcpk_is_course_listing()
{
    return is_post_type_archive('course') || is_tax('course_direction') || get_query_var('rcpk_course_search') || get_query_var('rcpk_direction_root');
}

function rcpk_course_search_url()
{
    return home_url('/kursy/poisk/');
}

/**
 * <option> для селектов «Выберите направление курса» в формах заявки:
 * верхнеуровневые направления course_direction (вместо старого типа записи "catalog").
 */
function rcpk_direction_select_options($option_class = '')
{
    $directions = get_terms(array(
        'taxonomy'   => 'course_direction',
        'parent'     => 0,
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));
    if (!$directions || is_wp_error($directions)) {
        return;
    }
    $class = $option_class ? ' class="' . esc_attr($option_class) . '"' : '';
    foreach ($directions as $direction) {
        echo '<option value="' . esc_attr($direction->name) . '"' . $class . '>' . esc_html($direction->name) . '</option>';
    }
}

/**
 * Сводка по курсам направления (включая дочерние категории): диапазоны часов,
 * виды документов и сроков действия — для карточки направления в каталоге /kursy/.
 */
function rcpk_direction_courses_summary($term_id)
{
    $term_ids   = array($term_id);
    $descendants = get_term_children($term_id, 'course_direction');
    if (!is_wp_error($descendants)) {
        $term_ids = array_merge($term_ids, $descendants);
    }

    $courses = get_posts(array(
        'post_type'      => 'course',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => array(array(
            'taxonomy' => 'course_direction',
            'field'    => 'term_id',
            'terms'    => $term_ids,
        )),
    ));

    $hours = $documents = $validity = array();
    foreach ($courses as $course_id) {
        $h = rcpk_course_meta('hours', $course_id);
        $d = rcpk_course_meta('document', $course_id);
        $v = rcpk_course_meta('validity', $course_id);
        if ($h) {
            $hours[(int) $h] = true;
        }
        if ($d) {
            $documents[$d] = true;
        }
        if ($v) {
            $validity[$v] = true;
        }
    }
    ksort($hours);

    return array(
        'count'     => count($courses),
        'hours'     => implode('/', array_keys($hours)),
        'documents' => implode(' / ', array_keys($documents)),
        'validity'  => implode(' / ', array_keys($validity)),
    );
}
