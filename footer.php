<!-- FOOTER -->
<div class="section section--u-i3l98xowf">
    <div class="div div--u-i3a3hm0a4">
        <div class="div div--u-irylfvasj">
            <div class="div div--u-i22o5bdsq">
                <!-- Copyright и реквизиты -->
                <div class="div div--u-ic3l1ozof">
                    <div class="mosaic-site-copyright mosaic-site-copyright--u-ivs7yhxjb">
                        Copyright © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
                    </div>
                    <?php if (get_theme_mod('mytheme_inn')): ?>
                        <div class="text text--u-i60flq6do">
                            <span class="text-block-wrap-div">ИНН: <?php echo esc_html(get_theme_mod('mytheme_inn')); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (get_theme_mod('mytheme_ogrn')): ?>
                        <div class="text text--u-ibudnkn3j">
                            <span class="text-block-wrap-div">ОГРН: <?php echo esc_html(get_theme_mod('mytheme_ogrn')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Телефон и адрес -->
                <div class="div div--u-i6h3pj8s6">
                    <div class="div div--u-ibxthpmkw">
                        <span class="svg_image svg_image--u-ing0y4zod">
                            <svg xmlns="http://www.w3.org/2000/svg" width="51" height="51" viewBox="0 0 51 51">
                                <path d="M40.387 51a8.877 8.877 0 0 1-2.826-.459c-17.6-5.911-30.044-18.344-37-36.953a8.987 8.987 0 0 1 2.056-9.5l2.247-2.24A6.32 6.32 0 0 1 9.361 0h.023a6.245 6.245 0 0 1 4.449 1.856 25.147 25.147 0 0 1 4.468 5.74 6.269 6.269 0 0 1-1.185 7.305l-2.078 2.07a1.559 1.559 0 0 0-.306 1.822 43.412 43.412 0 0 0 17.425 17.3 1.6 1.6 0 0 0 1.88-.277l2.018-2.01a6.359 6.359 0 0 1 7.422-1.137 25.1 25.1 0 0 1 5.684 4.44 6.175 6.175 0 0 1 1.843 4.41 6.281 6.281 0 0 1-1.862 4.5l-2.367 2.359A9.077 9.077 0 0 1 40.387 51zm-2.145-2.473a6.916 6.916 0 0 0 7.026-1.652l2.366-2.358a4.175 4.175 0 0 0 1.237-2.99 4.065 4.065 0 0 0-1.211-2.9 23.416 23.416 0 0 0-5.19-4.076 4.208 4.208 0 0 0-4.908.766l-2.017 2.01a3.722 3.722 0 0 1-4.439.623A45.507 45.507 0 0 1 12.85 19.797a3.693 3.693 0 0 1 .68-4.323l2.078-2.071a4.135 4.135 0 0 0 .807-4.811 23.208 23.208 0 0 0-4.1-5.239A4.124 4.124 0 0 0 9.376 2.13h-.014a4.2 4.2 0 0 0-2.985 1.23L4.13 5.599a6.865 6.865 0 0 0-1.566 7.252c6.716 17.98 18.72 29.984 35.678 35.681z" fill-rule="evenodd" class="path-i2ztpkvrl"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="div div--u-iy9bxzi0m">
                        <?php if (get_theme_mod('mytheme_phone_work_time')): ?>
                            <div class="text text--u-i5nsw79v5">
                                <span class="text-block-wrap-div"><?php echo esc_html(get_theme_mod('mytheme_phone_work_time')); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php
                        $phone_display = '';
                        $phone_link = '';
                        if (function_exists('mytheme_get_phone')) {
                            $phone_display = mytheme_get_phone('main');
                            $phone_link = mytheme_get_phone_link('main');
                        }
                        if (!empty($phone_display)):
                        ?>
                            <div class="list list--u-ikxpe4s8h">
                                <div class="list__item list__item--u-iboggbg4r">
                                    <a target="_self" href="tel:<?php echo esc_attr($phone_link); ?>" class="link-universal link-universal--u-isxhzcfu9">
                                        <div class="text text--u-i1ztaoo2g">
                                            <span class="text-block-wrap-div"><?php echo esc_html($phone_display); ?></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (get_theme_mod('mytheme_address')): ?>
                            <div class="text text--u-ick6icfd5">
                                <span class="text-block-wrap-div"><?php echo esc_html(get_theme_mod('mytheme_address')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Кнопка и соцсети -->
                <div class="div div--u-i0tnm1p5x">
                    <div role="button" class="link-universal link-universal--u-i9wqqd5h5">
                        <div class="text text--u-iccb8ch0c">
                            <span class="text-block-wrap-div">Написать нам</span>
                        </div>
                    </div>
                    <div class="div div--u-ik7golrpf">
                        <div class="list list--u-ivusjbsr5">
                            <!-- VK -->
                            <?php if (get_theme_mod('mytheme_vk')): ?>
                                <div class="list__item list__item--u-itbtc9gvc">
                                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_vk')); ?>" class="link-universal link-universal--u-idfvuzsdo">
                                        <span class="svg_image svg_image--u-ib49gv24c">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="322" height="201" viewBox="0 0 322 201">
                                                <path d="M0 0h54.58v2.185c3.38 5.7 2.06 33.917 4.36 42.6 10.06 37.941 30.08 88.8 70.95 93.946V0h51.3v78.652c33.79-.324 72.45-48 76.41-78.652h52.39c-2.3 38.382-41.67 86.212-69.85 99.408a3.058 3.058 0 0 0 1.09 1.092c7.18 6.791 18.27 10.518 26.19 16.386C288.4 132.41 317.7 169.768 322 201h-56.76c-11.76-29.971-26.81-49.386-54.58-63.359-5-2.516-22.56-11.049-28.38-7.646-2.51 4-1.09 14.747-1.09 20.755V201C59.7 202.2.14 119.781 0 0z" fill-rule="evenodd" class="path-irkakbs5m"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- Telegram -->
                            <?php if (get_theme_mod('mytheme_telegram')): ?>
                                <div class="list__item list__item--u-itbtc9gvc">
                                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_telegram')); ?>" class="link-universal link-universal--u-idfvuzsdo">
                                        <span class="svg_image svg_image--u-ib49gv24c">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="286.84" height="237.406" viewBox="0 0 286.84 237.406">
                                                <path d="M196 87l-62 59s-18.73 15.886 0 29 78 53 78 53 18.52 11.636 29 9 13.52-11.114 18-37 17.27-99.614 25-153c4.23-36.136 6.52-47.364-14-47-16.02 2.364-75 27-75 27L53 88l-39 17s-14.23 7.136-14 13 8.02 9.636 27 15 36.27 12.136 50 10 47-26 47-26l72-49s15.52-12.614 21-9-4.5 13.25-21 28z" fill-rule="evenodd" class="path-ieuuuqbq6"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- OK -->
                            <?php if (get_theme_mod('mytheme_ok')): ?>
                                <div class="list__item list__item--u-itbtc9gvc">
                                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_ok')); ?>" class="link-universal link-universal--u-idfvuzsdo">
                                        <span class="svg_image svg_image--u-ib49gv24c">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1258.2 2174.7" width="1258.2" height="2174.7">
                                                <path d="M629.9 1122.4c310-.1 561.1-251.5 561-561.4C1190.8 251 939.4-.1 629.5 0S68.4 251.5 68.5 561.4c.4 309.8 251.6 560.8 561.4 561m0-793.4c128.4 0 232.5 104.1 232.5 232.5S758.3 793.9 629.9 793.9 397.4 689.8 397.4 561.4c.2-128.3 104.2-232.3 232.5-232.4zm226.9 1251.3c115.5-26.2 225.7-71.9 326-135 76.4-49.3 98.4-151.1 49.1-227.5-48.5-75.2-148.3-97.9-224.5-51-231.1 144.5-524.5 144.5-755.6 0-76.7-48.1-178-25.1-226.3 51.5C-23 1295-.2 1396.6 76.6 1445.1c.1 0 .2.1.2.1 100.2 63 210.4 108.7 325.8 135L88.8 1894c-62.5 66-59.6 170.2 6.5 232.7 63.5 60 162.7 60 226.2 0l308.2-308.4 308.4 308.4c64.2 64.1 168.1 64.1 232.3 0 64.1-64.2 64.1-168.1 0-232.3l-313.6-314.1z" class="path-iepz1qyzg"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- WhatsApp -->
                            <?php if (get_theme_mod('mytheme_whatsapp')): ?>
                                <div class="list__item list__item--u-itbtc9gvc">
                                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_whatsapp')); ?>" class="link-universal link-universal--u-idfvuzsdo">
                                        <span class="svg_image svg_image--u-ib49gv24c">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="143" height="141.969" viewBox="0 0 143 141.969">
                                                <path d="M5.6 137.612c.33-2.92 1.59-6.038 2.45-8.666 1.18-3.637 1.49-7.027 2.67-10.665.49-1.518 2.34-5.534 1.78-6.888a10.187 10.187 0 0 0-1.56-2.444 67.488 67.488 0 0 1-4.23-7.776 116.022 116.022 0 0 1-5.12-15.331c-.67-2.354-.33-4.347-.89-6.888-.75-3.349-1.01-9.716-.23-13.109l.45-6c1.2-4.928 1.97-9.827 3.79-14.22C13.07 25.4 26.36 12.995 46.59 4.52 58.37-.42 78.31-1.745 91.81 2.743c10.7 3.558 18.72 8.631 26.73 14.664.74.815 1.48 1.63 2.23 2.444a65.1 65.1 0 0 1 6.68 7.333 80.752 80.752 0 0 1 12.47 23.329c.99 2.978.98 5.681 1.79 8.888.9 3.6 1.86 9.773.89 14v3.555c-.51 2.194-.32 4.638-.89 6.888-1 3.921-1.64 7.975-3.12 11.554-8.43 20.366-21.9 34.053-42.33 42.438-12.37 5.078-32.89 5.58-46.33 1.111-3.76-1.249-8.08-2.677-11.36-4.666-1.88-1.135-4.2-3.294-6.69-3.555-1.8 1.31-6.04 1.484-8.46 2.222-3.26.99-6.79 1.55-10.25 2.666a35.808 35.808 0 0 1-7.57 1.998zM40.35 31.405c-.83.538-2 .413-2.9.889-2.22 1.175-5.34 5.361-6.68 7.554-5.5 9-2.03 22.045 2.45 29.329 11.5 18.712 25.97 32.891 48.78 40.439 4.02 1.329 11.33 3.484 16.71 2.222 9.34-2.189 17.51-8.235 16.04-20.442a102.037 102.037 0 0 0-13.59-6.888c-2.59-1.135-4.58-2.986-8.24-3.11a15.631 15.631 0 0 0-1.78 1.333c-1.06 1.422-1.85 2.828-2.9 4.222-.59.79-1.63 1.432-2.23 2.221-.76 1.011-1.68 2.848-2.89 3.333h-2.23c-1.05-.822-2.47-.98-3.79-1.555a51.766 51.766 0 0 1-6.01-3.111 55.523 55.523 0 0 1-16.26-15.109c-1.71-2.381-4.81-4.888-4.9-8.665 1.83-1.81 6.35-7.073 7.13-9.554.86-2.76-1.33-5.282-2.01-6.888-2.44-5.762-3.79-12-8.02-16z" fill-rule="evenodd" class="path-i4f2cbbx1"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Нижняя часть футера -->
            <div class="div div--u-ih3h5tazc">
                <div class="div div--u-ibhcgycgd">
                    <div class="blocklist__item_title blocklist__item_title--u-ifk3wyu8n">
                        <span class="text-block-wrap-div">Получите новые знания, запишитесь на курс!</span>
                    </div>
                </div>
                <div class="div div--u-ijft4pmxz">
                    <button role="button" class="button-up button-up--u-igorgqpz7">
                        <span class="svg_image svg_image--u-iorsre8w1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="55.03" height="30.125" viewBox="0 0 55.03 30.125">
                                <path d="M54.42 29.509a1.994 1.994 0 0 1-2.84 0L27.12 4.818 3.37 28.789a1.968 1.968 0 0 1-2.8 0 2.015 2.015 0 0 1 0-2.824L25.75.549a1.968 1.968 0 0 1 2.8 0c.02.018.02.042.04.061a1.767 1.767 0 0 1 .24.2l25.59 25.826a2.034 2.034 0 0 1 0 2.873z" fill-rule="evenodd" class="path-i62gk2rwg"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SIDE PANEL (Мобильное меню) -->
<div class="side-panel side-panel--u-i9vrxi7xh">
    <div class="side-panel__button-open side-panel__button-open--u-ikkgsxyge">
        <span class="svg_image svg_image--u-i9yah5ua6">
            <svg xmlns="http://www.w3.org/2000/svg" width="22.13" height="14.562" viewBox="0 0 22.13 14.562">
                <path d="M20.85 8.556H1.28a1.285 1.285 0 0 1 0-2.57h19.57a1.285 1.285 0 0 1 0 2.57zm0-6H1.28a1.285 1.285 0 0 1 0-2.57h19.57a1.285 1.285 0 0 1 0 2.57zm-19.57 9.43h19.57a1.285 1.285 0 0 1 0 2.57H1.28a1.285 1.285 0 0 1 0-2.569z" fill-rule="evenodd" class="path-ijde2zpzp"></path>
            </svg>
        </span>
    </div>
    <div class="side-panel__mask side-panel__mask--u-ie2ugt4qt" style="display: none; opacity: 0; transition: opacity 0.3s;"></div>
    <div class="side-panel__content side-panel__content--u-i2jo7s1fj" style="left: auto; right: 0px; display: none; transform: translate3d(315px, 0px, 0px); transition: transform 0.3s;">
        <div class="side-panel__button-close side-panel__button-close--u-iubk127oq">
            <span class="svg_image svg_image--u-iqgq8nf8x">
                <svg xmlns="http://www.w3.org/2000/svg" width="16.41" height="16.406" viewBox="0 0 16.41 16.406">
                    <path d="M9.86 8.058l6.16 6.161a1.286 1.286 0 0 1-1.82 1.817L8.04 9.875l-5.86 5.857A1.252 1.252 0 0 1 .41 13.96l5.86-5.857-5.9-5.9A1.282 1.282 0 1 1 2.18.386l5.9 5.9L13.9.471a1.252 1.252 0 1 1 1.77 1.772z" fill-rule="evenodd" class="path-id3efmiyd"></path>
                </svg>
            </span>
        </div>
        <div class="side-panel__content-inner side-panel__content-inner--u-ig5mf5hls">
            <?php
            wp_nav_menu(array(
                'theme_location'  => 'primary',
                'container'       => 'div',
                'container_class' => 'ver-menu ver-menu--u-ijov1ryfh',
                'menu_class'      => 'ver-menu__list ver-menu__list--u-ieml6up2m',
                'walker'          => new Custom_Walker_Mobile_Menu(),
                'fallback_cb'     => false,
            ));
            ?>
        </div>
    </div>
</div>


<!-- Всплывающая форма Политики конфиденциальности -->
<div class="popup-form py-3" id="popupForm">
    <div class="form-content container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-9">
                <p class="mb-md-0">
                    На нашем сайте используются cookie-файлы, в том числе сервисов
                    веб-аналитики. Используя сайт, вы соглашаетесь на <a
                        href="<?= get_template_directory_uri(); ?>/docs/Consent-to-the-processing-of-personal-data.pdf"
                        target="blank"
                        >обработку персональных данных</a
                    > при помощи cookie-файлов. Подробнее об обработке персональных данных
                    вы можете узнать в <a
                        href="<?= get_template_directory_uri(); ?>/docs/Privacy-Policy.pdf"
                        target="blank"
                        >Политике конфиденциальности.</a
                    >
                </p>
            </div>
            <div class="col-md-3 text-md-center">
                <button id="closeBtn" class="btn action-btn">Понятно</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popupForm = document.getElementById('popupForm');
        const closeBtn = document.getElementById('closeBtn');

        // Проверяем нужно ли показывать форму
        function shouldShowPopup() {
            const lastClosed = localStorage.getItem('popupLastClosed');

            // Если пользователь никогда не закрывал форму
            if (!lastClosed) return true;

            // Если прошло более 1 часа (3600000 миллисекунд) с последнего закрытия
            const now = new Date().getTime();
            return now - parseInt(lastClosed) > 3600000;
        }

        // Показываем форму если нужно
        if (shouldShowPopup()) {
            setTimeout(() => {
                popupForm.classList.add('active');
            }, 3000);
        }

        // Функция закрытия формы
        function closePopup() {
            popupForm.classList.remove('active');

            // Сохраняем время закрытия
            localStorage.setItem('popupLastClosed', new Date().getTime().toString());
        }

        // Закрытие по кнопке
        closeBtn.addEventListener('click', closePopup);
    });
