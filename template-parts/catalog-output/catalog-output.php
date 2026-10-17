<?php
// Каталог направлений: верхнеуровневые рубрики course_direction (главная и /kursy/).
// Старый тип записи "catalog" здесь больше не выводится.
$directions = get_terms(array(
    'taxonomy'   => 'course_direction',
    'parent'     => 0,
    'hide_empty' => true,
));
?>

<div class="section section--u-i1mm52hsj rcpk-directions">
    <div class="div div--u-ihuo14hdq">
        <div class="heading heading--u-iwz7id95u">
            <span class="text-block-wrap-div">Каталог направлений курсов</span>
        </div>
        <div class="div div--u-ix97q2o2o">
            <div class="blocklist blocklist--u-iv7qintoh">
                <div class="blocklist__items_wrapper blocklist__items_wrapper--u-ingho8az2">
                    <div class="blocklist__list blocklist__list--u-iditg5wka">

                        <?php if ($directions && !is_wp_error($directions)) : ?>
                            <?php foreach ($directions as $direction) :
                                $image_id  = (int) rcpk_direction_meta($direction->term_id, 'image');
                                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : get_template_directory_uri() . '/assets/img/course.webp';
                                $summary   = rcpk_direction_courses_summary($direction->term_id);
                                $link      = get_term_link($direction);
                            ?>
                                <div class="blocklist__item__outer blocklist__item__outer--u-i7u8q7ljz">
                                    <div class="blocklist__item blocklist__item--u-iz82wxbiw">
                                        <div class="imageFit imageFit--u-ividt7p2w photo-swipe-image-nc">
                                            <img src="<?php echo esc_url($image_url); ?>"
                                                alt="<?php echo esc_attr($direction->name); ?>"
                                                title="<?php echo esc_attr($direction->name); ?>"
                                                class="imageFit__img imageFit__img--u-iuyvbsvyh" />
                                            <div class="imageFit__overlay imageFit__overlay--u-ieyv553u6">
                                                <a href="<?php echo esc_url($link); ?>" class="link-universal link-universal--u-i3mwgrwp8">
                                                    <div class="text text--u-ir2e76r7j">
                                                        <span class="text-block-wrap-div">Подробнее</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="blocklist__item_title blocklist__item_title--u-i75ooip4h">
                                            <span class="text-block-wrap-div"><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($direction->name); ?></a></span>
                                        </div>
                                        <div class="blocklist__item_text blocklist__item_text--u-iz7j2hzkl course-card-blocklist__meta">
                                            <?php if ($summary['hours']) : ?>
                                                <span class="text-block-wrap-div">Количество часов: <?php echo esc_html($summary['hours']); ?></span>
                                            <?php endif; ?>
                                            <?php if ($summary['documents']) : ?>
                                                <span class="text-block-wrap-div">Вид выдаваемого документа: <?php echo esc_html($summary['documents']); ?></span>
                                            <?php endif; ?>
                                            <?php if ($summary['validity']) : ?>
                                                <span class="text-block-wrap-div">Срок действия: <?php echo esc_html($summary['validity']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Направления пока не добавлены.</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
