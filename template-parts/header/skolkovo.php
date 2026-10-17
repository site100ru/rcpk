<?php
/**
 * Блок «Сколково» в шапке hero: логотип + подпись, ссылка на страницу
 * «Образовательная деятельность в Сколково». Макеты: Hero, Home mobile.
 */

// Ссылка на страницу «Образовательная деятельность в Сколково» — поменять здесь, если у страницы другой адрес
$rcpk_skolkovo_url = home_url('/obrazovatelnaya-deyatelnost-v-skolkovo/');
$rcpk_img_dir = get_template_directory_uri() . '/assets/img';
?>
<div class="rcpk-sk">
    <a class="rcpk-sk__link" href="<?php echo esc_url($rcpk_skolkovo_url); ?>">
        <picture class="rcpk-sk__logo">
            <source media="(max-width: 767px)" srcset="<?php echo esc_url($rcpk_img_dir . '/skolkovo-logo-mobile.png'); ?>">
            <img src="<?php echo esc_url($rcpk_img_dir . '/skolkovo-logo.png'); ?>" alt="Сколково" width="173" height="48">
        </picture>
        <span class="rcpk-sk__text">Разрешение на образовательную деятельность</span>
    </a>
</div>
