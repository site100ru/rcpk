<?php
/**
 * Template Name: Контакты
 * Description: Шаблон страницы контактов с картой
 */

get_header(); ?>

<style>
    :root {
        --header-title-5-default: 20px;
        --header-text-2-default: 18px;
    }

    .content--u-iwo7oqyms {
        position: relative;
        width: 100%;
        display: block;
    }
    .lpc-gap-block {
        padding: 32px 0;
    }

    .lpc-universal-contacts__item--phones {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        row-gap: 8px;
    }

    .lpc-wrap h5,
    .lpc-wrap .lp-header-title-5 {
        font-family: Montserrat, sans-serif;
        font-style: normal;
        font-weight: 700;
        font-size: var(--header-title-5-default);
    }

    .lpc-wrap a[href^='tel:']:not(.lp-button),
    .lpc-wrap a[href^='tel:']:not(.lp-button):hover {
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        margin-right: 16px;
    }

    .lpc-col-4-lg {
        margin: 0;
    }

    @media (max-width: 820px) {
        .lpc-col-6-md {
            width: 100%;
        }
    }

    #map2 {
        height: 500px;
    }

    .lpc-universal-contacts__item--style {
        margin-top: 20px;
    }

    .lpc-universal-contacts__item:first-child {
        margin-top: 0 !important;
    }

    .lpc-wrap .lp-header-text-1,
    .lpc-wrap .lp-header-text-2,
    .lpc-wrap .lp-header-text-3,
    .lpc-wrap .lp-header-text-4 {
        font-family: Montserrat, sans-serif;
        font-style: normal;
        font-weight: 400;
        line-height: 1.5;
        font-size: var(--header-text-2-default);
    }

    .lpc-universal-contacts__field {
        text-decoration: none;
    }

    .lpc-universal-contacts__messenger {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin: 0 -4px -8px;
    }

    .lpc-universal-contacts__messenger-item {
        position: relative;
        margin: 0 4px 8px;
    }

    .lpc-universal-contacts__messenger-icon {
        display: flex;
        max-width: 64px;
        max-height: 64px;
        min-width: 28px;
        min-height: 28px;
        width: 40px;
        height: 40px;
        overflow: hidden;
        border-radius: 6px;
    }

    .lpc-universal-contacts .lpc-universal-social {
        display: flex;
        flex-wrap: wrap;
        margin-top: 14px;
        margin-right: -5px;
        margin-left: -5px;
    }

    .lpc-universal-contacts .lpc-universal-social__link.hidden_text {
        padding: 0;
        flex: 0 0 auto;
    }

    .lpc-universal-contacts .lpc-universal-social__link {
        display: flex;
        align-items: center;
        text-decoration: none;
        margin: 10px 5px 0;
        border-radius: 6px;
        padding: 10px 28px;
        position: relative;
        z-index: 1;
        transition: all 0.2s linear;
        overflow: hidden;
        flex: 1 1 auto;
        min-height: 50px;
        justify-content: center;
    }

    .lpc-universal-contacts .lpc-universal-social__icon {
        width: 32px;
        height: 32px;
        display: flex;
        overflow: hidden;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .lpc-universal-contacts .lpc-universal-social__icon-box {
        width: 32px;
        height: 32px;
        font-size: 0;
        flex: 0 0 auto;
    }

    .lpc-universal-contacts .lpc-universal-social__link.hidden_text .lpc-universal-social__icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lpc-universal-social__link.hidden_text .lpc-universal-social__icon {
        max-width: 50px;
        max-height: 50px;
        min-width: 28px;
        min-height: 28px;
    }

    .lpc-universal-contacts .lpc-universal-social__link.hidden_text .lpc-universal-social__icon {
        width: 50px;
        height: 50px;
    }

    .lpc-col-8-xl {
        margin-left: 16px;
        margin-right: 16px;
        width: calc(((100% / 12) * 8) - 32px);
    }

    .lpc-universal-contacts__map-inner {
        height: 100%;
        overflow: hidden;
    }
    .lpc-universal-contacts__map {
        width: 100%;
        height: 100%;
    }

    @media (max-width: 830px) {
        .lpc-col-12-sm {
            margin-left: 8px;
            margin-right: 8px;
            width: calc(100% - 16px);
        }

        .lpc-universal-contacts__map-inner {
            height: 340px;
            overflow: hidden;
            margin-top: 24px;
        }
    }
</style>

<div class="section section--u-i1mm52hsj">
    <div class="div div--u-ib590tatk">
        <!-- Хлебные крошки -->
        <div class="mosaic-crumbs mosaic-crumbs--u-iedna726y">
            <a href="<?php echo home_url('/'); ?>" class="mosaic-crumbs__item_link mosaic-crumbs__item_link--u-ion7bivdc">
                <span class="text-block-wrap-div">Главная</span>
            </a>
            <span class="mosaic-crumbs__delimiter mosaic-crumbs__delimiter--u-i85f67ptd">/</span>
            <span class="mosaic-crumbs__last mosaic-crumbs__last--u-i4m0w84oi">
                <span class="text-block-wrap-div">Контакты</span>
            </span>
        </div>

        <h1 class="page-title page-title--u-ipo71g40j">Контакты</h1>

        <div class="content content--u-iwo7oqyms">
            <div class="lpc-content-wrapper">
                <div class="decor-wrap">
                    <div class="lpc-universal-contacts lpc-block lpc-gap-block">
                        <div class="lpc-universal-contacts__wrap lpc-wrap lpc_qr_init">
                            <div class="lpc-universal-contacts__wrap-box">
                                <div class="lpc-full-width-content lpc-container-wrap lpc-universal-contacts__inner">
                                    <div class="lpc-universal-contacts__row lpc-row">
                                        <!-- Контактная информация -->
                                        <div class="lpc-universal-contacts__content lpc-col-4-xl lpc-col-4-lg lpc-col-6-md lpc-col-12-xs lpc-col-12-sm">
                                            <div class="lpc-universal-contacts__content-in">
                                                <div class="lpc-universal-contacts__list">
                                                    
                                                    <!-- Телефон с мессенджерами -->
                                                    <?php 
                                                    $phone_display = '';
                                                    $phone_link = '';
                                                    if (function_exists('mytheme_get_phone')) {
                                                        $phone_display = mytheme_get_phone('main');
                                                        $phone_link = mytheme_get_phone_link('main');
                                                    }
                                                    if (!empty($phone_display)): 
                                                    ?>
                                                    <div class="lpc-universal-contacts__item lpc_qr_code_item lpc-title-color lpc-universal-contacts__item--phones lpc-universal-contacts__item--style">
                                                        <a href="tel:<?php echo esc_attr($phone_link); ?>" class="lpc-universal-contacts__item-tel lp-header-title-5 lpc_qr_code_link_phone">
                                                            <?php echo esc_html($phone_display); ?>
                                                        </a>
                                                        <div class="lpc-universal-contacts__messenger">
                                                            <?php if (get_theme_mod('mytheme_whatsapp')): ?>
                                                            <div class="lpc-universal-contacts__messenger-item">
                                                                <a href="<?php echo esc_url(get_theme_mod('mytheme_whatsapp')); ?>" class="lpc-universal-contacts__messenger-link lpc_qr_code_link">
                                                                    <span class="lpc-universal-contacts__messenger-icon">
                                                                        <img width="64" height="64" src="<?php echo get_template_directory_uri(); ?>/assets/img/contact/wa.svg" alt="WhatsApp" />
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (get_theme_mod('mytheme_telegram')): ?>
                                                            <div class="lpc-universal-contacts__messenger-item">
                                                                <a href="<?php echo esc_url(get_theme_mod('mytheme_telegram')); ?>" class="lpc-universal-contacts__messenger-link lpc_qr_code_link">
                                                                    <span class="lpc-universal-contacts__messenger-icon">
                                                                        <img width="64" height="64" src="<?php echo get_template_directory_uri(); ?>/assets/img/contact/tg.svg" alt="Telegram" />
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                    <!-- Время работы -->
                                                    <?php if (get_theme_mod('mytheme_job_time')): ?>
                                                    <div class="lpc-universal-contacts__item lpc-universal-contacts__item--style">
                                                        <div class="lpc-universal-contacts__field lp-header-text-2">
                                                            <?php echo esc_html(get_theme_mod('mytheme_job_time')); ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                    <!-- Выходные -->
                                                    <?php if (get_theme_mod('mytheme_weekend')): ?>
                                                    <div class="lpc-universal-contacts__item lpc-universal-contacts__item--style">
                                                        <div class="lpc-universal-contacts__field lp-header-text-2">
                                                            <?php echo esc_html(get_theme_mod('mytheme_weekend')); ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                    <!-- Адрес -->
                                                    <?php if (get_theme_mod('mytheme_address')): ?>
                                                    <div class="lpc-universal-contacts__item lpc-universal-contacts__item--style">
                                                        <div class="lpc-universal-contacts__field lp-header-text-2">
                                                            <?php echo esc_html(get_theme_mod('mytheme_address')); ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                    <!-- Email -->
                                                    <?php if (get_theme_mod('mytheme_email')): ?>
                                                    <div class="lpc-universal-contacts__item lpc-universal-contacts__item--style">
                                                        <a href="mailto:<?php echo esc_attr(get_theme_mod('mytheme_email')); ?>" class="lpc-universal-contacts__field lp-header-text-2 lpc_mail">
                                                            <?php echo esc_html(get_theme_mod('mytheme_email')); ?>
                                                        </a>
                                                    </div>
                                                    <?php endif; ?>

                                                    <!-- Реквизиты -->
                                                    <?php if (get_theme_mod('mytheme_inn') || get_theme_mod('mytheme_ogrn')): ?>
                                                    <div class="lpc-universal-contacts__item lpc-universal-contacts__item--style">
                                                        <div class="lpc-universal-contacts__field lp-header-text-2">
                                                            <?php if (get_theme_mod('mytheme_inn')): ?>
                                                                ИНН <?php echo esc_html(get_theme_mod('mytheme_inn')); ?>
                                                            <?php endif; ?>
                                                            <?php if (get_theme_mod('mytheme_ogrn')): ?>
                                                                ОГРН <?php echo esc_html(get_theme_mod('mytheme_ogrn')); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                    <!-- Социальные сети -->
                                                    <div class="lpc-universal-contacts__item lpc-universal-social">
                                                        <?php if (get_theme_mod('mytheme_vk')): ?>
                                                        <a href="<?php echo esc_url(get_theme_mod('mytheme_vk')); ?>" class="lpc-universal-social__link hidden_text has_custom_bg" style="background: rgb(39, 135, 245); color: rgb(255, 255, 255)">
                                                            <span class="lpc-universal-social__icon-box">
                                                                <span class="lpc-universal-social__icon">
                                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/contact/vk.svg" alt="VK" />
                                                                </span>
                                                            </span>
                                                        </a>
                                                        <?php endif; ?>

                                                        <?php if (get_theme_mod('mytheme_ok')): ?>
                                                        <a href="<?php echo esc_url(get_theme_mod('mytheme_ok')); ?>" class="lpc-universal-social__link hidden_text has_custom_bg" style="background: var(--primary-color-l-35); color: rgb(40, 30, 30)">
                                                            <span class="lpc-universal-social__icon-box">
                                                                <span class="lpc-universal-social__icon">
                                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/contact/ok.svg" alt="OK" />
                                                                </span>
                                                            </span>
                                                        </a>
                                                        <?php endif; ?>

                                                        <?php if (get_theme_mod('mytheme_rutube')): ?>
                                                        <a href="<?php echo esc_url(get_theme_mod('mytheme_rutube')); ?>" class="lpc-universal-social__link hidden_text has_custom_bg" style="background: rgb(7, 21, 30); color: rgb(255, 255, 255)">
                                                            <span class="lpc-universal-social__icon-box">
                                                                <span class="lpc-universal-social__icon">
                                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/contact/ru.svg" alt="Rutube" />
                                                                </span>
                                                            </span>
                                                        </a>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Карта -->
                                        <div class="lpc-universal-contacts__map-wrap lpc-col-8-xl lpc-col-8-lg lpc-col-6-md lpc-col-12-xs lpc-col-12-sm">
                                            <div class="lpc-universal-contacts__map-inner lpc-image-type-1">
                                                <div id="map2" class="lpc-universal-contacts__map js-lpc-simple-map lp-map-placeholder"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- API Yandex Map -->
<?php 
$latitude = get_theme_mod('mytheme_latitude', '55.751244');
$longitude = get_theme_mod('mytheme_longitude', '37.618423');
?>
<script src="https://api-maps.yandex.ru/2.1/?apikey=7a322092-0e89-4de6-8bff-0a1795b5548e&lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
    ymaps.ready(init);
    
    var screenWidth = document.documentElement.clientWidth;
    if (screenWidth > 1000) {
        var center = [<?php echo esc_js($latitude); ?>, <?php echo esc_js($longitude); ?>];
        var zoom = 17;
    } else {
        var center = [<?php echo esc_js($latitude); ?>, <?php echo esc_js($longitude); ?>];
        var zoom = 15;
    }

    function init() {
        var myMap = new ymaps.Map('map2', {
            center: center,
            zoom: zoom,
        });
        
        var myPlacemark = new ymaps.Placemark(
            [<?php echo esc_js($latitude); ?>, <?php echo esc_js($longitude); ?>],
            {},
            {
                iconImageSize: [170, 150],
                iconImageOffset: [-100, -180],
            },
        );
        
        myMap.behaviors.disable('scrollZoom');
        myMap.geoObjects.add(myPlacemark);
    }
</script>
<!-- /API Yandex Map -->

<?php get_footer(); ?>