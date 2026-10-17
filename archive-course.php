<?php
/**
 * Каталог направлений курсов: /kursy/
 * Верхнеуровневые направления — карточками (как на главной), популярные категории — тегами.
 */

$popular = get_terms(array(
    'taxonomy'   => 'course_tag',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 30,
));

$GLOBALS['rcpk_hero_bg_image'] = get_theme_mod('rcpk_catalog_hero_image', '');
add_action('rcpk_internal_header_hero', function () {
    get_template_part('template-parts/course/hero', null, array(
        'title'         => 'Каталог направлений курсов',
        'search_action' => rcpk_course_search_url(),
        'search_value'  => '',
    ));
});
get_header(); ?>

<main class="courses-page">

    <!-- Контейнер как на остальных страницах сайта: section--u-i1mm52hsj + div--u-ihuo14hdq -->
    <div class="section section--u-i1mm52hsj rcpk-container--top">
        <div class="div div--u-ihuo14hdq">
            <nav class="courses-page__crumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> / <span>Курсы</span>
            </nav>

            <?php if ($popular && !is_wp_error($popular)) : ?>
                <section class="courses-page__popular">
                    <h2 class="courses-page__popular-title">Популярные курсы</h2>
                    <div class="courses-page__pills">
                        <?php foreach ($popular as $tag) : ?>
                            <a class="courses-page__pill" href="<?php echo esc_url(get_term_link($tag)); ?>">
                                <?php echo esc_html($tag->name); ?>
                                <span class="courses-page__pill-arrow">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="5.65685" y="11.3136" width="1.6" height="8" rx="0.8" transform="rotate(-135 5.65685 11.3136)" fill="currentColor" />
                                        <rect x="4.52547" y="1.13146" width="1.6" height="8" rx="0.8" transform="rotate(-45 4.52547 1.13146)" fill="currentColor" />
                                    </svg>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

    <!-- Каталог направлений — общий блок с главной страницей -->
    <?php get_template_part('template-parts/catalog-output/catalog-output'); ?>

    <div class="section section--u-i1mm52hsj">
        <div class="div div--u-ihuo14hdq">
            <?php get_template_part('template-parts/course/help-banner'); ?>
        </div>
    </div>

</main>

<?php get_footer(); ?>
