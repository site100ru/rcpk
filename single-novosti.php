<?php 
// single-novosti.php - шаблон для отдельного поста типа 'novosti'
get_header();
?>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">
        
        <?php
        // Хлебные крошки
        if (have_posts()) : the_post();
            $categories = get_the_terms(get_the_ID(), 'category_novosti');
            $category = (!empty($categories) && !is_wp_error($categories)) ? array_shift($categories) : null;
        ?>
        
        <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
            <a href="<?php echo home_url('/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Главная</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            
            <a href="<?php echo home_url('/o-kurse/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">О курсе</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            
            <a href="/o-kurse/novosti/" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Новости</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            
            <?php if ($category) : ?>
            <a href="<?php echo get_term_link($category); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div"><?php echo esc_html($category->name); ?></span>
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
                    <div class="g-page-article__top-panel">
                        <div class="g-page-article__date">
                            Дата публикации: <?php echo get_the_date('d.m.Y'); ?>
                        </div>
                    </div>

                    <div class="g-page-article__text">
                        <?php the_content(); ?>
                    </div>

                    <div class="g-clear"></div>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
        
    </div>
</div>

<?php
get_footer();
?>