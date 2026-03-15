<?php
	
	session_start();
	if ( isset( $_SESSION['win'] ) ) {
		unset( $_SESSION['win'] );
		$_SESSION['display'] = "block";
	} else { $_SESSION['display'] = "none"; }
	
?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="mosaic-wrap">
        <div class="root root--u-ibcywfqup">

            <!-- POPUP: Обратный звонок -->
            <div class="mosaic-popup mosaic-popup--u-i6fnbs3ah" id="popup-callback">
                <div class="mosaic-popup__inner-bg mosaic-popup__inner-bg--u-iim3w5fa1">
                    <div class="mosaic-popup__inner-data mosaic-popup__inner-data--u-ibozsrwvn">
                        <div class="mosaic-popup__close mosaic-popup__close--u-iqxwzmwlp">
                            <span class="svg_image svg_image--u-im0u0viwr">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16.41" height="16.406" viewBox="0 0 16.41 16.406">
                                    <path
                                        d="M9.86 8.058l6.16 6.161a1.286 1.286 0 0 1-1.82 1.817L8.04 9.875l-5.86 5.857A1.252 1.252 0 0 1 .41 13.96l5.86-5.857-5.9-5.9A1.282 1.282 0 1 1 2.18.386l5.9 5.9L13.9.471a1.252 1.252 0 1 1 1.77 1.772z"
                                        fill-rule="evenodd"
                                        class="path-ij1ve12k6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="mosaic-form mosaic-form--u-iyjjmcepo">
                            <form action="<?php echo get_template_directory_uri(); ?>/assets/mails/callback-mail.php" class="mosaic-form__form mosaic-form__form--u-iwsx1397a" method="POST">
                                <div class="mosaic-form__title mosaic-form__title--u-iacq4vc9z">
                                    <span class="text-block-wrap-div">Записаться на курс</span>
                                </div>
                                <div class="div div--u-i1om4z0p8">
                                    <div class="mosaic-form__field mosaic-form__field--u-i3bdh0fvm">
                                        <div class="mosaic-form__error mosaic-form__error--u-in1yotpdk is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-i978tvlqr">
                                            <span class="mosaic-form__name mosaic-form__name--u-ibutptk30">
                                                <span class="text-block-wrap-div">Строка ввода:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-itjutd3kf is-removed">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-i8vj08i1m">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-ijow40kc0">
                                                <span class="svg_image svg_image--u-i866kyae0"></span>
                                            </div>
                                            <input type="text" placeholder="Ваш адрес" name="address" class="mosaic-form__text mosaic-form__text--u-i3kvj8jn2" />
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-izhs2gv50 is-removed"></div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-i38c5r4tj">
                                        <div class="mosaic-form__error mosaic-form__error--u-i0gsidc3q is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-ignt4mbed">
                                            <span class="mosaic-form__name mosaic-form__name--u-imir0ihrj">
                                                <span class="text-block-wrap-div">Телефон:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-i4bpgonih">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-idpfeo902">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-ionl1f5vp">
                                                <span class="svg_image svg_image--u-imgr86jq5"></span>
                                            </div>
                                            <input type="tel" name="tel" placeholder="Телефон *" class="mosaic-form__text mosaic-form__text--u-it5nzmjjk" />
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-itxjzugwf is-removed"></div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-i37kfqwac">
                                        <div class="mosaic-form__error mosaic-form__error--u-ihxznargw is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-imqu3rmw8">
                                            <span class="mosaic-form__name mosaic-form__name--u-io7w7mawl">
                                                <span class="text-block-wrap-div">E-mail:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-iipk53m0x is-removed">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-ijqm7e5ls">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-ifu41tibu">
                                                <span class="svg_image svg_image--u-i9lnmh20e"></span>
                                            </div>
                                            <input type="email" name="email" placeholder="E-mail" class="mosaic-form__text mosaic-form__text--u-i4zr6bis1" />
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-ilv3eglhg is-removed"></div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-ijupsfj38">
                                        <div class="mosaic-form__error mosaic-form__error--u-ik0ykfi0y is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-i38hynusa">
                                            <span class="mosaic-form__name mosaic-form__name--u-iudtiaqoa">
                                                <span class="text-block-wrap-div">Область ввода:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-iss44b7p9 is-removed">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-i41aqhogt">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-igapui408">
                                                <span class="svg_image svg_image--u-icmc4sm76"></span>
                                            </div>
                                            <textarea name="question" placeholder="Ваш вопрос" class="mosaic-form__textarea mosaic-form__textarea--u-ia9y9rf9l"></textarea>
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-ivzaandjw is-removed"></div>
                                    </div>
                                </div>
                                <div class="mosaic-form__field mosaic-form__field--u-ixu80mu3q">
                                    <div class="mosaic-form__error mosaic-form__error--u-ih65iwl2d is-removed">
                                        <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                    </div>
                                    <div class="mosaic-form__header mosaic-form__header--u-i1n5rh36d">
                                        <span class="mosaic-form__name mosaic-form__name--u-imeq4ck1o">
                                            <span class="text-block-wrap-div">Галочка</span>
                                        </span>
                                        <span class="mosaic-form__required mosaic-form__required--u-i4jxv8c62 is-removed">
                                            <span class="text-block-wrap-div">* </span>
                                        </span>
                                    </div>
                                    <label class="mosaic-form__label mosaic-form__label--u-igkj6ded9">
                                        <input type="checkbox" name="agreement" value="Ознакомлен с пользовательским соглашением" class="mosaic-form__checkbox mosaic-form__checkbox--u-ipkpe12aq" />
                                        <span class="mosaic-form__checkbox-icon mosaic-form__checkbox-icon--u-i4ba4orh4"></span>
                                        <span class="mosaic-form__value mosaic-form__value--u-iuv4z9pvs">
                                            <span class="text-block-wrap-div">Ознакомлен с пользовательским соглашением</span>
                                        </span>
                                    </label>
                                    <div class="mosaic-form__note mosaic-form__note--u-i9wu11dpk is-removed"></div>
                                </div>
								<input type="hidden" id="g-recaptcha-response-callback" name="g-recaptcha-response">
                                <button type="submit" class="mosaic-form__button mosaic-form__button--u-ihqwpp16f">
                                    <span class="button__text button__text--u-ixtzflqi8">
                                        <span class="text-block-wrap-div">Отправить</span>
                                    </span>
                                </button>
                            </form>
                            <div class="mosaic-form__success mosaic-form__success--u-ilw9exebi is-removed">
                                <div class="mosaic-form__success__text mosaic-form__success__text--u-iimvls0q9">
                                    <span class="text-block-wrap-div">Спасибо! Форма отправлена</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- POPUP: Написать нам -->
            <div class="mosaic-popup mosaic-popup--u-i6fnbs3ah" id="popup-username">
                <div class="mosaic-popup__inner-bg mosaic-popup__inner-bg--u-iim3w5fa1">
                    <div class="mosaic-popup__inner-data mosaic-popup__inner-data--u-ibozsrwvn">
                        <div class="mosaic-popup__close mosaic-popup__close--u-iqxwzmwlp">
                            <span class="svg_image svg_image--u-im0u0viwr">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16.41" height="16.406" viewBox="0 0 16.41 16.406">
                                    <path
                                        d="M9.86 8.058l6.16 6.161a1.286 1.286 0 0 1-1.82 1.817L8.04 9.875l-5.86 5.857A1.252 1.252 0 0 1 .41 13.96l5.86-5.857-5.9-5.9A1.282 1.282 0 1 1 2.18.386l5.9 5.9L13.9.471a1.252 1.252 0 1 1 1.77 1.772z"
                                        fill-rule="evenodd"
                                        class="path-ij1ve12k6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="mosaic-form mosaic-form--u-iyjjmcepo">
                            <form action="<?php echo get_template_directory_uri(); ?>/assets/mails/callback-username.php" class="mosaic-form__form mosaic-form__form--u-iwsx1397a" method="POST">
                                <div class="mosaic-form__title mosaic-form__title--u-iacq4vc9z">
                                    <span class="text-block-wrap-div">Написать нам</span>
                                </div>
                                <div class="div div--u-i1om4z0p8">
                                    <div class="mosaic-form__field mosaic-form__field--u-i3bdh0fvm">
                                        <div class="mosaic-form__error mosaic-form__error--u-in1yotpdk is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-i978tvlqr">
                                            <span class="mosaic-form__name mosaic-form__name--u-ibutptk30">
                                                <span class="text-block-wrap-div">Строка ввода:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-itjutd3kf is-removed">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-i8vj08i1m">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-ijow40kc0">
                                                <span class="svg_image svg_image--u-i866kyae0"></span>
                                            </div>
                                            <input type="text" placeholder="Ваше имя" name="username" class="mosaic-form__text mosaic-form__text--u-i3kvj8jn2" />
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-izhs2gv50 is-removed"></div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-i38c5r4tj">
                                        <div class="mosaic-form__error mosaic-form__error--u-i0gsidc3q is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-ignt4mbed">
                                            <span class="mosaic-form__name mosaic-form__name--u-imir0ihrj">
                                                <span class="text-block-wrap-div">Телефон:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-i4bpgonih">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-idpfeo902">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-ionl1f5vp">
                                                <span class="svg_image svg_image--u-imgr86jq5"></span>
                                            </div>
                                            <input type="tel" name="tel" placeholder="Телефон *" class="mosaic-form__text mosaic-form__text--u-it5nzmjjk" />
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-itxjzugwf is-removed"></div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-i37kfqwac">
                                        <div class="mosaic-form__error mosaic-form__error--u-ihxznargw is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-imqu3rmw8">
                                            <span class="mosaic-form__name mosaic-form__name--u-io7w7mawl">
                                                <span class="text-block-wrap-div">E-mail:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-iipk53m0x is-removed">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-ijqm7e5ls">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-ifu41tibu">
                                                <span class="svg_image svg_image--u-i9lnmh20e"></span>
                                            </div>
                                            <input type="email" name="email" placeholder="E-mail" class="mosaic-form__text mosaic-form__text--u-i4zr6bis1" />
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-ilv3eglhg is-removed"></div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-ijupsfj38">
                                        <div class="mosaic-form__error mosaic-form__error--u-ik0ykfi0y is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-i38hynusa">
                                            <span class="mosaic-form__name mosaic-form__name--u-iudtiaqoa">
                                                <span class="text-block-wrap-div">Область ввода:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-iss44b7p9 is-removed">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-i41aqhogt">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-igapui408">
                                                <span class="svg_image svg_image--u-icmc4sm76"></span>
                                            </div>
                                            <textarea name="question" placeholder="Ваш вопрос" class="mosaic-form__textarea mosaic-form__textarea--u-ia9y9rf9l"></textarea>
                                        </div>
                                        <div class="mosaic-form__note mosaic-form__note--u-ivzaandjw is-removed"></div>
                                    </div>
                                </div>
                                <div class="mosaic-form__field mosaic-form__field--u-ixu80mu3q">
                                    <div class="mosaic-form__error mosaic-form__error--u-ih65iwl2d is-removed">
                                        <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                    </div>
                                    <div class="mosaic-form__header mosaic-form__header--u-i1n5rh36d">
                                        <span class="mosaic-form__name mosaic-form__name--u-imeq4ck1o">
                                            <span class="text-block-wrap-div">Галочка</span>
                                        </span>
                                        <span class="mosaic-form__required mosaic-form__required--u-i4jxv8c62 is-removed">
                                            <span class="text-block-wrap-div">* </span>
                                        </span>
                                    </div>
                                    <label class="mosaic-form__label mosaic-form__label--u-igkj6ded9">
                                        <input type="checkbox" name="agreement" value="Ознакомлен с пользовательским соглашением" class="mosaic-form__checkbox mosaic-form__checkbox--u-ipkpe12aq" required checked  />
                                        <span class="mosaic-form__checkbox-icon mosaic-form__checkbox-icon--u-i4ba4orh4"></span>
                                        <span class="mosaic-form__value mosaic-form__value--u-iuv4z9pvs">
                                            <span class="text-block-wrap-div">Ознакомлен с <a href="<?= get_template_directory_uri(); ?>/docs/Privacy-Policy.pdf" target="blank" >Политике конфиденциальности.</a></span>
                                        </span>
                                    </label>
                                    <div class="mosaic-form__note mosaic-form__note--u-i9wu11dpk is-removed"></div>
                                </div>
								<input type="hidden" id="g-recaptcha-response-username" name="g-recaptcha-response">
                                <button type="submit" class="mosaic-form__button mosaic-form__button--u-ihqwpp16f">
                                    <span class="button__text button__text--u-ixtzflqi8">
                                        <span class="text-block-wrap-div">Отправить</span>
                                    </span>
                                </button>
                            </form>
                            <div class="mosaic-form__success mosaic-form__success--u-ilw9exebi is-removed">
                                <div class="mosaic-form__success__text mosaic-form__success__text--u-iimvls0q9">
                                    <span class="text-block-wrap-div">Спасибо! Форма отправлена</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- POPUP: Запись на курс -->
            <div class="mosaic-popup mosaic-popup--u-i6fnbs3ah" id="popup-course">
                <div class="mosaic-popup__inner-bg mosaic-popup__inner-bg--u-iim3w5fa1">
                    <div class="mosaic-popup__inner-data mosaic-popup__inner-data--u-ibozsrwvn">
                        <div class="mosaic-popup__close mosaic-popup__close--u-iqxwzmwlp">
                            <span class="svg_image svg_image--u-im0u0viwr">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16.41" height="16.406" viewBox="0 0 16.41 16.406">
                                    <path
                                        d="M9.86 8.058l6.16 6.161a1.286 1.286 0 0 1-1.82 1.817L8.04 9.875l-5.86 5.857A1.252 1.252 0 0 1 .41 13.96l5.86-5.857-5.9-5.9A1.282 1.282 0 1 1 2.18.386l5.9 5.9L13.9.471a1.252 1.252 0 1 1 1.77 1.772z"
                                        fill-rule="evenodd"
                                        class="path-ij1ve12k6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="mosaic-form--u-ijpswxniq mosaic-form--u-ijpswxniq-1 mosaic-form--u-iyjjmcepo">
                            <form action="<?php echo get_template_directory_uri(); ?>/assets/mails/course-form.php" class="mosaic-form__form mosaic-form__form--u-ik5179561" method="POST">
                                <div class="mosaic-form__title mosaic-form__title--u-ixicssomo">
                                    <span class="text-block-wrap-div">Оставить заявку прямо сейчас</span>
                                </div>
                                <div class="div div--u-i0ejolayq">
                                    <div class="mosaic-form__field mosaic-form__field--u-ieowq877k">
                                        <div class="mosaic-form__error mosaic-form__error--u-ig4i4wyjd is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-iyb99p7le">
                                            <span class="mosaic-form__name mosaic-form__name--u-i38jv27ap">
                                                <span class="text-block-wrap-div">ФИО</span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-i250lhgc3">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-i76ficuys">
                                                <span class="svg_image svg_image--u-iayw7ng0h"></span>
                                            </div>
                                            <input type="text" name="fio" placeholder="ФИО" class="mosaic-form__text mosaic-form__text--u-idpnhogca" />
                                        </div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-iemthdq2t">
                                        <div class="mosaic-form__header mosaic-form__header--u-i2r5h3wgg">
                                            <span class="mosaic-form__name mosaic-form__name--u-iirz7s5jo">
                                                <span class="text-block-wrap-div">Телефон:</span>
                                            </span>
                                            <span class="mosaic-form__required mosaic-form__required--u-idef95otq">
                                                <span class="text-block-wrap-div">* </span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-ivge1fzf5">
                                            <input type="tel" name="tel" placeholder="Телефон *" class="mosaic-form__text mosaic-form__text--u-iqc0dx3o9" required />
                                        </div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-if7a3g01p">
                                        <div class="mosaic-form__header mosaic-form__header--u-iujjp290g">
                                            <span class="mosaic-form__name mosaic-form__name--u-ipv2ba8vq">
                                                <span class="text-block-wrap-div">E-mail:</span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-ihp67drj4">
                                            <input type="email" name="email" placeholder="E-mail" class="mosaic-form__text mosaic-form__text--u-izmi4wbgt" />
                                        </div>
                                    </div>
                                    
                                    <div class="mosaic-form__field mosaic-form__field--u-i179qeij8">
                                        <div class="mosaic-form__header mosaic-form__header--u-inxxb5vss">
                                            <span class="mosaic-form__name mosaic-form__name--u-idvhkkseq">
                                                <span class="text-block-wrap-div">Выберите направление курса</span>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <select
                                                name="course_direction"
                                                id="course_direction"
                                                class="form-control"
                                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;"
                                                required>
                                                <option value="">Выберите направление</option>
                                                <?php
                                                $args = array(
                                                    'post_type'      => 'catalog',
                                                    'posts_per_page' => -1,
                                                    'orderby'        => 'title',
                                                    'order'          => 'ASC'
                                                );

                                                $query = new WP_Query($args);

                                                if ($query->have_posts()) {
                                                    while ($query->have_posts()) {
                                                        $query->the_post();
                                                            echo '<option value="' . esc_attr(get_the_title()) . '">' . esc_html(get_the_title()) . '</option>';
                                                    }
                                                }
                                                wp_reset_postdata();
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-i1ev5l1de">
                                        <div class="mosaic-form__header mosaic-form__header--u-i8twxx04e">
                                            <span class="mosaic-form__name mosaic-form__name--u-i8htbm2je">
                                                <span class="text-block-wrap-div">Формат обучения</span>
                                            </span>
                                        </div>
                                        <select name="learning_format" class="mosaic-form__select mosaic-form__select--u-i6enzw69k">
                                            <option value="">Выберите формат</option>
                                            <option value="Онлайн">Онлайн</option>
                                            <option value="Очно">Очно</option>
                                        </select>
                                    </div>
                                    <div class="mosaic-form__field mosaic-form__field--u-ieowq877k">
                                        <div class="mosaic-form__error mosaic-form__error--u-ig4i4wyjd is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <div class="mosaic-form__header mosaic-form__header--u-iyb99p7le">
                                            <span class="mosaic-form__name mosaic-form__name--u-i38jv27ap">
                                                <span class="text-block-wrap-div">Количество человек</span>
                                            </span>
                                        </div>
                                        <div class="mosaic-form__body mosaic-form__body--u-i250lhgc3">
                                            <div class="mosaic-form__icon mosaic-form__icon--u-i76ficuys">
                                                <span class="svg_image svg_image--u-iayw7ng0h"></span>
                                            </div>
                                            <input type="number" name="kolvo_user" placeholder="Количество человек" class="mosaic-form__text mosaic-form__text--u-idpnhogca" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mosaic-form__field mosaic-form__field--u-ig753muzh">
                                    <label class="mosaic-form__label mosaic-form__label--u-iffag8czd">
                                        <input type="checkbox" name="agreement" value="Ознакомлен с пользовательским соглашением" class="mosaic-form__checkbox mosaic-form__checkbox--u-ipllxarsi" required checked />
                                        <span class="mosaic-form__checkbox-icon mosaic-form__checkbox-icon--u-imvd5rcjk"></span>
                                        <span class="mosaic-form__value mosaic-form__value--u-iduim6tjb">
                                            <span class="text-block-wrap-div">Ознакомлен с <a href="<?= get_template_directory_uri(); ?>/docs/Privacy-Policy.pdf" target="blank" >Политике конфиденциальности.</a></span>
                                        </span>
                                    </label>
                                </div>
								<input type="hidden" id="g-recaptcha-response-course" name="g-recaptcha-response">
                                <button type="submit" class="mosaic-form__button mosaic-form__button--u-ikpkw5uss">
                                    <span class="button__text button__text--u-ipdo2852o">
                                        <span class="text-block-wrap-div">Отправить</span>
                                    </span>
                                </button>
                            </form>
                            <div class="mosaic-form__success mosaic-form__success--u-ilw9exebi is-removed">
                                <div class="mosaic-form__success__text mosaic-form__success__text--u-iimvls0q9">
                                    <span class="text-block-wrap-div">Спасибо! Форма отправлена</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HEADER для внутренних страниц -->
            <div class="section section--u-iuqh7gk14">
                <div class="div div--u-ibl6xyh2p">
                    <div class="section section--u-ie41r58os">
                        <div class="div div--u-ige14ztwb">
                            <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;">
                                <!-- Телефон -->
                                <div class="div div--u-iz7p7tcug">
                                    <div class="list list--u-i99wkb6ie">
                                        <?php
                                        $phone_display = '';
                                        $phone_link = '';
                                        if (function_exists('mytheme_get_phone')) {
                                            $phone_display = mytheme_get_phone('main');
                                            $phone_link = mytheme_get_phone_link('main');
                                        }
                                        if (!empty($phone_display)):
                                        ?>
                                            <div class="list__item list__item--u-iqvnaqrvp">
                                                <a target="_self" href="tel:<?php echo esc_attr($phone_link); ?>" class="link-universal link-universal--u-i2gx7d7z2">
                                                    <div class="text text--u-ivur73rjr">
                                                        <span class="text-block-wrap-div"><?php echo esc_html($phone_display); ?></span>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Дополнительный телефон -->
                                <div class="div div--u-iz7p7tcug">
                                    <div class="list list--u-i99wkb6ie">
                                        <?php
                                        $additional_phone_country = get_theme_mod('additional_phone_country_code', '+7');
                                        $additional_phone_region = get_theme_mod('additional_phone_region_code', '');
                                        $additional_phone_number = get_theme_mod('additional_phone_number', '');
                                        
                                        if (!empty($additional_phone_number)):
                                            // Формируем отображаемый номер
                                            $additional_phone_display = $additional_phone_country;
                                            if (!empty($additional_phone_region)) {
                                                $additional_phone_display .= ' (' . $additional_phone_region . ') ';
                                            } else {
                                                $additional_phone_display .= ' ';
                                            }
                                            $additional_phone_display .= $additional_phone_number;
                                            
                                            // Формируем ссылку (только цифры и +)
                                            $additional_phone_link = preg_replace('/[^0-9+]/', '', $additional_phone_country . $additional_phone_region . $additional_phone_number);
                                        ?>
                                            <div class="list__item list__item--u-iqvnaqrvp">
                                                <a target="_self" href="tel:<?php echo esc_attr($additional_phone_link); ?>" class="link-universal link-universal--u-i2gx7d7z2">
                                                    <div class="text text--u-ivur73rjr">
                                                        <span class="text-block-wrap-div"><?php echo esc_html($additional_phone_display); ?></span>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Адрес (закомментирован)
                            <div class="div div--u-ida5z40u2">
                                <div class="list list--u-i8xxmxuqc">
                                    <?php if (get_theme_mod('mytheme_address')): ?>
                                        <div class="list__item list__item--u-if57zb42r">
                                            <div class="text text--u-iks06b845">
                                                <span class="text-block-wrap-div"><?php echo esc_html(get_theme_mod('mytheme_address')); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            -->
							
							<!-- skolkovo -->
                            <div class="div div--u-if8m6hfjg">
                                <div class="list list--u-id9txhv53">
                                    <div class="list__item list__item--u-izm0hwedg">
                                        <a href="/skolkovo">
                                            <img src="https://rcpk.site/wp-content/uploads/2026/03/skolkovo-logo.png">
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp -->
<!--                             <?php if (get_theme_mod('mytheme_whatsapp')): ?>
                                <div class="div div--u-if8m6hfjg">
                                    <div class="list list--u-id9txhv53">
                                        <div class="list__item list__item--u-izm0hwedg">
                                            <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_whatsapp')); ?>" class="link-universal link-universal--u-itn05k4qx">
                                                <div class="div div--u-ieeym1dd5">
                                                    <span class="svg_image svg_image--u-in68x7mcj">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 418.135 418.135" width="418.135" height="418.135">
                                                            <path
                                                                d="M198.929.242C88.5 5.5 1.356 97.466 1.691 208.02c.102 33.672 8.231 65.454 22.571 93.536L2.245 408.429c-1.191 5.781 4.023 10.843 9.766 9.483l104.723-24.811c26.905 13.402 57.125 21.143 89.108 21.631 112.869 1.724 206.982-87.897 210.5-200.724C420.113 93.065 320.295-5.538 198.929.242zm124.957 321.955c-30.669 30.669-71.446 47.559-114.818 47.559-25.396 0-49.71-5.698-72.269-16.935l-14.584-7.265-64.206 15.212 13.515-65.607-7.185-14.07c-11.711-22.935-17.649-47.736-17.649-73.713 0-43.373 16.89-84.149 47.559-114.819 30.395-30.395 71.837-47.56 114.822-47.56 43.372.001 84.147 16.891 114.816 47.559 30.669 30.669 47.559 71.445 47.56 114.817-.001 42.986-17.166 84.428-47.561 114.822z"
                                                                class="path-ixkhr0ux7"></path>
                                                            <path
                                                                d="M309.712 252.351l-40.169-11.534a14.971 14.971 0 0 0-14.816 3.903l-9.823 10.008c-4.142 4.22-10.427 5.576-15.909 3.358-19.002-7.69-58.974-43.23-69.182-61.007-2.945-5.128-2.458-11.539 1.158-16.218l8.576-11.095a14.97 14.97 0 0 0 1.847-15.21l-16.9-38.223c-4.048-9.155-15.747-11.82-23.39-5.356-11.211 9.482-24.513 23.891-26.13 39.854-2.851 28.144 9.219 63.622 54.862 106.222 52.73 49.215 94.956 55.717 122.449 49.057 15.594-3.777 28.056-18.919 35.921-31.317 5.362-8.453 1.128-19.679-8.494-22.442z"
                                                                class="path-i558hbw9a"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="text text--u-i2wwkv9f5">
                                                    <span class="text-block-wrap-div">WhatsApp</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?> -->

                            <!-- Telegram -->
