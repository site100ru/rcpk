<?php
/**
 * Тег курсов (/kursy/tag/{тег}/) — курсы по тегу.
 * Вёрстка унифицирована с archive-course.php (/kursy/) и taxonomy-course_direction.php:
 * hero как на странице поиска, блок тегов, сетка карточек курсов, блок заявки, футер.
 */
$term = get_queried_object();

$tags = get_terms(array(
    'taxonomy'   => 'course_tag',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 30,
));

$courses_query = new WP_Query(array(
    'post_type'      => 'course',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'tax_query'      => array(array(
        'taxonomy' => 'course_tag',
        'field'    => 'term_id',
        'terms'    => $term->term_id,
    )),
));

$GLOBALS['rcpk_hero_bg_image']   = get_theme_mod(
    'rcpk_neutral_hero_image',
    get_theme_mod('rcpk_catalog_hero_image', '')
);
$GLOBALS['rcpk_hero_extra_class'] = 'rcpk-hero--sm';
add_action('rcpk_internal_header_hero', function () use ($term) {
    get_template_part('template-parts/course/hero', null, array(
        'title'         => $term->name,
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
                <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a> /
                <a href="<?php echo esc_url(get_post_type_archive_link('course')); ?>">Курсы</a> /
                <span><?php echo esc_html($term->name); ?></span>
            </nav>

            <?php if ($tags && !is_wp_error($tags)) : ?>
                <section class="courses-page__popular">
                    <h2 class="courses-page__popular-title">Популярные курсы</h2>
                    <div class="courses-page__pills">
                        <?php foreach ($tags as $tag) : ?>
                            <a class="courses-page__pill<?php echo $tag->term_id === $term->term_id ? ' courses-page__pill--active' : ''; ?>" href="<?php echo esc_url(get_term_link($tag)); ?>">
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

    <!-- Курсы по тегу — та же вёрстка (blocklist), что и на /kursy/ -->
    <div class="section section--u-i1mm52hsj">
        <div class="div div--u-ihuo14hdq">
            <div class="heading heading--u-iwz7id95u">
                <span class="text-block-wrap-div"><?php echo esc_html($term->name); ?></span>
            </div>
            <div class="div div--u-ix97q2o2o">
                <div class="blocklist blocklist--u-iv7qintoh">
                    <div class="blocklist__items_wrapper blocklist__items_wrapper--u-ingho8az2">
                        <div class="blocklist__list blocklist__list--u-iditg5wka">

                            <?php if ($courses_query->have_posts()) : ?>
                                <?php while ($courses_query->have_posts()) : $courses_query->the_post(); ?>
                                    <?php get_template_part('template-parts/course/card-blocklist'); ?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <p>Курсы пока не добавлены.</p>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); ?>

                        </div>
                    </div>
                </div>
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
