<?php
/**
 * Single Template for Catalog (Каталог курсов)
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">
        <!-- Хлебные крошки -->
        <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
            <a href="<?php echo home_url('/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Главная</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            <a href="/catalog/" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Каталог направлений курсов</span>
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
                    
                    <!-- Блок с изображением и текстом -->
                    <div class="lpc-elements-text-3 lpc-block" style="max-width: 1500px">
                        <div class="lpc-elements-text-3__wrap lpc-wrap">
                            <div class="lpc-elements-text-3__inner">
                                <div class="lpc-elements-text-3__row lpc-row js-lg-init">
                                    
                                    <?php if (has_post_thumbnail()): ?>
                                    <!-- Фото -->
                                    <div class="lpc-col-4-xl lpc-col-4-lg lpc-col-6-md lpc-col-6-sm lpc-col-12-xs lpc-elements-text-3__photos _right">
                                        <div class="lpc-elements-text-3__photo-wrap">
                                            <a href="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" target="_blank" class="lpc-image-type-1 lg-item lpc-elements-text-3__photo lp-selected-element">
                                                <?php the_post_thumbnail('large'); ?>
                                            </a>
                                        </div>
                                        <?php if (get_the_excerpt()): ?>
                                        <div class="lpc-elements-text-3__img-title lp-header-title-6">
                                            <?php echo get_the_excerpt(); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Текст -->
                                    <div class="lpc-elements-text-3__text-wrap">
                                        <div class="lpc-elements-text-3__text lp-header-text-1">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>