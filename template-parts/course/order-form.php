<?php
/**
 * Форма записи на курс → заказ в GetCourse → переход на оплату.
 */
defined('ABSPATH') || exit;

$course_id = !empty($args['course_id']) ? (int) $args['course_id'] : get_the_ID();
$id_suffix = !empty($args['id_suffix']) ? '-' . (int) $args['id_suffix'] : '';
$status    = rcpk_order_status_message();
?>
<div id="course-order<?php echo esc_attr($id_suffix); ?>" class="mosaic-form mosaic-form--u-iyjjmcepo course-order">
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="mosaic-form__form mosaic-form__form--u-iwsx1397a">
        <div class="mosaic-form__title mosaic-form__title--u-iacq4vc9z">
            <span class="text-block-wrap-div">Записаться на курс</span>
        </div>

        <?php if ($status) : ?>
            <p class="course-order__status"><?php echo esc_html($status); ?></p>
        <?php endif; ?>

        <input type="hidden" name="action" value="rcpk_course_order">
        <input type="hidden" name="course_id" value="<?php echo (int) $course_id; ?>">
        <?php wp_nonce_field('rcpk_course_order_' . $course_id, 'rcpk_order_nonce'); ?>

        <?php foreach (rcpk_utm_keys() as $utm) : ?>
            <?php if (!empty($_GET[$utm])) : ?>
                <input type="hidden" name="<?php echo esc_attr($utm); ?>" value="<?php echo esc_attr(sanitize_text_field(wp_unslash($_GET[$utm]))); ?>">
            <?php endif; ?>
        <?php endforeach; ?>

        <div style="position:absolute;left:-9999px" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="div div--u-i1om4z0p8">
            <div class="mosaic-form__field mosaic-form__field--u-i3bdh0fvm">
                <div class="mosaic-form__header mosaic-form__header--u-i978tvlqr">
                    <span class="mosaic-form__name mosaic-form__name--u-ibutptk30">
                        <span class="text-block-wrap-div">ФИО:</span>
                    </span>
                    <span class="mosaic-form__required mosaic-form__required--u-itjutd3kf">
                        <span class="text-block-wrap-div">* </span>
                    </span>
                </div>
                <div class="mosaic-form__body mosaic-form__body--u-i8vj08i1m">
                    <div class="mosaic-form__icon mosaic-form__icon--u-ijow40kc0">
                        <span class="svg_image svg_image--u-i866kyae0"></span>
                    </div>
                    <input type="text" placeholder="Ваше имя" name="name" required autocomplete="name" class="mosaic-form__text mosaic-form__text--u-i3kvj8jn2" />
                </div>
            </div>

            <div class="mosaic-form__field mosaic-form__field--u-i38c5r4tj">
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
                    <input type="tel" name="phone" placeholder="Телефон *" required autocomplete="tel" class="mosaic-form__text mosaic-form__text--u-it5nzmjjk" />
                </div>
            </div>

            <div class="mosaic-form__field mosaic-form__field--u-i37kfqwac">
                <div class="mosaic-form__header mosaic-form__header--u-imqu3rmw8">
                    <span class="mosaic-form__name mosaic-form__name--u-io7w7mawl">
                        <span class="text-block-wrap-div">E-mail:</span>
                    </span>
                    <span class="mosaic-form__required mosaic-form__required--u-iipk53m0x">
                        <span class="text-block-wrap-div">* </span>
                    </span>
                </div>
                <div class="mosaic-form__body mosaic-form__body--u-ijqm7e5ls">
                    <div class="mosaic-form__icon mosaic-form__icon--u-ifu41tibu">
                        <span class="svg_image svg_image--u-i9lnmh20e"></span>
                    </div>
                    <input type="email" name="email" placeholder="E-mail (для чека и доступа в личный кабинет)" required autocomplete="email" class="mosaic-form__text mosaic-form__text--u-i4zr6bis1" />
                </div>
            </div>
        </div>

        <div class="mosaic-form__field mosaic-form__field--u-ixu80mu3q">
            <label class="mosaic-form__label mosaic-form__label--u-igkj6ded9">
                <input type="checkbox" name="agreement" value="1" required class="mosaic-form__checkbox mosaic-form__checkbox--u-ipkpe12aq" />
                <span class="mosaic-form__checkbox-icon mosaic-form__checkbox-icon--u-i4ba4orh4"></span>
                <span class="mosaic-form__value mosaic-form__value--u-iuv4z9pvs">
                    <span class="text-block-wrap-div">Согласен на <a href="<?php echo esc_url(get_template_directory_uri() . '/docs/Consent-to-the-processing-of-personal-data.pdf'); ?>" target="_blank" rel="noopener">обработку персональных данных</a></span>
                </span>
            </label>
        </div>

        <button type="submit" class="mosaic-form__button mosaic-form__button--u-ihqwpp16f">
            <span class="button__text button__text--u-ixtzflqi8">
                <span class="text-block-wrap-div">Перейти к оплате</span>
            </span>
        </button>
    </form>
</div>
