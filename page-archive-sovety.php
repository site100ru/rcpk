<?php
/* Template Name: Новости */
get_header();
?>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">
        <!-- Хлебные крошки -->
        <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
            <a href="<?php echo home_url(); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Главная</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            <a href="<?php echo get_permalink(get_page_by_path('o-kurse')->ID); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">О курсе</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                <span class="text-block-wrap-div">Новости</span>
            </span>
        </div>

        <!-- Заголовок страницы -->
        <h1 class="page-title page-title--u-ipo71g40j">Новости</h1>

        <div class="content content--u-iwo7oqyms">
            <div class="g-page g-page-article g-page-article--main">
                <div class="g-top-panel g-top-panel--relative g-top-panel--no-flex">
                    <!-- Фильтрация по категориям -->
                    <?php
                    // Получаем все категории
                    $categories = get_terms(array(
                        'taxonomy' => 'category_novosti',
                        'hide_empty' => false,
                    ));

                    if (!empty($categories) && !is_wp_error($categories)) :
                    ?>
                        <div class="g-categories">
                            <a href="#" class="g-categories__button">Разделы</a>
                            <div class="g-categories__dropdown">
                                <ul class="g-menu-2 g-menu-2--level-1">
                                    <?php
                                    foreach ($categories as $category) :
                                    ?>
                                        <li class="g-menu-2__item g-menu-2__item--level-1">
                                            <a href="<?php echo get_term_link($category); ?>" class="g-menu-2__link g-menu-2__link--level-1">
                                                <?php echo esc_html($category->name); ?>
                                            </a>
                                        </li>
                                    <?php
                                    endforeach;
                                    ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Вывод статей -->
                <div class="g-article-list">
                    <?php
                    // Запрос на получение постов типа 'novosti'
                    $args = array(
                        'post_type' => 'novosti',
                        'posts_per_page' => -1, // Выводим все посты (без пагинации)
                    );
                    $query = new WP_Query($args);

                    if ($query->have_posts()) :
                        while ($query->have_posts()) : $query->the_post();
                    ?>
                            <article class="g-article g-article--simple-view">
                                <time class="g-article__date"><?php the_date('d.m.Y'); ?></time>
                                <a class="g-article__name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <div class="g-article__body g-clear-self">
                                    <div class="g-article__image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('thumbnail'); // Выводим миниатюру 
                                            ?>
                                        </a>
                                    </div>
                                    <div class="g-article__text">
                                        <p>
                                            <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); // Выводим краткое описание 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </article>
                    <?php
                        endwhile;
                    else :
                        echo '<p>Нет статей для отображения.</p>';
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer(); ?>
<script>
    $(document).ready(function() {
        // Обработчик для кнопки
        $(document).on('click', '.g-categories__button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).parent().toggleClass('g-categories--opened');
        });

        // Закрытие меню при клике вне элемента
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.g-categories').length) {
                $('.g-categories').removeClass('g-categories--opened');
            }
        });
    });
</script>