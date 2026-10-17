<?php
defined('ABSPATH') || exit;

$hours        = rcpk_course_meta('hours');
$price        = rcpk_course_meta('price');
$document     = rcpk_course_meta('document');
$validity     = rcpk_course_meta('validity');
$requirements = rcpk_course_meta('requirements');
$terms        = get_the_terms(get_the_ID(), 'course_direction');
?>
<article class="course-card course-card--gc">
    <h3 class="course-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php if ($terms && !is_wp_error($terms)) : ?>
        <div class="course-card__terms">
            <?php echo esc_html(implode(', ', wp_list_pluck($terms, 'name'))); ?>
        </div>
    <?php endif; ?>
    <?php if (has_excerpt()) : ?>
        <div class="course-card__excerpt"><?php echo wp_kses_post(get_the_excerpt()); ?></div>
    <?php endif; ?>
    <ul class="course-card__meta">
        <?php if ($hours) : ?><li class="course-card__meta-item course-card__meta-item--hours"><?php echo esc_html($hours); ?> ч.</li><?php endif; ?>
        <?php if ($document) : ?><li class="course-card__meta-item course-card__meta-item--document"><?php echo esc_html($document); ?></li><?php endif; ?>
        <?php if ($validity) : ?><li class="course-card__meta-item course-card__meta-item--validity">Действие: <?php echo esc_html($validity); ?></li><?php endif; ?>
        <?php if ($requirements) : ?><li class="course-card__meta-item course-card__meta-item--requirements">Требования: <?php echo esc_html(wp_trim_words($requirements, 12, '…')); ?></li><?php endif; ?>
        <?php if ($price) : ?><li class="course-card__meta-item course-card__meta-item--price"><?php echo esc_html(number_format_i18n($price)); ?> ₽</li><?php endif; ?>
    </ul>
    <a class="course-card__more" href="<?php the_permalink(); ?>">Подробнее</a>
</article>