<!--                             <?php if (get_theme_mod('mytheme_telegram')): ?>
                                <div class="div div--u-i8gb385xk">
                                    <div class="list list--u-ixebabtr6">
                                        <div class="list__item list__item--u-ih8p9c514">
                                            <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_telegram')); ?>" class="link-universal link-universal--u-in5marjrz">
                                                <div class="div div--u-i5ii0cj1c">
                                                    <span class="svg_image svg_image--u-iovkrsaj0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="29.719" height="25.281" viewBox="0 0 29.719 25.281">
                                                            <path
                                                                d="M11.128 23.676l.532-7.074c.308-.2.514-.529.8-.746.506-.386.914-.868 1.42-1.254l.3-.328c1.248-.953 2.242-2.212 3.491-3.164.368-.281.66-.669 1.035-.955.485-.37.869-.878 1.361-1.253l.3-.328c.364-.278.649-.653 1.005-.926.633-.483 1.142-1.1 1.775-1.582.238-.181.414-.475.651-.657a1.489 1.489 0 0 0 .769-.9 1.113 1.113 0 0 0-1.212.239c-.983.651-2 1.306-2.988 1.94-3.014 1.927-6.036 3.78-9.053 5.7q-1.188.756-2.4 1.522a3.263 3.263 0 0 1-1.183.686 7.453 7.453 0 0 0-1.45-.448c-1.284-.435-2.65-.82-3.934-1.254a7.523 7.523 0 0 1-2.16-.746l-.177-.269c-.023-.978.752-1.183 1.479-1.492 1.3-.556 2.664-1 3.994-1.552 4.253-1.78 8.674-3.3 12.928-5.074 1.457-.609 2.948-1.092 4.379-1.7C24.385 1.382 26.031.834 27.612.18a1.489 1.489 0 0 1 1.834.3 3.149 3.149 0 0 1 .059 2.447c-.095.342-.081.65-.178.985-.209.73-.261 1.51-.473 2.269-.623 2.228-.861 4.636-1.479 6.865-.173.625-.18 1.225-.355 1.85-.622 2.228-.861 4.635-1.479 6.865-.293 1.055-.308 2.765-1.065 3.313-.816.59-1.906-.22-2.426-.6-1.259-.911-2.463-1.935-3.728-2.835-.586-.418-1.139-.873-1.715-1.284a4.613 4.613 0 0 1-.74-.537 3.218 3.218 0 0 0-.68.657c-.333.253-.563.618-.888.865a14.18 14.18 0 0 0-1.39 1.313 2.54 2.54 0 0 1-1.781 1.023z"
                                                                fill-rule="evenodd"
                                                                class="path-iu15mjz5q"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="text text--u-ixefawkr9">
                                                    <span class="text-block-wrap-div">Telegram</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?> -->
                            
                            <div class="div div--u-igqeyih6g">
                                <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;">
                                    <!-- Телефон -->
                                    <div class="div div--u-iz7p7tcug">
                                        <div class="list list--u-i99wkb6ie">
                                                <div class="list__item list__item--u-iqvnaqrvp">
                                                    <a target="_self" href="mailto:rcpk62@mail.ru" class="link-universal link-universal--u-i2gx7d7z2">
                                                        <div class="text text--u-ivur73rjr">
                                                            <span class="text-block-wrap-div">rcpk62@mail.ru</span>
                                                        </div>
                                                    </a>
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="button" class="link-universal link-universal--u-ikofmis2h">
                                    <div class="text text--u-iwuj0ajod">
                                        <span class="text-block-wrap-div">Записаться на курс</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MENU -->
                        <div class="div div--u-iuw2cx10r wow animate__fadeInUp" data-wow-duration="1.5s" data-wow-delay="0s" data-wow-offset="100" data-wow-iteration="1">
                            <?php
                            wp_nav_menu(array(
                                'theme_location'  => 'primary',
                                'container'       => 'div',
                                'container_class' => 'hor-menu hor-menu--u-i3bjdv9ob flex-menu',
                                'menu_class'      => 'hor-menu__list hor-menu__list--u-ij93uzkw5',
                                'walker'          => new Custom_Walker_Nav_Menu(),
                                'fallback_cb'     => false,
                            ));
                            ?>
                        </div>
                    </div>

                    <!-- LOGO и соцсети для внутренних страниц -->
                    <div class="section section--u-i9t9jamjn">
                        <div class="div div--u-ibqzfvbr4">
                            <div class="div div--u-is6pr6s7f">
                                <a href="<?php echo home_url('/'); ?>" class="link-universal link-universal--u-itgxitplz">
                                    <div class="imageFit imageFit--u-ig8fag9qi photo-swipe-image-nc">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.svg" alt="<?php bloginfo('name'); ?>" class="imageFit__img imageFit__img--u-iyv3rdsz1" />
                                    </div>
                                </a>
                                <div class="div div--u-iah4jl8a7">
									<a href="index.html" class="link-universal link-universal--u-iljh3vrqf">
										<div class="text text--u-iedrbdreu">
											<span class="text-block-wrap-div">Региональный центр повышения квалификации</span>
										</div>
									</a>
								</div>
                            </div>
                            <div class="div div--u-ih2638amk">
                                <div class="list list--u-ipyaa0nel">
                                    <?php if (get_theme_mod('mytheme_vk')): ?>
                                        <div class="list__item list__item--u-irihptyui">
                                            <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_vk')); ?>" class="link-universal link-universal--u-i7zkk7cbp">
                                                <span class="svg_image svg_image--u-ieb9j10ao">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="322" height="201" viewBox="0 0 322 201">
                                                        <path
                                                            d="M0 0h54.58v2.185c3.38 5.7 2.06 33.917 4.36 42.6 10.06 37.941 30.08 88.8 70.95 93.946V0h51.3v78.652c33.79-.324 72.45-48 76.41-78.652h52.39c-2.3 38.382-41.67 86.212-69.85 99.408a3.058 3.058 0 0 0 1.09 1.092c7.18 6.791 18.27 10.518 26.19 16.386C288.4 132.41 317.7 169.768 322 201h-56.76c-11.76-29.971-26.81-49.386-54.58-63.359-5-2.516-22.56-11.049-28.38-7.646-2.51 4-1.09 14.747-1.09 20.755V201C59.7 202.2.14 119.781 0 0z"
                                                            fill-rule="evenodd"
                                                            class="path-irkakbs5m"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (get_theme_mod('mytheme_telegram')): ?>
                                        <div class="list__item list__item--u-irihptyui">
                                            <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_telegram')); ?>" class="link-universal link-universal--u-i7zkk7cbp">
                                                <span class="svg_image svg_image--u-ieb9j10ao">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="286.84" height="237.406" viewBox="0 0 286.84 237.406">
                                                        <path
                                                            d="M196 87l-62 59s-18.73 15.886 0 29 78 53 78 53 18.52 11.636 29 9 13.52-11.114 18-37 17.27-99.614 25-153c4.23-36.136 6.52-47.364-14-47-16.02 2.364-75 27-75 27L53 88l-39 17s-14.23 7.136-14 13 8.02 9.636 27 15 36.27 12.136 50 10 47-26 47-26l72-49s15.52-12.614 21-9-4.5 13.25-21 28z"
                                                            fill-rule="evenodd"
                                                            class="path-ieuuuqbq6"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (get_theme_mod('mytheme_ok')): ?>
                                        <div class="list__item list__item--u-irihptyui">
                                            <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_ok')); ?>" class="link-universal link-universal--u-i7zkk7cbp">
                                                <span class="svg_image svg_image--u-ieb9j10ao">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1258.2 2174.7" width="1258.2" height="2174.7">
                                                        <path
                                                            d="M629.9 1122.4c310-.1 561.1-251.5 561-561.4C1190.8 251 939.4-.1 629.5 0S68.4 251.5 68.5 561.4c.4 309.8 251.6 560.8 561.4 561m0-793.4c128.4 0 232.5 104.1 232.5 232.5S758.3 793.9 629.9 793.9 397.4 689.8 397.4 561.4c.2-128.3 104.2-232.3 232.5-232.4zm226.9 1251.3c115.5-26.2 225.7-71.9 326-135 76.4-49.3 98.4-151.1 49.1-227.5-48.5-75.2-148.3-97.9-224.5-51-231.1 144.5-524.5 144.5-755.6 0-76.7-48.1-178-25.1-226.3 51.5C-23 1295-.2 1396.6 76.6 1445.1c.1 0 .2.1.2.1 100.2 63 210.4 108.7 325.8 135L88.8 1894c-62.5 66-59.6 170.2 6.5 232.7 63.5 60 162.7 60 226.2 0l308.2-308.4 308.4 308.4c64.2 64.1 168.1 64.1 232.3 0 64.1-64.2 64.1-168.1 0-232.3l-313.6-314.1z"
                                                            class="path-iepz1qyzg"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (get_theme_mod('mytheme_whatsapp')): ?>
                                        <div class="list__item list__item--u-irihptyui">
                                            <a target="_blank" href="<?php echo esc_url(get_theme_mod('mytheme_whatsapp')); ?>" class="link-universal link-universal--u-i7zkk7cbp">
                                                <span class="svg_image svg_image--u-ieb9j10ao">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="143" height="141.969" viewBox="0 0 143 141.969">
                                                        <path
                                                            d="M5.6 137.612c.33-2.92 1.59-6.038 2.45-8.666 1.18-3.637 1.49-7.027 2.67-10.665.49-1.518 2.34-5.534 1.78-6.888a10.187 10.187 0 0 0-1.56-2.444 67.488 67.488 0 0 1-4.23-7.776 116.022 116.022 0 0 1-5.12-15.331c-.67-2.354-.33-4.347-.89-6.888-.75-3.349-1.01-9.716-.23-13.109l.45-6c1.2-4.928 1.97-9.827 3.79-14.22C13.07 25.4 26.36 12.995 46.59 4.52 58.37-.42 78.31-1.745 91.81 2.743c10.7 3.558 18.72 8.631 26.73 14.664.74.815 1.48 1.63 2.23 2.444a65.1 65.1 0 0 1 6.68 7.333 80.752 80.752 0 0 1 12.47 23.329c.99 2.978.98 5.681 1.79 8.888.9 3.6 1.86 9.773.89 14v3.555c-.51 2.194-.32 4.638-.89 6.888-1 3.921-1.64 7.975-3.12 11.554-8.43 20.366-21.9 34.053-42.33 42.438-12.37 5.078-32.89 5.58-46.33 1.111-3.76-1.249-8.08-2.677-11.36-4.666-1.88-1.135-4.2-3.294-6.69-3.555-1.8 1.31-6.04 1.484-8.46 2.222-3.26.99-6.79 1.55-10.25 2.666a35.808 35.808 0 0 1-7.57 1.998zM40.35 31.405c-.83.538-2 .413-2.9.889-2.22 1.175-5.34 5.361-6.68 7.554-5.5 9-2.03 22.045 2.45 29.329 11.5 18.712 25.97 32.891 48.78 40.439 4.02 1.329 11.33 3.484 16.71 2.222 9.34-2.189 17.51-8.235 16.04-20.442a102.037 102.037 0 0 0-13.59-6.888c-2.59-1.135-4.58-2.986-8.24-3.11a15.631 15.631 0 0 0-1.78 1.333c-1.06 1.422-1.85 2.828-2.9 4.222-.59.79-1.63 1.432-2.23 2.221-.76 1.011-1.68 2.848-2.89 3.333h-2.23c-1.05-.822-2.47-.98-3.79-1.555a51.766 51.766 0 0 1-6.01-3.111 55.523 55.523 0 0 1-16.26-15.109c-1.71-2.381-4.81-4.888-4.9-8.665 1.83-1.81 6.35-7.073 7.13-9.554.86-2.76-1.33-5.282-2.01-6.888-2.44-5.762-3.79-12-8.02-16z"
                                                            fill-rule="evenodd"
                                                            class="path-i4f2cbbx1"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>