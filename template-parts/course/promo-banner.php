<?php
/**
 * Баннер акции с промокодом и таймером (страница направления).
 * Настройки: Внешний вид → Настроить → «Акция (промокод)», логика таймера — inc/courses/promo.php.
 */
defined('ABSPATH') || exit;

$promo = rcpk_promo();
if ($promo['code'] === '') {
    return;
}

$left  = rcpk_promo_seconds_left();
$units = array(
    'days'    => array((int) floor($left / DAY_IN_SECONDS), array('день', 'дня', 'дней')),
    'hours'   => array((int) floor($left % DAY_IN_SECONDS / HOUR_IN_SECONDS), array('час', 'часа', 'часов')),
    'minutes' => array((int) floor($left % HOUR_IN_SECONDS / MINUTE_IN_SECONDS), array('минута', 'минуты', 'минут')),
    'seconds' => array($left % MINUTE_IN_SECONDS, array('секунда', 'секунды', 'секунд')),
);
?>
<section class="rcpk-promo">
    <div class="rcpk-promo__content">
        <h2 class="rcpk-promo__title">
            Получите скидку<?php if ($promo['percent'] !== '') : ?> <span><?php echo esc_html($promo['percent']); ?>%</span><?php endif; ?><br class="rcpk-promo__br"> на любой курс
        </h2>
        <p class="rcpk-promo__subtitle">Используйте промокод при оплате обучения</p>

        <div class="rcpk-promo__code">
            <div class="rcpk-promo__code-inner">
                <span class="rcpk-promo__code-label">Промокод:</span>
                <span class="rcpk-promo__code-value"><?php echo esc_html($promo['code']); ?></span>
                <button type="button" class="rcpk-promo__copy" data-copy="<?php echo esc_attr($promo['code']); ?>" aria-label="Скопировать промокод" title="Скопировать промокод">
                    <svg width="28" height="34" viewBox="0 0 28 34" fill="none" aria-hidden="true"><rect x="1" y="7" width="20" height="26" rx="3" stroke="currentColor" stroke-width="2"/><path d="M7 7V4a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v20a3 3 0 0 1-3 3h-3" stroke="currentColor" stroke-width="2"/></svg>
                </button>
            </div>
        </div>

        <p class="rcpk-promo__note">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="10" stroke="currentColor" stroke-width="1.2"/><rect x="10.3" y="9" width="1.4" height="7" rx="0.7" fill="currentColor"/><circle cx="11" cy="6.5" r="0.9" fill="currentColor"/></svg>
            <span>Промокод действует в течение ограниченного времени.<br>До конца действия скидки осталось:</span>
        </p>

        <div class="rcpk-promo__timer" data-anchor="<?php echo esc_attr(rcpk_promo_anchor()); ?>" data-period="<?php echo esc_attr(RCPK_PROMO_PERIOD); ?>">
            <?php $i = 0; foreach ($units as $key => $unit) : ?>
                <?php if ($i++) : ?><span class="rcpk-promo__colon" aria-hidden="true"></span><?php endif; ?>
                <div class="rcpk-promo__unit">
                    <b data-unit="<?php echo esc_attr($key); ?>"><?php echo esc_html(sprintf('%02d', $unit[0])); ?></b>
                    <span data-label="<?php echo esc_attr($key); ?>" data-forms="<?php echo esc_attr(implode('|', $unit[1])); ?>"><?php echo esc_html(rcpk_plural($unit[0], ...$unit[1])); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <svg class="rcpk-promo__decor" viewBox="0 0 800 420" fill="none" aria-hidden="true">
        <g stroke="#fff" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round">
            <path d="M3 203H172V358H140V393L110 358H3Z" fill="#eed5b7"/>
            <path d="M100 203V63L137 3L172 63V203Z" fill="#eed5b7"/>
            <path d="M100 63H172M120 63V203M150 63V203M124 36H150"/>
            <rect x="172" y="341" width="273" height="90" rx="14" fill="#eed5b7"/>
            <ellipse cx="292" cy="220" rx="150" ry="72" transform="rotate(45 292 220)"/>
            <ellipse cx="292" cy="220" rx="150" ry="72" transform="rotate(-45 292 220)"/>
            <circle cx="292" cy="220" r="45"/>
            <circle cx="342" cy="173" r="12" fill="#eed5b7"/>
            <path d="M503 0V15M503 45L382 252M503 45L582 162M466 104Q503 122 541 104"/>
            <circle cx="503" cy="30" r="15" fill="#eed5b7"/>
            <path d="M507 263V364a14 14 0 0 0 14 14H639a14 14 0 0 0 14-14V263" fill="#eed5b7"/>
            <path d="M445 233L580 161L715 233L580 301Z" fill="#eed5b7"/>
            <path d="M715 233V323"/>
            <circle cx="750" cy="390" r="66" fill="#eed5b7"/>
            <ellipse cx="750" cy="390" rx="28" ry="66" transform="rotate(-30 750 390)"/>
            <path d="M694 355L806 425M690 410L780 335"/>
        </g>
    </svg>
</section>
