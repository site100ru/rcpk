<?php
/**
 * Блок под hero, как на странице направления: «Поиск», «Популярные направления», баннер акции.
 * Используется на /kursy/ (archive-course.php) и /kursy/poisk/ (search-course.php).
 *
 * args: search_value — текущая поисковая строка;
 *       show_hours / show_promo — выводить «Количество часов» и баннер акции (по умолчанию да; на поиске — нет).
 * «Количество часов» — все значения по курсам, ведут на /kursy/poisk/?chasy=N.
 * «Популярные направления» — самые частые теги курсов (страницы тегов).
 */
$search_value = isset($args['search_value']) ? $args['search_value'] : '';
$show_hours   = !isset($args['show_hours']) || $args['show_hours'];
$show_promo   = !isset($args['show_promo']) || $args['show_promo'];
$hours_list   = $show_hours ? rcpk_course_hours_list() : array();

$popular = get_terms(array(
    'taxonomy'   => 'course_tag',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 30,
));

$pill_arrow = '<svg class="rcpk-dir__pill-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><rect x="5.65685" y="11.3136" width="1.6" height="8" rx="0.8" transform="rotate(-135 5.65685 11.3136)" fill="currentColor"/><rect x="4.52547" y="1.13146" width="1.6" height="8" rx="0.8" transform="rotate(-45 4.52547 1.13146)" fill="currentColor"/></svg>';
?>
<div class="section section--u-i1mm52hsj rcpk-dir__section rcpk-dir__section--filters">
    <div class="div div--u-ihuo14hdq">

        <div class="rcpk-dir__filters">
            <section class="rcpk-dir__block">
                <h2 class="rcpk-dir__subtitle">Поиск</h2>
                <form class="rcpk-dir__search" method="get" action="<?php echo esc_url(rcpk_course_search_url()); ?>">
                    <input type="search" name="q" class="rcpk-dir__search-input" placeholder="Название курса или специальность" value="<?php echo esc_attr($search_value); ?>" required>
                    <button type="submit" class="rcpk-dir__search-button">Найти</button>
                </form>
            </section>

            <?php if ($hours_list) : ?>
                <section class="rcpk-dir__block">
                    <h2 class="rcpk-dir__subtitle">Количество часов</h2>
                    <div class="rcpk-dir__pills rcpk-dir__pills--hours">
                        <?php foreach ($hours_list as $hours) : ?>
                            <a class="rcpk-dir__pill" href="<?php echo esc_url(add_query_arg('chasy', $hours, rcpk_course_search_url()) . '#courses'); ?>">
                                <?php echo esc_html($hours . ' ' . rcpk_plural($hours, 'час', 'часа', 'часов')); ?>
                                <?php echo $pill_arrow; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($popular && !is_wp_error($popular)) : ?>
                <section class="rcpk-dir__block">
                    <h2 class="rcpk-dir__subtitle">Популярные направления</h2>
                    <div class="rcpk-dir__pills rcpk-dir__pills--popular">
                        <?php foreach ($popular as $tag) : ?>
                            <a class="rcpk-dir__pill" href="<?php echo esc_url(get_term_link($tag)); ?>">
                                <?php echo esc_html($tag->name); ?>
                                <?php echo $pill_arrow; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>

        <?php if ($show_promo) {
            get_template_part('template-parts/course/promo-banner');
        } ?>

    </div>
</div>
