<?php
/**
 * Список курсов + пагинация. Используется и при обычной загрузке, и в AJAX-ответе.
 * $args: query (WP_Query), filters (array), base (URL для пагинации, необязательно)
 */
defined('ABSPATH') || exit;

$query   = $args['query'];
$filters = $args['filters'];
$base    = $args['base'] ?? '';
?>
<p class="courses-results__count">Найдено курсов: <?php echo (int) $query->found_posts; ?></p>

<?php if ($query->have_posts()) : ?>
    <div class="courses-results__list">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php get_template_part('template-parts/course/card'); ?>
        <?php endwhile; ?>
    </div>
    <nav class="courses-results__pagination">
        <?php echo rcpk_course_pagination($query, $filters, $base); ?>
    </nav>
<?php else : ?>
    <p class="courses-results__empty">
        <?php echo $filters['q'] !== '' || !empty($filters['dir']) ? 'По вашему запросу курсов не найдено.' : 'Курсов пока нет.'; ?>
    </p>
<?php endif; ?>
