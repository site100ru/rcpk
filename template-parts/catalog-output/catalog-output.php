<?php
// Получаем записи каталога
$args = array(
    'post_type'      => 'catalog',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish'
);

$catalog_query = new WP_Query($args);
?>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ihuo14hdq">
        <div class="heading heading--u-iwz7id95u">
            <span class="text-block-wrap-div">Каталог направлений курсов</span>
        </div>
        <div class="div div--u-ix97q2o2o">
            <div class="blocklist blocklist--u-iv7qintoh">
                <div class="blocklist__items_wrapper blocklist__items_wrapper--u-ingho8az2">
                    <div class="blocklist__list blocklist__list--u-iditg5wka">
                        
                        <?php if ($catalog_query->have_posts()) : ?>
                            <?php while ($catalog_query->have_posts()) : $catalog_query->the_post(); ?>
                                
                                <?php
                                // Получаем внешнюю ссылку из дополнительного поля
                                $external_link = get_post_meta(get_the_ID(), 'external_link', true);
                                
                                // Определяем финальную ссылку: если есть внешняя - используем её, иначе - ссылку на статью
                                $final_link = !empty($external_link) ? esc_url($external_link) : get_permalink();
                                
                                // Получаем изображение
                                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                if (!$thumbnail_url) {
                                    // Заглушка если нет изображения
                                    $thumbnail_url = get_template_directory_uri() . '/assets/img/placeholder.webp';
                                }
                                
                                // Получаем отрывок с сохранением HTML
                                $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 15, '...');
                                ?>
                                
                                <div class="blocklist__item__outer blocklist__item__outer--u-i7u8q7ljz">
                                    <div class="blocklist__item blocklist__item--u-iz82wxbiw">
                                        <div class="imageFit imageFit--u-ividt7p2w photo-swipe-image-nc">
                                            <img src="<?php echo esc_url($thumbnail_url); ?>" 
                                                alt="<?php echo esc_attr(get_the_title()); ?>" 
                                                title="<?php echo esc_attr(get_the_title()); ?>" 
                                                class="imageFit__img imageFit__img--u-iuyvbsvyh" />
                                            <div class="imageFit__overlay imageFit__overlay--u-ieyv553u6">
                                                <a href="<?php echo $final_link; ?>" 
                                                class="link-universal link-universal--u-i3mwgrwp8"
                                                <?php echo !empty($external_link) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                                    <div class="text text--u-ir2e76r7j">
                                                        <span class="text-block-wrap-div">Подробнее</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="imageFit__zoom imageFit__zoom--u-iwv0wxq1l">
                                                <span class="svg_image svg_image--u-iz8areiqr"> </span>
                                            </div>
                                        </div>
                                        <div class="blocklist__item_title blocklist__item_title--u-i75ooip4h">
                                            <span class="text-block-wrap-div"><?php the_title(); ?></span>
                                        </div>
                                        <div class="blocklist__item_text blocklist__item_text--u-iz7j2hzkl">
                                            <span class="text-block-wrap-div"><?php echo wp_kses_post($excerpt); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        <?php else : ?>
                            <p>Курсы не найдены</p>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>