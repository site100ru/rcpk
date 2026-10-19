<?php
/**
 * Каталог направлений курсов: /kursy/
 * Первый экран и блоки под ним — как на странице направления (taxonomy-course_direction.php):
 * hero без поиска, ниже «Поиск», «Популярные направления», баннер акции; затем каталог направлений.
 */

$GLOBALS['rcpk_hero_bg_image']    = get_theme_mod('rcpk_catalog_hero_image', '');
$GLOBALS['rcpk_hero_extra_class'] = 'rcpk-hero--direction';
add_action('rcpk_internal_header_hero', function () {
    get_template_part('template-parts/course/hero', null, array('title' => 'Каталог направлений курсов'));
});
get_header(); ?>

<main class="courses-page rcpk-dir">

    <?php get_template_part('template-parts/course/dir-filters'); ?>

    <!-- Каталог направлений — общий блок с главной страницей -->
    <?php get_template_part('template-parts/catalog-output/catalog-output'); ?>

    <?php get_template_part('template-parts/course/to-top'); ?>

</main>

<?php get_footer(); ?>
