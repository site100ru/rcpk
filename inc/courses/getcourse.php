<?php
/**
 * Запись на курс: заявка с сайта → заказ в GetCourse через API → редирект на оплату.
 * Документация API: https://getcourse.ru/help/api
 *
 * Адрес аккаунта и секретный ключ задаются в админке: Внешний вид → Настроить → GetCourse API.
 * Также можно задать через wp-config.php (имеет приоритет над настройками админки):
 *   define('RCPK_GC_ACCOUNT_URL', 'https://ИМЯ_АККАУНТА.getcourse.ru');
 *   define('RCPK_GC_SECRET_KEY', 'секретный ключ API');
 *   define('RCPK_ORDER_NOTIFY_EMAIL', 'info@rcpk62.ru'); // необязательно, по умолчанию — email администратора
 */

defined('ABSPATH') || exit;

function rcpk_gc_account_url()
{
    if (defined('RCPK_GC_ACCOUNT_URL') && RCPK_GC_ACCOUNT_URL) {
        return RCPK_GC_ACCOUNT_URL;
    }
    return get_theme_mod('mytheme_gc_account_url', '');
}

function rcpk_gc_secret_key()
{
    if (defined('RCPK_GC_SECRET_KEY') && RCPK_GC_SECRET_KEY) {
        return RCPK_GC_SECRET_KEY;
    }
    return get_theme_mod('mytheme_gc_secret_key', '');
}

function rcpk_gc_is_configured()
{
    return (bool) rcpk_gc_account_url() && (bool) rcpk_gc_secret_key();
}

/**
 * @return array|WP_Error Результат API (result) с ключом payment_link.
 */
