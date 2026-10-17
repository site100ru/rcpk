<?php
/**
 * Обёртка попапа записи на курс (выводится в footer через rcpk_course_order_button(),
 * чтобы position:fixed не ограничивался родителем карточки).
 */
defined('ABSPATH') || exit;

$course_id = (int) ($args['course_id'] ?? 0);
$popup_id  = 'popup-course-order-' . $course_id;
?>
<div class="mosaic-popup mosaic-popup--u-i6fnbs3ah" id="<?php echo esc_attr($popup_id); ?>">
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
            <?php get_template_part('template-parts/course/order-form', null, array('course_id' => $course_id, 'id_suffix' => $course_id)); ?>
        </div>
    </div>
</div>
