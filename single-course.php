<?php
/**
 * Страница курса: /kursy/kurs/{курс}/
 * Hero — как на странице поиска (search-course.php), но без поля поиска.
 * Блок описания — как на странице новости (single-novosti.php).
 * Блок заявки — как на странице направлений (taxonomy-course_direction.php).
 * Футер — стандартный, без блока тегов.
 */

$title = get_the_title();

$course_thumb_id = get_post_thumbnail_id();
$GLOBALS['rcpk_hero_bg_image'] = $course_thumb_id
    ? wp_get_attachment_image_url($course_thumb_id, 'full')
    : get_theme_mod('rcpk_catalog_hero_image', '');
$GLOBALS['rcpk_hero_extra_class'] = 'rcpk-hero--sm';
add_action('rcpk_internal_header_hero', function () use ($title) {
    get_template_part('template-parts/course/hero', null, array(
        'title' => $title,
    ));
});
get_header();

while (have_posts()) : the_post();
    $terms   = get_the_terms(get_the_ID(), 'course_direction');
    $primary = $terms && !is_wp_error($terms) ? $terms[0] : null;
    $price          = rcpk_course_meta('price');
    $document_files = array_filter(array_map('absint', explode(',', (string) rcpk_course_meta('document_file'))));
    $how_to_pdf     = get_theme_mod('rcpk_course_howto_pdf', '');
    ?>

    <div class="section section--u-i1mm52hsj rcpk-container--top">
        <div class="div div--u-ib590tatk">

            <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                    <span class="text-block-wrap-div">Главная</span>
                </a>
                <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>

                <a href="<?php echo esc_url(get_post_type_archive_link('course')); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                    <span class="text-block-wrap-div">Курсы</span>
                </a>
                <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>

                <?php if ($primary) : ?>
                    <?php foreach (array_reverse(get_ancestors($primary->term_id, 'course_direction', 'taxonomy')) as $ancestor_id) : ?>
                        <a href="<?php echo esc_url(get_term_link($ancestor_id, 'course_direction')); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                            <span class="text-block-wrap-div"><?php echo esc_html(get_term($ancestor_id)->name); ?></span>
                        </a>
                        <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
                    <?php endforeach; ?>
                    <a href="<?php echo esc_url(get_term_link($primary)); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                        <span class="text-block-wrap-div"><?php echo esc_html($primary->name); ?></span>
                    </a>
                    <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
                <?php endif; ?>

                <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                    <span class="text-block-wrap-div"><?php the_title(); ?></span>
                </span>
            </div>

            <h1 class="page-title page-title--u-ipo71g40j"><?php the_title(); ?></h1>

            <div class="content content--u-iwo7oqyms">
                <div class="g-page g-page-article g-page-article--main">
                    <div class="g-page-article">

                        <div class="g-page-article__text">
                            <?php if (has_excerpt()) : ?>
                                <p><?php echo wp_kses_post(get_the_excerpt()); ?></p>
                            <?php endif; ?>

                            <?php the_content(); ?>

                            <?php if ($document_files) : ?>
                                <p class="has-medium-font-size"><strong>Выдаваемый документ:</strong> <?php echo esc_html(rcpk_course_meta('document')); ?></p>
                                <p>
                                    <?php foreach ($document_files as $i => $file_id) : ?>
                                        <?php $file_url = wp_get_attachment_url($file_id); ?>
                                        <?php if ($file_url) : ?>
                                            <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener"><?php echo count($document_files) > 1 ? 'Образец документа ' . ($i + 1) : 'Образец документа'; ?></a><?php echo $i < count($document_files) - 1 ? '<br>' : ''; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($how_to_pdf) : ?>
                                <p class="has-medium-font-size"><strong>Как проходить обучение</strong></p>
                                <p><a href="<?php echo esc_url($how_to_pdf); ?>" target="_blank" rel="noopener">Инструкция по прохождению обучения (PDF)</a></p>
                            <?php endif; ?>
                        </div>

                        <div class="g-clear"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="section section--u-i1mm52hsj">
        <div class="div div--u-ib590tatk rcpk-course-order-cta">
            <?php if ($price) : ?>
                <p class="rcpk-course-price has-text-align-center has-background has-medium-font-size"><strong>Стоимость: </strong><?php echo esc_html(number_format_i18n($price)); ?> рублей</p>
            <?php endif; ?>
            <?php echo rcpk_course_order_button(get_the_ID(), 'Пройти обучение', 'link-universal link-universal--u-ikofmis2h rcpk-course-order-button'); ?>
        </div>
    </div>

    <div class="section section--u-i1mm52hsj">
        <div class="div div--u-ihuo14hdq">
            <?php get_template_part('template-parts/course/help-banner'); ?>
        </div>
    </div>

<?php endwhile;

get_footer();
