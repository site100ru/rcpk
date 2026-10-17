<?php
/**
 * Поиск курсов: /kursy/poisk/?q=...
 * Hero и крошки как в archive-course.php (/kursy/), карточки и сетка — как на странице
 * направления (template-parts/course/card-direction.php), баннер заявки, футер.
 */
global $wp_query;

$filters = rcpk_course_filters_from_request();
$action  = rcpk_course_search_url();
$title   = $filters['q'] !== '' ? 'Результаты поиска: «' . $filters['q'] . '»' : 'Поиск курсов';

$GLOBALS['rcpk_hero_bg_image']   = get_theme_mod(
    'rcpk_neutral_hero_image',
    get_theme_mod('rcpk_catalog_hero_image', '')
);
$GLOBALS['rcpk_hero_extra_class'] = 'rcpk-hero--sm';
add_action('rcpk_internal_header_hero', function () use ($action, $title, $filters) {
    get_template_part('template-parts/course/hero', null, array(
        'title'         => $title,
        'search_action' => $action,
        'search_value'  => $filters['q'],
    ));
});
get_header(); ?>

<main class="courses-page rcpk-dir">

    <!-- Контейнер как на остальных страницах сайта: section--u-i1mm52hsj + div--u-ihuo14hdq -->
    <div class="section section--u-i1mm52hsj rcpk-container--top">
        <div class="div div--u-ihuo14hdq">
            <nav class="courses-page__crumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> /
                <a href="<?php echo esc_url(get_post_type_archive_link('course')); ?>">Курсы</a> /
                <span>Поиск</span>
            </nav>
        </div>
    </div>

    <!-- Результаты поиска — карточки как на странице направления (card-direction) -->
    <div class="section section--u-i1mm52hsj">
        <div class="div div--u-ihuo14hdq">
            <div class="heading heading--u-iwz7id95u">
                <span class="text-block-wrap-div"><?php echo esc_html($title); ?></span>
            </div>
            <div class="div div--u-ix97q2o2o">
                <?php if ($filters['q'] === '') : ?>
                    <p>Введите название курса или специальность.</p>
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
                    <p class="courses-results__empty">По вашему запросу курсов не найдено.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="section section--u-i1mm52hsj">
        <div class="div div--u-ihuo14hdq">
            <?php get_template_part('template-parts/course/help-banner'); ?>
        </div>
    </div>

</main>

<?php get_footer(); ?>
