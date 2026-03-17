<?php

/**
 * Template Name: Основные сведения
 * Description: Шаблон страницы с боковым меню подпунктов
 */
get_header();
?>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <!-- Хлебные крошки -->
                <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
                    <a href="<?php echo home_url('/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                        <span class="text-block-wrap-div">Главная</span>
                    </a>

                    <?php
                    $ancestors_display = array_reverse(get_post_ancestors(get_the_ID()));
                    foreach ($ancestors_display as $ancestor_id) : ?>
                        <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
                        <a href="<?php echo get_permalink($ancestor_id); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                            <span class="text-block-wrap-div"><?php echo get_the_title($ancestor_id); ?></span>
                        </a>
                    <?php endforeach; ?>

                    <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
                    <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                        <span class="text-block-wrap-div"><?php the_title(); ?></span>
                    </span>
                </div>

                <!-- Заголовок страницы -->
                <h1 class="page-title page-title--u-ipo71g40j"><?php the_title(); ?></h1>

                <div class="content content--u-iwo7oqyms">
                    <div class="lpc-content-wrapper">
                        <div class="decor-wrap">

                            <!-- Контент из Gutenberg -->
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


                            <?php
                            // Ищем текущую страницу в меню и берём её подпункты
                            $current_id = get_the_ID();
                            $menu_items = [];
                            $menu_locations = get_nav_menu_locations();

                            if (!empty($menu_locations['primary'])) {
                                $all_items = wp_get_nav_menu_items($menu_locations['primary']);

                                if ($all_items) {
                                    // Находим ID пункта меню для текущей страницы
                                    $current_menu_item_id = null;
                                    foreach ($all_items as $item) {
                                        if ((int)$item->object_id === $current_id && $item->object === 'page') {
                                            $current_menu_item_id = $item->ID;
                                            break;
                                        }
                                    }

                                    // Собираем дочерние пункты
                                    if ($current_menu_item_id) {
                                        foreach ($all_items as $item) {
                                            if ((int)$item->menu_item_parent === $current_menu_item_id) {
                                                $menu_items[] = $item;
                                            }
                                        }
                                    }
                                }
                            }
                            ?>

                            <?php if (!empty($menu_items)) : ?>
                                <div class="lpc-menu-1 lpc-block lpc-gap-block" style="max-width: 1500px">
                                    <div class="lpc-menu-1__wrap lpc-wrap">
                                        <div class="lpc-menu-1__inner lpc-row _left">
                                            <ul class="lpc-menu-1__list lpc-col-4-xl lpc-col-4-lg lpc-col-4-md lpc-col-12-sm lpc-col-12-xs">
                                                <?php foreach ($menu_items as $item) : ?>
                                                    <li class="lpc-menu-1__item">
                                                        <a href="<?php echo esc_url($item->url); ?>" class="lpc-menu-1__link lp-button lpc-button--type-2">
                                                            <?php echo esc_html($item->title); ?>
                                                            <span class="lpc-menu-1__arrow">
                                                                <span class="lpc-menu-1__arrow-line"></span>
                                                                <span class="lpc-menu-1__arrow-line"></span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


                        </div>
                    </div>
                </div>

        <?php endwhile;
        endif; ?>

    </div>
</div>

<?php get_footer(); ?>