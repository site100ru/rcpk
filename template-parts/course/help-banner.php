<?php
/**
 * CTA-баннер "Нужна помощь? Напишите нам" (как на GetCourse).
 * Кнопка использует существующий механизм попапов (assets/js/main.js:163) —
 * role="button" + класс link-universal--u-ikofmis2h открывает #popup-course.
 */
defined('ABSPATH') || exit;
?>
<section class="rcpk-help-banner">
    <div class="rcpk-help-banner__text">
        <h2 class="rcpk-help-banner__title">Нужна помощь?</h2>
        <p class="rcpk-help-banner__subtitle">Напишите нам</p>
        <div role="button" class="link-universal link-universal--u-ikofmis2h rcpk-help-banner__button">
            <div class="text text--u-iwuj0ajod">
                <span class="text-block-wrap-div">Задать вопрос</span>
            </div>
        </div>
    </div>
    <div class="rcpk-help-banner__decor" aria-hidden="true">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/help-banner-decor.png'); ?>" alt="" loading="lazy">
    </div>
</section>
