<?php
/**
 * Направление (/kursy/napravlenie/{направление}/) — курсы направления, включая дочерние категории.
 * Категория (/kursy/napravlenie/{направление}/{категория}/) — SEO-страница под аудиторию.
 * Макет: Figma, вкладка develop, фреймы «Archive desktop» / «Archive mobile».
 *
 * Фильтры (GET) работают только внутри текущего направления:
 * q — поиск по названию, chasy — объём в часах, teg — слаг тега курса.
 * (не «tag»: это зарезервированная query var WordPress для рубрик записей)
 */
$term     = get_queried_object();
$h1       = rcpk_direction_meta($term->term_id, 'h1') ?: $term->name;
$intro    = rcpk_direction_meta($term->term_id, 'intro');
$base_url = get_term_link($term);

$term_ids    = array($term->term_id);
$descendants = get_term_children($term->term_id, 'course_direction');
if (!is_wp_error($descendants)) {
    $term_ids = array_merge($term_ids, $descendants);
}
$direction_tax = array(
    'taxonomy' => 'course_direction',
    'field'    => 'term_id',
    'terms'    => $term_ids,
);

$filter_q     = isset($_GET['q']) ? mb_substr(sanitize_text_field(wp_unslash($_GET['q'])), 0, 100) : '';
$filter_hours = isset($_GET['chasy']) ? absint($_GET['chasy']) : 0;
$filter_tag   = isset($_GET['teg']) ? sanitize_title(wp_unslash($_GET['teg'])) : '';

/**
 * Ссылка на эту же страницу с изменёнными фильтрами (остальные сохраняются), с якорем на список курсов.
 */
function rcpk_direction_filter_url($base_url, $changes)
{
    $params = array();
    foreach (array('q', 'chasy', 'teg') as $key) {
        if (isset($_GET[$key]) && $_GET[$key] !== '') {
            $params[$key] = sanitize_text_field(wp_unslash($_GET[$key]));
        }
    }
    foreach ($changes as $key => $value) {
        if ($value === '' || $value === 0) {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }
    }
    return add_query_arg(array_map('rawurlencode', $params), $base_url) . '#courses';
}

// Все курсы направления — источник тегов «Количество часов» и «Популярные направления»
$direction_course_ids = get_posts(array(
    'post_type'      => 'course',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'tax_query'      => array($direction_tax),
));

$hours_list = array();
foreach ($direction_course_ids as $course_id) {
    $h = (int) rcpk_course_meta('hours', $course_id);
    if ($h > 0) {
        $hours_list[$h] = true;
    }
}
$hours_list = array_keys($hours_list);
sort($hours_list);

// Популярные направления: дочерние категории (их страницы — внутри этого же направления)
// + теги курсов этого направления (фильтр на этой же странице), самые частые первыми.
$popular = array();
$children = get_terms(array('taxonomy' => 'course_direction', 'parent' => $term->term_id, 'hide_empty' => true));
if ($children && !is_wp_error($children)) {
    foreach ($children as $child) {
        $popular[mb_strtolower($child->name)] = array(
            'name'   => $child->name,
            'url'    => get_term_link($child),
            'active' => false,
        );
    }
}
$tag_rows = $direction_course_ids ? wp_get_object_terms($direction_course_ids, 'course_tag', array('fields' => 'all_with_object_id')) : array();
if ($tag_rows && !is_wp_error($tag_rows)) {
    $tag_counts = array();
    foreach ($tag_rows as $row) {
        if (!isset($tag_counts[$row->term_id])) {
            $tag_counts[$row->term_id] = array('term' => $row, 'count' => 0);
        }
        $tag_counts[$row->term_id]['count']++;
    }
    uasort($tag_counts, function ($left, $right) {
        return $right['count'] <=> $left['count'];
    });
    foreach (array_column($tag_counts, 'term') as $tag) {
        $key = mb_strtolower($tag->name);
        if (isset($popular[$key])) {
            continue;
        }
        $active        = $tag->slug === $filter_tag;
        $popular[$key] = array(
            'name'   => $tag->name,
            'url'    => rcpk_direction_filter_url($base_url, array('teg' => $active ? '' : $tag->slug)),
            'active' => $active,
        );
    }
}

$courses_args = array(
    'post_type'      => 'course',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'tax_query'      => array($direction_tax),
);
if ($filter_q !== '') {
    $courses_args['s'] = $filter_q;
}
if ($filter_hours) {
    $courses_args['meta_query'] = array(array(
        'key'   => '_rcpk_hours',
        'value' => $filter_hours,
        'type'  => 'NUMERIC',
    ));
}
if ($filter_tag !== '') {
    $courses_args['tax_query'][] = array(
        'taxonomy' => 'course_tag',
        'field'    => 'slug',
        'terms'    => $filter_tag,
    );
}
$courses_query = new WP_Query($courses_args);
$has_filters   = $filter_q !== '' || $filter_hours || $filter_tag !== '';

$pill_arrow = '<svg class="rcpk-dir__pill-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><rect x="5.65685" y="11.3136" width="1.6" height="8" rx="0.8" transform="rotate(-135 5.65685 11.3136)" fill="currentColor"/><rect x="4.52547" y="1.13146" width="1.6" height="8" rx="0.8" transform="rotate(-45 4.52547 1.13146)" fill="currentColor"/></svg>';

