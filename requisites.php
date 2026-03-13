<?php

/**
 * Template Name: Реквизиты
 * Description: Шаблон страницы реквизитов
 */

get_header(); ?>

<style>
    .lpc-gap-block {
        padding: 32px 0;
    }

    :root {
        --header-title-4-media-1-default: 26px;
        --header-text-2-media-1-default: 18px;
    }

    .lpc-props-1__address-item {
        display: flex;
        align-items: baseline;
    }

    .lp-header-text-2 {
        font-size: var(--header-text-2-media-1-default);
        line-height: 1.53;
    }

    .lpc-wrap .lp-header-text-1,
    .lpc-wrap .lp-header-text-2,
    .lpc-wrap .lp-header-text-3,
    .lpc-wrap .lp-header-text-4 {
        font-family: Montserrat, sans-serif;
        font-style: normal;
        font-weight: 400;
    }

    .lpc-props-1__legal-address-title,
    .lpc-props-1__actual-address-title {
        flex: 0 0 194px;
        margin: 0 16px 0 0;
    }

    .lpc-props-1__address-item+.lpc-props-1__address-item {
        margin: 12px 0 0 0;
    }

    @media (max-width: 1170px) {
        :root {
            --header-title-4-media-1-default: 22px;
        }

        .lpc-gap-block {
            padding: 24px 0;
        }
    }

    @media (max-width: 820px) {
        :root {
            --header-title-4-media-1-default: 20px;
            --header-text-2-media-1-default: 17px;
        }
    }

    @media (max-width: 540px) {
        :root {
            --header-text-2-media-1-default: 16px;
        }

        .lpc-props-1__address-item {
            flex-direction: column;
        }

        .lpc-props-1__legal-address-title,
        .lpc-props-1__actual-address-title {
            flex: 1 1 auto;
            margin: 0 0 4px;
        }
    }
</style>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">
        <!-- Хлебные крошки -->
        <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
            <a href="<?php echo home_url('/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Главная</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>

            <?php
            // Получаем родительскую страницу
            $parent_id = wp_get_post_parent_id(get_the_ID());
            if ($parent_id) {
                $parent = get_post($parent_id);
            ?>
                <a href="<?php echo get_permalink($parent_id); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                    <span class="text-block-wrap-div"><?php echo esc_html($parent->post_title); ?></span>
                </a>
                <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            <?php } ?>

            <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                <span class="text-block-wrap-div">Реквизиты</span>
            </span>
        </div>

        <h1 class="page-title page-title--u-ipo71g40j">Реквизиты</h1>

        <div class="content content--u-iwo7oqyms">
            <div class="lpc-content-wrapper">
                <div class="decor-wrap">
                    <div class="lpc-props-1 lpc-block lpc-gap-block" style="max-width: 1215px">
                        <div class="lpc-props-1__wrap lpc-wrap _left">
                            <div class="lpc-col-8-xl lpc-col-8-lg lpc-col-12-md lpc-col-12-xs lpc-col-12-sm">
                                <div class="lpc-row lpc-props-1__row">
                                    <div class="lpc-props-1__row-container">

                                        <!-- Название компании -->
                                        <?php if (get_theme_mod('mytheme_company_name')): ?>
                                            <div class="lpc-props-1__head">
                                                <div class="lpc-props-1__company lp-header-title-4">
                                                    <?php echo esc_html(get_theme_mod('mytheme_company_name')); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="lpc-props-1__body">

                                            <!-- Юридический адрес -->
                                            <?php if (get_theme_mod('mytheme_legal_address')): ?>
                                                <div class="lpc-props-1__address-item">
                                                    <div class="lpc-props-1__legal-address-title lp-header-text-2">
                                                        Юридический адрес:
                                                    </div>
                                                    <div class="lpc-props-1__legal-address lp-header-text-2">
                                                        <?php echo esc_html(get_theme_mod('mytheme_legal_address')); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- ИНН -->
                                            <?php if (get_theme_mod('mytheme_inn')): ?>
                                                <div class="lpc-props-1__address-item">
                                                    <div class="lpc-props-1__legal-address-title lp-header-text-2">
                                                        ИНН:
                                                    </div>
                                                    <div class="lpc-props-1__legal-address lp-header-text-2">
                                                        <?php echo esc_html(get_theme_mod('mytheme_inn')); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- ОГРН -->
                                            <?php if (get_theme_mod('mytheme_ogrn')): ?>
                                                <div class="lpc-props-1__address-item">
                                                    <div class="lpc-props-1__legal-address-title lp-header-text-2">
                                                        ОРГН:
                                                    </div>
                                                    <div class="lpc-props-1__legal-address lp-header-text-2">
                                                        <?php echo esc_html(get_theme_mod('mytheme_ogrn')); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- КПП -->
                                            <?php if (get_theme_mod('mytheme_kpp')): ?>
                                                <div class="lpc-props-1__address-item">
                                                    <div class="lpc-props-1__legal-address-title lp-header-text-2">
                                                        КПП:
                                                    </div>
                                                    <div class="lpc-props-1__legal-address lp-header-text-2">
                                                        <?php echo esc_html(get_theme_mod('mytheme_kpp')); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile;
            endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>