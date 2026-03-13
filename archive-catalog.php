<?php
/**
 * Template Name: Каталог направлений курсов
 * Description: Шаблон страницы каталога с выводом всех курсов
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
                <span class="text-block-wrap-div"><?php the_title(); ?></span>
            </span>
        </div>

        <h1 class="page-title page-title--u-ipo71g40j"><?php the_title(); ?></h1>

        <div class="content content--u-iwo7oqyms">
            <div class="lpc-content-wrapper">
                <div class="decor-wrap">
                    
                    <!-- Контент из Gutenberg -->
                    <?php while (have_posts()) : the_post(); ?>
                        <?php if (get_the_content()): ?>
                        <div class="lpc-elements-text-1 lpc-block" style="max-width: 1500px">
                            <div class="lpc-elements-text-1__container">
                                <div class="lpc-wrap lpc-elements-text-1__wrap">
                                    <div class="lpc-row lpc-elements-text-1__row _left">
                                        <div class="lpc-col-12-xl lpc-col-12-lg lpc-col-12-md lpc-col-12-sm lpc-col-12-xs">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endwhile; ?>

                    <!-- Список курсов из типа записи "catalog" -->
                    <?php
                    $args = array(
                        'post_type'      => 'catalog',
                        'posts_per_page' => -1,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    $catalog_query = new WP_Query($args);
                    
                    if ($catalog_query->have_posts()) : ?>
                        <div class="lpc-menu-1 lpc-block lpc-gap-block" style="max-width: 1500px">
                            <div class="lpc-menu-1__wrap lpc-wrap">
                                <div class="lpc-menu-1__inner lpc-row _left">
                                    <ul class="lpc-menu-1__list lpc-col-4-xl lpc-col-4-lg lpc-col-4-md lpc-col-12-sm lpc-col-12-xs">
                                        
                                        <?php while ($catalog_query->have_posts()) : $catalog_query->the_post(); ?>
                                            <li class="lpc-menu-1__item">
                                                <a href="<?php the_permalink(); ?>" class="lpc-menu-1__link lp-button lpc-button--type-2">
                                                    <?php the_title(); ?>
                                                    <span class="lpc-menu-1__arrow">
                                                        <span class="lpc-menu-1__arrow-line"></span>
                                                        <span class="lpc-menu-1__arrow-line"></span>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; 
                    wp_reset_postdata();
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>