function rcpk_gc_create_deal($user, $deal, $session = array())
{
    if (!rcpk_gc_is_configured()) {
        return new WP_Error('gc_not_configured', 'GetCourse API не настроен');
    }

    $params = array(
        'user'   => $user,
        'system' => array(
            'refresh_if_exists'   => 1,
            'return_payment_link' => 1,
        ),
        'deal'   => $deal,
    );
    if ($session) {
        $params['session'] = $session;
    }

    $response = wp_remote_post(untrailingslashit(rcpk_gc_account_url()) . '/pl/api/deals', array(
        'timeout' => 20,
        'body'    => array(
            'action' => 'add',
            'key'    => rcpk_gc_secret_key(),
            'params' => base64_encode(wp_json_encode($params)),
        ),
    ));

    if (is_wp_error($response)) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code !== 200 || !is_array($body)) {
        return new WP_Error('gc_bad_response', 'GetCourse вернул некорректный ответ (HTTP ' . $code . ')');
    }
    if (!filter_var($body['success'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        return new WP_Error('gc_request_failed', is_string($body['error'] ?? null) ? $body['error'] : 'Запрос отклонён GetCourse');
    }

    $result = isset($body['result']) && is_array($body['result']) ? $body['result'] : array();
    if (filter_var($result['error'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        return new WP_Error('gc_deal_failed', $result['error_message'] ?? 'Ошибка создания заказа');
    }

    $result['payment_link'] = $result['payment_link'] ?? ($result['getcourse_payment_link'] ?? '');

    return $result;
}

function rcpk_utm_keys()
{
    return array('utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term');
}

add_action('admin_post_rcpk_course_order', 'rcpk_handle_course_order');
add_action('admin_post_nopriv_rcpk_course_order', 'rcpk_handle_course_order');
function rcpk_handle_course_order()
{
    $course_id = isset($_POST['course_id']) ? absint($_POST['course_id']) : 0;
    $course    = $course_id ? get_post($course_id) : null;

    if (!$course || $course->post_type !== 'course' || $course->post_status !== 'publish') {
        wp_safe_redirect(home_url('/kursy/'));
        exit;
    }

    $back = get_permalink($course);
    $fail = function ($status) use ($back) {
        wp_safe_redirect(add_query_arg('order', $status, $back) . '#course-order');
        exit;
    };

    if (!isset($_POST['rcpk_order_nonce']) || !wp_verify_nonce($_POST['rcpk_order_nonce'], 'rcpk_course_order_' . $course_id)) {
        $fail('expired');
    }
    // Ловушка для ботов: поле скрыто от людей
    if (!empty($_POST['website'])) {
        $fail('sent');
    }

    $name  = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $phone = preg_replace('/[^\d+]/', '', wp_unslash($_POST['phone'] ?? ''));

    if ($name === '' || !is_email($email) || strlen($phone) < 10 || empty($_POST['agreement'])) {
        $fail('invalid');
    }

    $ip_key = 'rcpk_order_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
    $hits   = (int) get_transient($ip_key);
    if ($hits >= 5) {
        $fail('limit');
    }
    set_transient($ip_key, $hits + 1, 10 * MINUTE_IN_SECONDS);

    $session = array();
    foreach (rcpk_utm_keys() as $utm) {
        if (!empty($_POST[$utm])) {
            $session[$utm] = sanitize_text_field(wp_unslash($_POST[$utm]));
        }
    }

    $offer_id = rcpk_course_meta('gc_offer_id', $course_id);

    $result = new WP_Error('gc_no_offer', 'У курса не указано предложение GetCourse');
    if ($offer_id) {
        $deal = array(
            'quantity'     => 1,
            'deal_comment' => 'Заявка с сайта: ' . $back,
            'offer_id'     => $offer_id,
        );

        $result = rcpk_gc_create_deal(
            array('email' => $email, 'phone' => $phone, 'first_name' => $name),
            $deal,
            $session
        );
    }

    $payment_link = is_wp_error($result) ? '' : $result['payment_link'];
    if ($payment_link && wp_parse_url($payment_link, PHP_URL_SCHEME) === 'https') {
        wp_redirect(esc_url_raw($payment_link));
        exit;
    }

    // Не удалось получить ссылку на оплату — заявка не должна потеряться
    $error = is_wp_error($result) ? $result->get_error_message() : 'GetCourse не вернул ссылку на оплату';
    error_log('[rcpk courses] course ' . $course_id . ': ' . $error);

    $message  = "Курс: " . get_the_title($course) . "\n" . $back . "\n\n";
    $message .= "ФИО: {$name}\nТелефон: {$phone}\nE-mail: {$email}\n";
    $message .= "Стоимость на сайте: " . rcpk_course_meta('price', $course_id) . "\n\n";
    $message .= "Автоматический переход на оплату не сработал: {$error}\nСвяжитесь с клиентом и отправьте ссылку на оплату вручную.";

    $to = defined('RCPK_ORDER_NOTIFY_EMAIL') ? RCPK_ORDER_NOTIFY_EMAIL : get_option('admin_email');
    wp_mail($to, 'Запись на курс с сайта (нужна ссылка на оплату)', $message, array('Reply-To: ' . $email));

    $fail('manual');
}

/**
 * Кнопка "Пройти обучение" + регистрация попапа с формой заказа для вывода
 * в футере страницы: position:fixed внутри карточек/секций иначе ограничивается
 * ближайшим позиционированным/трансформированным предком вместо всего экрана.
 */
function rcpk_course_order_button($course_id, $label = 'Пройти обучение', $classes = 'link-universal link-universal--u-i3mwgrwp8')
{
    $course_id = (int) $course_id;
    $GLOBALS['rcpk_order_popup_ids'][$course_id] = true;
    $popup_id = 'popup-course-order-' . $course_id;
    ob_start();
    ?>
    <a href="#" role="button" data-popup="<?php echo esc_attr($popup_id); ?>" class="<?php echo esc_attr($classes); ?>">
        <div class="text text--u-ir2e76r7j">
            <span class="text-block-wrap-div"><?php echo esc_html($label); ?></span>
        </div>
    </a>
    <?php
    return ob_get_clean();
}

add_action('wp_footer', function () {
    if (empty($GLOBALS['rcpk_order_popup_ids'])) {
        return;
    }
    foreach (array_keys($GLOBALS['rcpk_order_popup_ids']) as $popup_course_id) {
        get_template_part('template-parts/course/order-popup', null, array('course_id' => $popup_course_id));
    }
});

function rcpk_order_status_message()
{
    $status   = isset($_GET['order']) ? sanitize_key($_GET['order']) : '';
    $messages = array(
        'manual'  => 'Спасибо! Заявка принята. Менеджер свяжется с вами и пришлёт ссылку на оплату.',
        'sent'    => 'Спасибо! Заявка принята.',
        'invalid' => 'Проверьте ФИО, телефон, e-mail и согласие на обработку данных.',
        'expired' => 'Форма устарела, обновите страницу и отправьте ещё раз.',
        'limit'   => 'Слишком много заявок подряд. Попробуйте через 10 минут или позвоните нам.',
    );
    return $messages[$status] ?? '';
}
