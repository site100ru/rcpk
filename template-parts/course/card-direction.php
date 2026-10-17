<?php
/**
 * Карточка курса на странице направления (taxonomy-course_direction.php),
 * макет Figma «Archive desktop / mobile».
 */
defined('ABSPATH') || exit;

$course_id    = get_the_ID();
$hours        = (int) rcpk_course_meta('hours');
$document     = rcpk_course_meta('document');
$validity     = rcpk_course_meta('validity');
$requirements = rcpk_course_meta('requirements');
$price        = rcpk_course_meta('price');
$image_url    = get_the_post_thumbnail_url($course_id, 'medium_large') ?: get_template_directory_uri() . '/assets/img/course.webp';
?>
<article class="rcpk-dcard">
    <div class="rcpk-dcard__top">
        <img class="rcpk-dcard__img" src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async">
        <?php if ($hours) : ?>
            <span class="rcpk-dcard__hours"><?php echo esc_html($hours . ' ' . rcpk_plural($hours, 'час', 'часа', 'часов')); ?></span>
        <?php endif; ?>
    </div>

    <div class="rcpk-dcard__body">
        <h3 class="rcpk-dcard__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

        <ul class="rcpk-dcard__meta">
            <?php if ($document) : ?>
                <li><span>Вид выдаваемого документа:</span> <?php echo esc_html($document); ?></li>
            <?php endif; ?>
            <?php if ($validity) : ?>
                <li><span>Срок действия:</span> <?php echo esc_html($validity); ?></li>
            <?php endif; ?>
            <?php if ($requirements) : ?>
                <li><span>Требования к образованию:</span> <?php echo esc_html(wp_trim_words($requirements, 25, '…')); ?></li>
            <?php endif; ?>
        </ul>

        <?php if ($price) : ?>
            <p class="rcpk-dcard__price">Стоимость: <b><?php echo esc_html(number_format_i18n($price)); ?> ₽</b></p>
        <?php endif; ?>

        <div class="rcpk-dcard__actions">
            <?php echo rcpk_course_order_button($course_id, 'Пройти обучение', 'rcpk-dcard__btn'); ?>
        </div>
    </div>
</article>
