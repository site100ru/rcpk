<?php

/**
 * Template Name: О курсе
 * Description: Шаблон страницы о курсе с перелинковкой
 */

get_header(); ?>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">
        <!-- Хлебные крошки -->
        <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
            <a href="<?php echo home_url('/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Главная</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                <span class="text-block-wrap-div">О курсе</span>
            </span>
        </div>

        <h1 class="page-title page-title--u-ipo71g40j"><?php the_title(); ?></h1>

        <div class="content content--u-iwo7oqyms">
            <div class="lpc-content-wrapper">
                <div class="decor-wrap">

                    <!-- Основной контент страницы -->
                    <div class="lpc-elements-text-1 lpc-block lpc-lazy-iframe-js" style="max-width: 1016px">
                        <div class="lpc-elements-text-1__container">
                            <div class="lpc-wrap lpc-elements-text-1__wrap">
                                <div class="lpc-row lpc-elements-text-1__row _left">
                                    <div class="lpc-col-12-xl lpc-col-12-lg lpc-col-12-md lpc-col-12-sm lpc-col-12-xs lpc-elements-text-1__text-wrapper">
                                        <?php
                                        // Выводим содержимое страницы
                                        while (have_posts()) : the_post();
                                            the_content();
                                        endwhile;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Меню перелинковки -->
                    <div class="lpc-menu-1 lpc-block lpc-gap-block" style="max-width: 1016px">
                        <div class="lpc-menu-1__wrap lpc-wrap">
                            <div class="lpc-menu-1__inner lpc-row _left">
                                <ul class="lpc-menu-1__list lpc-col-4-xl lpc-col-4-lg lpc-col-4-md lpc-col-12-sm lpc-col-12-xs">

                                    <?php
                                    $current_page_id = get_the_ID();  

                                    $args = array(
                                        'post_type' => 'page',
                                        'post_parent' => $current_page_id,
                                        'orderby' => 'menu_order',
                                        'order' => 'ASC',
                                        'posts_per_page' => -1,
                                    );

                                    $query = new WP_Query($args);

                                    if ($query->have_posts()) :
                                        while ($query->have_posts()) : $query->the_post();
                                    ?>
                                            <li class="lpc-menu-1__item">
                                                <a href="<?php the_permalink(); ?>" class="lpc-menu-1__link lp-button lpc-button--type-2">
                                                    <?php the_title(); ?>
                                                    <span class="lpc-menu-1__arrow">
                                                        <span class="lpc-menu-1__arrow-line"></span>
                                                        <span class="lpc-menu-1__arrow-line"></span>
                                                    </span>
                                                </a>
                                            </li>
                                    <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    else :
                                        echo 'Нет дочерних страниц для отображения.';
                                    endif;
                                    ?>


                                </ul>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>