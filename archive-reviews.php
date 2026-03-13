<?php

/**
 * Template Name: Archive Reviews
 * Шаблон для архивной страницы отзывов
 * Сохраните как archive-reviews.php в корне вашей темы
 */

get_header(); ?>

<style>
    :root {
        --header-text-3-default: 16px;
    }

    .lpc-wrap .lp-header-text-1,
    .lpc-wrap .lp-header-text-2,
    .lpc-wrap .lp-header-text-3,
    .lpc-wrap .lp-header-text-4 {
        font-family: Montserrat, sans-serif;
        font-style: normal;
        font-weight: 400;
    }

    .lpc-wrap .lp-header-text-1,
    .lpc-wrap .lp-header-text-2,
    .lpc-wrap .lp-header-text-3,
    .lpc-wrap .lp-header-text-4 {
        line-height: 1.5;
    }

    .lpc-reviews-1-item__text,
    .lpc-reviews-1-item__text {
        margin-bottom: 12px;
    }

    .lp-header-text-3 {
        font-size: var(--header-text-3-default);
    }

    .lpc-row {
        margin: 0 -16px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        width: calc(100% + 32px);
    }

    @media (max-width: 1170px) {
        .lpc-row {
            margin: 0 -12px;
            width: calc(100% + 24px);
            row-gap: 32px;
        }

        .lpc-col-6-md {
            margin-left: 12px;
            margin-right: 12px;
            width: calc(((100% / 12) * 6) - 24px);
        }
    }

    @media (max-width: 520px) {
        .lpc-col-12-xs {
            margin-left: 8px;
            margin-right: 8px;
            width: calc(100% - 16px);
        }
    }

    .lpc-gap-block {
        padding: 32px 0;
    }

    @media (max-width: 1170px) {
        .lpc-gap-block {
            padding: 24px 0;
        }
    }

    .lpc-reviews-1-item__body {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: -12px;
    }

    .lpc-reviews-1-item__image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        max-width: 140px;
        max-height: 140px;
        flex: 0 0 auto;
        margin-right: 12px;
        margin-bottom: 12px;
    }

    .lpc-wrap h6,
    .lpc-wrap .lp-header-title-6 {
        font-family: Montserrat, sans-serif;
        font-style: normal;
        font-weight: 700;
    }

    .lpc-block__img-inner {
        height: 100%;
        width: 100%;
    }
    
    .lpc-block__img-inner img {
        height: 100%;
        object-fit: cover;
    }

    .lpc-reviews-1__item {
        padding-bottom: 24px;
    }
</style>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">
        <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
            <a href="<?php echo home_url('/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Главная</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                <span class="text-block-wrap-div">Отзывы о нас</span>
            </span>
        </div>

        <h1 class="page-title page-title--u-ipo71g40j">Отзывы о нас</h1>

        <div class="content content--u-iwo7oqyms">
            <div class="lpc-content-wrapper">
                <div class="decor-wrap">
                    <div class="lpc-reviews-1 lpc-block lpc-gap-block _1" style="max-width: 1500px">
                        <div class="lpc-wrap lpc-reviews-1__wrap three_columns">
                            <div class="lpc-reviews-1__box">
                                <div class="lpc-reviews-1__containr lpc-container-wrap">
                                    <div class="lpc-row _left lpc-reviews-1__row lpc-reviews-1__slider-row">

                                        <?php if (have_posts()) : ?>
                                            <?php while (have_posts()) : the_post();
                                                $author_name = get_post_meta(get_the_ID(), '_review_author_name', true);
                                                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                            ?>

                                                <div class="lpc-reviews-1__item lpc-col-4-xl lpc-col-4-lg lpc-col-6-md lpc-col-6-sm lpc-col-12-xs">
                                                    <div class="lpc-reviews-1__item-inner">
                                                        <div class="lpc-reviews-1-item__text lp-header-text-3">
                                                            <?php echo wp_kses_post(get_the_content()); ?>
                                                        </div>
                                                        <div class="lpc-reviews-1-item__body">
                                                            <?php if ($thumbnail_url) : ?>
                                                                <div class="lpc-reviews-1-item__image">
                                                                    <div class="lpc-block__img-inner _1_1">
                                                                        <img src="<?php echo esc_url($thumbnail_url); ?>"
                                                                            alt="<?php echo esc_attr($author_name); ?>" />
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>

                                                            <?php if ($author_name) : ?>
                                                                <div class="lpc-reviews-1-item__title lp-header-title-6">
                                                                    <?php echo esc_html($author_name); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <p>Отзывов пока нет.</p>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Получаем ID страницы архива отзывов для вывода контента Gutenberg
                    $reviews_page = get_page_by_path('reviews', OBJECT, 'page');
                    if ($reviews_page && $reviews_page->post_content) :
                    ?>
                        <div class="reviews-additional-content">
                            <?php echo apply_filters('the_content', $reviews_page->post_content); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>