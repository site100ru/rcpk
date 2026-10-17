<?php
/**
 * Акция на страницах направлений: % скидки и промокод (Внешний вид → Настроить → «Акция (промокод)»),
 * таймер обратного отсчёта — бесконечный цикл по 3 дня (после 0 начинается заново).
 */

defined('ABSPATH') || exit;

const RCPK_PROMO_PERIOD = 3 * DAY_IN_SECONDS;

add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('rcpk_promo', array(
        'title'       => 'Акция (промокод)',
        'description' => 'Баннер со скидкой на страницах направлений курсов. Если промокод пустой — баннер не выводится.',
        'priority'    => 231,
    ));

    $wp_customize->add_setting('rcpk_promo_percent', array(
        'default'           => '20',
        'transport'         => 'refresh',
        'sanitize_callback' => function ($value) {
            $value = absint($value);
            return $value > 0 && $value < 100 ? (string) $value : '';
        },
    ));
    $wp_customize->add_control('rcpk_promo_percent', array(
        'label'       => 'Скидка, %',
        'description' => 'Оставьте пустым — в тексте будет «Получите скидку на любой курс» без процентов.',
        'section'     => 'rcpk_promo',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 99, 'step' => 1),
    ));

    $wp_customize->add_setting('rcpk_promo_code', array(
        'default'           => 'УЧИТЕЛЬ20',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('rcpk_promo_code', array(
        'label'   => 'Промокод (кодовое слово)',
        'section' => 'rcpk_promo',
        'type'    => 'text',
    ));
});

add_action('wp_enqueue_scripts', function () {
    if (!is_tax('course_direction')) {
        return;
    }
    $path = get_template_directory() . '/assets/js/direction-page.js';
    wp_enqueue_script('rcpk-direction-page', get_template_directory_uri() . '/assets/js/direction-page.js', array(), filemtime($path), true);
});

function rcpk_promo()
{
    return array(
        'percent' => (string) get_theme_mod('rcpk_promo_percent', '20'),
        'code'    => trim((string) get_theme_mod('rcpk_promo_code', 'УЧИТЕЛЬ20')),
    );
}

// Точка отсчёта циклов — полночь по Москве, чтобы у всех посетителей таймер показывал одно и то же
function rcpk_promo_anchor()
{
    return (new DateTimeImmutable('2026-01-01 00:00:00', new DateTimeZone('Europe/Moscow')))->getTimestamp();
}

function rcpk_promo_seconds_left($now = null)
{
    $elapsed = (($now ?? time()) - rcpk_promo_anchor()) % RCPK_PROMO_PERIOD;
    if ($elapsed < 0) {
        $elapsed += RCPK_PROMO_PERIOD;
    }
    return RCPK_PROMO_PERIOD - $elapsed;
}

// 1 день / 2 дня / 5 дней
function rcpk_plural($n, $one, $few, $many)
{
    $n10  = $n % 10;
    $n100 = $n % 100;
    if ($n10 === 1 && $n100 !== 11) {
        return $one;
    }
    if ($n10 >= 2 && $n10 <= 4 && ($n100 < 12 || $n100 > 14)) {
        return $few;
    }
    return $many;
}