// Фоновое изображение hero-блока для этого направления (см. inc/courses/meta.php: поле "image")
$hero_image_id  = (int) rcpk_direction_meta($term->term_id, 'image');
$hero_image_url = $hero_image_id ? wp_get_attachment_image_url($hero_image_id, 'full') : '';

$GLOBALS['rcpk_hero_bg_image']    = $hero_image_url;
$GLOBALS['rcpk_hero_extra_class'] = 'rcpk-hero--direction';
add_action('rcpk_internal_header_hero', function () use ($h1) {
    get_template_part('template-parts/course/hero', null, array('title' => $h1));
});
get_header(); ?>

<main class="courses-page rcpk-dir">

    <div class="section section--u-i1mm52hsj rcpk-dir__section rcpk-dir__section--filters">
        <div class="div div--u-ihuo14hdq">

            <?php // data-dir-* — зоны, которые direction-page.js подменяет при AJAX-фильтрации ?>
            <div class="rcpk-dir__filters" data-dir-filters>
            <section class="rcpk-dir__block">
                <h2 class="rcpk-dir__subtitle">Поиск</h2>
                <form class="rcpk-dir__search" method="get" action="<?php echo esc_url($base_url); ?>#courses">
                    <input type="search" name="q" class="rcpk-dir__search-input" placeholder="Название курса или специальность" value="<?php echo esc_attr($filter_q); ?>">
                    <?php if ($filter_hours) : ?>
                        <input type="hidden" name="chasy" value="<?php echo esc_attr($filter_hours); ?>">
                    <?php endif; ?>
                    <?php if ($filter_tag !== '') : ?>
                        <input type="hidden" name="teg" value="<?php echo esc_attr($filter_tag); ?>">
                    <?php endif; ?>
                    <button type="submit" class="rcpk-dir__search-button">Найти</button>
                </form>
            </section>

            <?php if ($hours_list) : ?>
                <section class="rcpk-dir__block">
                    <h2 class="rcpk-dir__subtitle">Количество часов</h2>
                    <div class="rcpk-dir__pills rcpk-dir__pills--hours">
                        <?php foreach ($hours_list as $hours) :
                            $active = $hours === $filter_hours; ?>
                            <a class="rcpk-dir__pill<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url(rcpk_direction_filter_url($base_url, array('chasy' => $active ? '' : $hours))); ?>"<?php echo $active ? ' aria-current="true"' : ''; ?>>
                                <?php echo esc_html($hours . ' ' . rcpk_plural($hours, 'час', 'часа', 'часов')); ?>
                                <?php echo $pill_arrow; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($popular) : ?>
                <section class="rcpk-dir__block">
                    <h2 class="rcpk-dir__subtitle">Популярные направления</h2>
                    <div class="rcpk-dir__pills rcpk-dir__pills--popular">
                        <?php foreach ($popular as $item) : ?>
                            <a class="rcpk-dir__pill<?php echo $item['active'] ? ' is-active' : ''; ?>" href="<?php echo esc_url($item['url']); ?>"<?php echo $item['active'] ? ' aria-current="true"' : ''; ?>>
                                <?php echo esc_html($item['name']); ?>
                                <?php echo $pill_arrow; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
            </div>

            <?php get_template_part('template-parts/course/promo-banner'); ?>

        </div>
    </div>

    <div class="section section--u-i1mm52hsj rcpk-dir__section rcpk-dir__section--courses" id="courses">
        <div class="div div--u-ihuo14hdq" data-dir-results aria-live="polite">
            <h2 class="rcpk-dir__title">Курсы</h2>

            <?php if ($courses_query->have_posts()) : ?>
                <div class="rcpk-dir__grid">
                    <?php while ($courses_query->have_posts()) : $courses_query->the_post(); ?>
                        <?php get_template_part('template-parts/course/card-direction'); ?>
                    <?php endwhile; ?>
                </div>
            <?php elseif ($has_filters) : ?>
                <p class="rcpk-dir__empty">
                    По выбранным параметрам курсов не найдено.
                    <a href="<?php echo esc_url($base_url); ?>#courses">Показать все курсы направления</a>
                </p>
            <?php else : ?>
                <p class="rcpk-dir__empty">Курсы пока не добавлены.</p>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>

    <div data-dir-seo>
        <?php if ($intro && !$has_filters) : ?>
            <div class="section section--u-i1mm52hsj rcpk-dir__section">
                <div class="div div--u-ihuo14hdq">
                    <div class="courses-page__seo-text"><?php echo wp_kses_post(wpautop($intro)); ?></div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <a href="#" class="rcpk-to-top" aria-label="Наверх страницы">
        <span class="rcpk-to-top__inner">
            <svg width="30" height="40" viewBox="0 0 30 40" fill="none" aria-hidden="true"><path d="M15 0L30 40L15 31L0 40L15 0Z" fill="currentColor"/></svg>
            <span class="rcpk-to-top__label">Вверх</span>
        </span>
    </a>

</main>

<?php get_footer(); ?>
