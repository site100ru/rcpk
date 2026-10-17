<?php
/**
 * Форма поиска/фильтра/сортировки. Работает и без JS (GET-запрос), с JS — через AJAX.
 * $args: filters (array), action (URL формы), term (WP_Term|null — текущее направление/категория)
 */
defined('ABSPATH') || exit;

$filters = $args['filters'];
$term    = $args['term'] ?? null;

// На странице направления фильтруем по его категориям, на остальных — по всему дереву
$parent_id = 0;
if ($term) {
    $ancestors = get_ancestors($term->term_id, 'course_direction', 'taxonomy');
    $parent_id = $ancestors ? end($ancestors) : $term->term_id;
}

$groups = array();
$tops   = $parent_id
    ? array(get_term($parent_id, 'course_direction'))
    : get_terms(array('taxonomy' => 'course_direction', 'parent' => 0, 'hide_empty' => true));

foreach ((array) $tops as $top) {
    if (!$top || is_wp_error($top)) {
        continue;
    }
    $groups[] = array(
        'top'      => $top,
        'children' => get_terms(array('taxonomy' => 'course_direction', 'parent' => $top->term_id, 'hide_empty' => true)),
    );
}
?>
<form class="courses-filter" method="get" action="<?php echo esc_url($args['action']); ?>" data-term="<?php echo $term ? (int) $term->term_id : 0; ?>">
    <input type="search" name="q" value="<?php echo esc_attr($filters['q']); ?>" placeholder="Название курса или специальность">

    <?php foreach ($groups as $group) : ?>
        <fieldset class="courses-filter__group">
            <?php if ($parent_id) : ?>
                <legend><?php echo esc_html($group['top']->name); ?></legend>
            <?php else : ?>
                <legend>
                    <label>
                        <input type="checkbox" name="dir[]" value="<?php echo esc_attr($group['top']->slug); ?>" <?php checked(in_array($group['top']->slug, $filters['dir'], true)); ?>>
                        <?php echo esc_html($group['top']->name); ?>
                    </label>
                </legend>
            <?php endif; ?>

            <?php foreach ((array) $group['children'] as $child) : ?>
                <label>
                    <input type="checkbox" name="dir[]" value="<?php echo esc_attr($child->slug); ?>" <?php checked(in_array($child->slug, $filters['dir'], true)); ?>>
                    <?php echo esc_html($child->name); ?> (<?php echo (int) $child->count; ?>)
                </label>
            <?php endforeach; ?>
        </fieldset>
    <?php endforeach; ?>

    <select name="sort">
        <?php foreach (rcpk_course_sort_options() as $value => $label) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($filters['sort'], $value); ?>><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Найти</button>
    <?php if (rcpk_course_has_filters($filters)) : ?>
        <a href="<?php echo esc_url($args['action']); ?>">Сбросить</a>
    <?php endif; ?>
</form>
