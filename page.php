<?php

/**
 * Базовый шаблон страницы
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
                    // Получаем иерархию страниц (родители)
                    $ancestors = array_reverse(get_post_ancestors(get_the_ID()));

                    // Выводим родительские страницы
                    foreach ($ancestors as $ancestor_id) :
                    ?>
                        <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
                        <a href="<?php echo get_permalink($ancestor_id); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                            <span class="text-block-wrap-div"><?php echo get_the_title($ancestor_id); ?></span>
                        </a>
                    <?php endforeach; ?>

                    <!-- Текущая страница -->
                    <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
                    <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                        <span class="text-block-wrap-div"><?php the_title(); ?></span>
                    </span>
                </div>

                <!-- Заголовок страницы -->
                <h1 class="page-title page-title--u-ipo71g40j"><?php the_title(); ?></h1>

                <!-- Контент из Gutenberg -->
                <div class="content content--u-iwo7oqyms">
                    <?php the_content(); ?>
                </div>

        <?php endwhile;
        endif; ?>

    </div>
</div>

<?php get_footer(); ?>