</script>
<!-- /Всплывающая форма Политики конфиденциальности -->



<!-- PhotoSwipe -->
<div id="pswp-mosaic" class="pswp pswp--open" tabindex="-1" role="dialog" style="display: none;">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item">
                <div class="pswp__zoom-wrap">
                    <img class="pswp__img" src="" />
                </div>
            </div>
        </div>
        <button class="pswp__button pswp__button--close">&times;</button>
    </div>
</div>

</div>

<script src='https://www.google.com/recaptcha/api.js?render=6LdV1IcUAAAAADRQAhpGL8dVj5_t0nZDPh9m_0tn'></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LdV1IcUAAAAADRQAhpGL8dVj5_t0nZDPh9m_0tn', {action: 'contact_form'}).then(function(token) {
            document.getElementById('g-recaptcha-response-callback').value = token;
            document.getElementById('g-recaptcha-response-username').value = token;
            document.getElementById('g-recaptcha-response-course').value = token;
        });
    });
</script>

<!-- Показываем сообщение об успешной отправки -->
<div style="display: <?php echo $_SESSION['display'] ?>;" onclick="modalClose();">
    <div id="background-msg" style="display: <?php echo $_SESSION['display'] ?>;"></div>
    <button id="btn-close" type="button" class="btn-close btn-close-white" onclick="modalClose();" style="position: absolute; z-index: 9999; top: 15px; right: 15px;"></button>
    <div id="message">
        <?php echo $_SESSION['recaptcha']; unset( $_SESSION['recaptcha'] ); ?>
    </div> 
</div>

<script>
function modalClose() {
    var backgroundMsg = document.getElementById('background-msg');
    var message = document.getElementById('message');
    var btnClose = document.getElementById('btn-close');
    
    if (backgroundMsg) backgroundMsg.style.display = 'none';
    if (message) message.style.display = 'none';
    if (btnClose) btnClose.style.display = 'none';
}
</script>
		
<?php wp_footer(); ?>
</body>

</html>