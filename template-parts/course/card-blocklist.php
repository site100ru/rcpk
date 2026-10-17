<?php
/**
 * Карточка курса в стиле карточек направлений (imageFit/blocklist) — используется
 * в каталоге курсов направления (taxonomy-course_direction.php), чтобы вид совпадал
 * с карточками направлений на /kursy/.
 */
defined('ABSPATH') || exit;

$hours    = rcpk_course_meta('hours');
$document = rcpk_course_meta('document');
$validity = rcpk_course_meta('validity');
$price    = rcpk_course_meta('price');

$image_url = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: get_template_directory_uri() . '/assets/img/course.webp';
$link      = get_permalink();
$course_id = get_the_ID();
?>
<div class="blocklist__item__outer blocklist__item__outer--u-i7u8q7ljz">
    <div class="blocklist__item blocklist__item--u-iz82wxbiw">
        <div class="imageFit imageFit--u-ividt7p2w photo-swipe-image-nc">
            <img src="<?php echo esc_url($image_url); ?>"
                alt="<?php the_title_attribute(); ?>"
                title="<?php the_title_attribute(); ?>"
                class="imageFit__img imageFit__img--u-iuyvbsvyh" />
            <div class="imageFit__overlay imageFit__overlay--u-ieyv553u6">
                <?php echo rcpk_course_order_button($course_id, 'Пройти обучение'); ?>
            </div>
        </div>

        <div class="blocklist__item_title blocklist__item_title--u-i75ooip4h">
            <span class="text-block-wrap-div"><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></span>
        </div>
        <div class="blocklist__item_text blocklist__item_text--u-iz7j2hzkl course-card-blocklist__meta">
            <?php if ($hours) : ?>
                <span class="text-block-wrap-div">Количество часов: <?php echo esc_html($hours); ?></span>
            <?php endif; ?>
            <?php if ($document) : ?>
                <span class="text-block-wrap-div">Вид выдаваемого документа: <?php echo esc_html($document); ?></span>
            <?php endif; ?>
            <?php if ($validity) : ?>
                <span class="text-block-wrap-div">Срок действия: <?php echo esc_html($validity); ?></span>
            <?php endif; ?>
            <?php if ($price) : ?>
                <span class="text-block-wrap-div">Стоимость: <?php echo esc_html(number_format_i18n($price)); ?> ₽</span>
            <?php endif; ?>
        </div>
    </div>
</div>
