<?php
/**
 * Поиск курсов: /kursy/poisk/?q=...
 * Первый экран и блоки под ним — как на странице направления (taxonomy-course_direction.php):
 * hero без поиска, ниже «Поиск», «Популярные направления», баннер акции; затем результаты карточками card-direction.
 */
global $wp_query;

$filters = rcpk_course_filters_from_request();
$hours   = rcpk_course_hours_from_request(); // фильтр «Количество часов» (?chasy=N)
if ($filters['q'] !== '') {
    $title = 'Результаты поиска: «' . $filters['q'] . '»';
} elseif ($hours) {
    $title = 'Курсы на ' . $hours . ' ' . rcpk_plural($hours, 'час', 'часа', 'часов');
} else {
    $title = 'Поиск курсов';
}

$GLOBALS['rcpk_hero_bg_image']   = get_theme_mod(
    'rcpk_neutral_hero_image',
    get_theme_mod('rcpk_catalog_hero_image', '')
);
$GLOBALS['rcpk_hero_extra_class'] = 'rcpk-hero--direction';
add_action('rcpk_internal_header_hero', function () use ($title) {
    get_template_part('template-parts/course/hero', null, array('title' => $title));
});
get_header(); ?>

<main class="courses-page rcpk-dir">

    <?php get_template_part('template-parts/course/dir-filters', null, array('search_value' => $filters['q'], 'hours' => $hours)); ?>

    <div class="section section--u-i1mm52hsj rcpk-dir__section rcpk-dir__section--courses" id="courses">
        <div class="div div--u-ihuo14hdq">
            <h2 class="rcpk-dir__title">Курсы</h2>

            <?php if ($filters['q'] === '' && !$hours) : ?>
                <p class="rcpk-dir__empty">Введите название курса или специальность.</p>
            <?php elseif ($wp_query->have_posts()) : ?>
                <p class="courses-results__count">Найдено курсов: <?php echo (int) $wp_query->found_posts; ?></p>
                <div class="rcpk-dir__grid">
                    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                        <?php get_template_part('template-parts/course/card-direction'); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <nav class="courses-results__pagination">
                    <?php echo rcpk_course_pagination($wp_query, $filters); ?>
                </nav>
            <?php else : ?>
                <p class="rcpk-dir__empty">
                    По вашему запросу курсов не найдено.
                    <a href="<?php echo esc_url(get_post_type_archive_link('course')); ?>">Перейти в каталог направлений</a>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <?php get_template_part('template-parts/course/to-top'); ?>

</main>

<?php get_footer(); ?>
