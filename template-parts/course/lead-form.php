<?php
/**
 * Блок заявки "Запишитесь на курс прямо сейчас" (mosaic-form).
 * Извлечён из index.php, используется и на главной, и на странице направления курсов.
 * $args: title (строка, необязательно — переопределить заголовок формы)
 */
defined('ABSPATH') || exit;

$title = $args['title'] ?? 'Запишитесь на курс прямо сейчас';
?>
                        <div class="div div--u-igkajjj2l">
                            <div class="mosaic-form mosaic-form--u-ijpswxniq">
                                <form action="<?php echo get_template_directory_uri(); ?>/assets/mails/course-form.php" class="mosaic-form__form mosaic-form__form--u-ik5179561" method="POST">
                                    <div class="mosaic-form__title mosaic-form__title--u-ixicssomo">
                                        <span class="text-block-wrap-div"><?php echo esc_html($title); ?></span>
                                    </div>
                                    <div class="div div--u-i0ejolayq">
                                        <div class="mosaic-form__field mosaic-form__field--u-ieowq877k">
                                            <!--noindex-->
                                            <div class="mosaic-form__error mosaic-form__error--u-ig4i4wyjd is-removed">
                                                <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                            </div>
                                            <!--/noindex-->
                                            <div class="mosaic-form__header mosaic-form__header--u-iyb99p7le">
                                                <span class="mosaic-form__name mosaic-form__name--u-i38jv27ap"><span class="text-block-wrap-div">Ваш вопрос</span></span><span class="mosaic-form__required mosaic-form__required--u-i5c2808b9 is-removed"><span class="text-block-wrap-div">* </span></span>
                                            </div>
                                            <div class="mosaic-form__body mosaic-form__body--u-i250lhgc3">
                                                <div class="mosaic-form__icon mosaic-form__icon--u-i76ficuys">
                                                    <span class="svg_image svg_image--u-iayw7ng0h"></span>
                                                </div>
                                                <input type="text" name="fio" placeholder="ФИО" class="mosaic-form__text mosaic-form__text--u-idpnhogca" />
                                            </div>
                                            <div class="mosaic-form__note mosaic-form__note--u-idl8l976b is-removed"></div>
                                        </div>
                                        <div class="mosaic-form__field mosaic-form__field--u-iemthdq2t">
                                            <!--noindex-->
                                            <div class="mosaic-form__error mosaic-form__error--u-i6u5gl7uz is-removed">
                                                <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                            </div>
                                            <!--/noindex-->
                                            <div class="mosaic-form__header mosaic-form__header--u-i2r5h3wgg">
                                                <span class="mosaic-form__name mosaic-form__name--u-iirz7s5jo"><span class="text-block-wrap-div">Телефон:</span></span><span class="mosaic-form__required mosaic-form__required--u-idef95otq"><span class="text-block-wrap-div">* </span></span>
                                            </div>
                                            <div class="mosaic-form__body mosaic-form__body--u-ivge1fzf5">
                                                <div class="mosaic-form__icon mosaic-form__icon--u-i1a74hspa">
                                                    <span class="svg_image svg_image--u-ihzs58p8p"></span>
                                                </div>
                                                <input type="tel" name="tel" placeholder="Телефон *" class="mosaic-form__text mosaic-form__text--u-iqc0dx3o9" />
                                            </div>
                                            <div class="mosaic-form__note mosaic-form__note--u-i7rsps8ye is-removed"></div>
                                        </div>
                                        <div class="mosaic-form__field mosaic-form__field--u-if7a3g01p">
                                            <!--noindex-->
                                            <div class="mosaic-form__error mosaic-form__error--u-inr09jmtw is-removed">
                                                <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                            </div>
                                            <!--/noindex-->
                                            <div class="mosaic-form__header mosaic-form__header--u-iujjp290g">
                                                <span class="mosaic-form__name mosaic-form__name--u-ipv2ba8vq"><span class="text-block-wrap-div">E-mail:</span></span><span class="mosaic-form__required mosaic-form__required--u-im2v6z3wf"><span class="text-block-wrap-div">* </span></span>
                                            </div>
                                            <div class="mosaic-form__body mosaic-form__body--u-ihp67drj4">
                                                <div class="mosaic-form__icon mosaic-form__icon--u-i94ij2yh6">
                                                    <span class="svg_image svg_image--u-ivh9mem01"></span>
                                                </div>
                                                <input
                                                    type="email"
                                                    name="email"
                                                    placeholder="E-mail *"
                                                    required
                                                    class="mosaic-form__text mosaic-form__text--u-izmi4wbgt" />
                                            </div>
                                            <div class="mosaic-form__note mosaic-form__note--u-ibhhmln4s is-removed"></div>
                                        </div>

                                        <div class="mosaic-form__field mosaic-form__field--u-i179qeij8">
                                            <!--noindex-->
                                            <div class="mosaic-form__error mosaic-form__error--u-iy4333gan is-removed">
                                                <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                            </div>
                                            <!--/noindex-->
                                            <div class="mosaic-form__header mosaic-form__header--u-inxxb5vss">
                                                <span class="mosaic-form__name mosaic-form__name--u-idvhkkseq"><span class="text-block-wrap-div">Выберите направление курса</span></span><span class="mosaic-form__required mosaic-form__required--u-i9vfjuxw8 is-removed"><span class="text-block-wrap-div">* </span></span>
                                            </div>
                                            <select name="course_direction" class="mosaic-form__select mosaic-form__select--u-ilpvin3m8">
                                                <option value="" class="mosaic-form__select-item mosaic-form__select-item--u-ib0fudd2r">
                                                    Выберите направление
                                                </option>
                                                <?php rcpk_direction_select_options('mosaic-form__select-item mosaic-form__select-item--u-ib0fudd2r'); ?>
                                            </select>
                                            <div class="mosaic-form__note mosaic-form__note--u-iplzu7mlo is-removed"></div>
                                        </div>
                                        <div class="mosaic-form__field mosaic-form__field--u-i1ev5l1de">
                                            <!--noindex-->
                                            <div class="mosaic-form__error mosaic-form__error--u-ixljvij0d is-removed">
                                                <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                            </div>
                                            <!--/noindex-->
                                            <div class="mosaic-form__header mosaic-form__header--u-i8twxx04e">
                                                <span class="mosaic-form__name mosaic-form__name--u-i8htbm2je"><span class="text-block-wrap-div">Формат обучения</span></span><span class="mosaic-form__required mosaic-form__required--u-ifp1y3orq is-removed"><span class="text-block-wrap-div">* </span></span>
                                            </div>
                                            <select name="learning_format" class="mosaic-form__select mosaic-form__select--u-i6enzw69k">
                                                <option class="mosaic-form__select-item mosaic-form__select-item--u-ikai3js6e">Онлайн</option>
                                                <option class="mosaic-form__select-item mosaic-form__select-item--u-ikai3js6e">Очно</option>
                                            </select>
                                            <div class="mosaic-form__note mosaic-form__note--u-iwmmve6vi is-removed"></div>
                                        </div>
                                        <div class="mosaic-form__field mosaic-form__field--u-if7a3g01p">
                                            <!--noindex-->
                                            <div class="mosaic-form__error mosaic-form__error--u-inr09jmtw is-removed">
                                                <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                            </div>
                                            <!--/noindex-->
                                            <div class="mosaic-form__header mosaic-form__header--u-iujjp290g">
                                                <span class="mosaic-form__name mosaic-form__name--u-ipv2ba8vq"><span class="text-block-wrap-div">Количество человек:</span></span><span class="mosaic-form__required mosaic-form__required--u-im2v6z3wf is-removed"><span class="text-block-wrap-div">* </span></span>
                                            </div>
                                            <div class="mosaic-form__body mosaic-form__body--u-ihp67drj4">
                                                <div class="mosaic-form__icon mosaic-form__icon--u-i94ij2yh6">
                                                    <span class="svg_image svg_image--u-ivh9mem01"></span>
                                                </div>
                                                <input
                                                    type="number"
                                                    name="kolvo_user"
                                                    placeholder="Количество человек"
                                                    class="mosaic-form__text mosaic-form__text--u-izmi4wbgt" />
                                            </div>
                                            <div class="mosaic-form__note mosaic-form__note--u-ibhhmln4s is-removed"></div>
                                        </div>
                                    </div>



                                    <div class="mosaic-form__field mosaic-form__field--u-ig753muzh">
                                        <!--noindex-->
                                        <div class="mosaic-form__error mosaic-form__error--u-i98nb76wo is-removed">
                                            <span class="text-block-wrap-div">это поле обязательно для заполнения</span>
                                        </div>
                                        <!--/noindex-->
                                        <div class="mosaic-form__header mosaic-form__header--u-ikkxskmkj">
                                            <span class="mosaic-form__name mosaic-form__name--u-igp98fk7m"><span class="text-block-wrap-div">Галочка</span></span><span class="mosaic-form__required mosaic-form__required--u-i1e97sp2o is-removed"><span class="text-block-wrap-div">* </span></span>
                                        </div>
                                        <label class="mosaic-form__label mosaic-form__label--u-iffag8czd">
                                            <input
                                                type="checkbox"
                                                name="agreement"
                                                value="Ознакомлен с пользовательским соглашением"
                                                class="mosaic-form__checkbox mosaic-form__checkbox--u-ipllxarsi" required checked />
                                            <span class="mosaic-form__checkbox-icon mosaic-form__checkbox-icon--u-imvd5rcjk"></span>
                                            <span class="mosaic-form__value mosaic-form__value--u-iduim6tjb">
                                                <span class="text-block-wrap-div">Ознакомлен с <a href="<?= get_template_directory_uri(); ?>/docs/Privacy-Policy.pdf" target="blank">Политике конфиденциальности.</a></span>
                                            </span></label>
                                        <div class="mosaic-form__note mosaic-form__note--u-ith3092u9 is-removed"></div>
                                    </div>
                                    <button type="submit" class="mosaic-form__button mosaic-form__button--u-ikpkw5uss">
                                        <span class="button__text button__text--u-ipdo2852o"><span class="text-block-wrap-div">Отправить</span></span></button><re-captcha></re-captcha>
                                </form>
                                <!--noindex-->
                                <div class="mosaic-form__success mosaic-form__success--u-ia8cg4jry is-removed">
                                    <div class="mosaic-form__success__text mosaic-form__success__text--u-ia0x22t2h">
                                        <span class="text-block-wrap-div">Спасибо! Форма отправлена</span>
                                    </div>
                                </div>
                                <!--/noindex-->
                            </div>
                        </div>
