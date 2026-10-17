<?php
/**
 * HERO-контент для внутренних страниц каталога курсов: заголовок + поиск.
 * Топбар/меню/лого уже выведены в header.php («HEADER для внутренних страниц»);
 * этот партиал вызывается через хук do_action('rcpk_internal_header_hero')
 * внутри того же блока (см. header.php) — поэтому здесь только содержимое,
 * без открытия/закрытия чужих тегов.
 * Фон hero задаётся ДО get_header() через $GLOBALS['rcpk_hero_bg_image'].
 *
 * $args: title (string), search_action (url), search_value (string)
 */
defined('ABSPATH') || exit;

$title         = $args['title'] ?? '';
$search_action = $args['search_action'] ?? '';
$search_value  = $args['search_value'] ?? '';
?>
<!-- HERO контент -->
<div class="div div--u-ie6zm4gfm">
    <div class="heading heading--u-i1sycf41t">
        <span class="text-block-wrap-div"><?php echo esc_html($title); ?></span>
    </div>

    <?php if ($search_action) : ?>
        <form class="rcpk-hero-search" method="get" action="<?php echo esc_url($search_action); ?>">
            <input type="search" name="q" class="rcpk-hero-search__input" placeholder="Название курса или специальность" value="<?php echo esc_attr($search_value); ?>">
            <button type="submit" class="rcpk-hero-search__button">Найти</button>
        </form>
    <?php endif; ?>
</div>
