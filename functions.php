<?php

/**
 * Functions and definitions
 */

/**
 * Получить полный номер телефона
 */
function mytheme_get_phone($type = 'main')
{
    if ($type === 'main') {
        $country = get_theme_mod('mytheme_main_phone_country_code', '+7');
        $region = get_theme_mod('mytheme_main_phone_region_code', '');
        $number = get_theme_mod('mytheme_main_phone_number', '');
    } else {
        $country = get_theme_mod('additional_phone_country_code', '+7');
        $region = get_theme_mod('additional_phone_region_code', '');
        $number = get_theme_mod('additional_phone_number', '');
    }

    if (empty($number)) {
        return '';
    }

    $phone = $country;
    if (!empty($region)) {
        $phone .= ' (' . $region . ') ';
    } else {
        $phone .= ' ';
    }
    $phone .= $number;

    return $phone;
}

/**
 * Получить телефон в формате для tel:
 */
function mytheme_get_phone_link($type = 'main')
{
    if ($type === 'main') {
        $country = get_theme_mod('mytheme_main_phone_country_code', '+7');
        $region = get_theme_mod('mytheme_main_phone_region_code', '');
        $number = get_theme_mod('mytheme_main_phone_number', '');
    } else {
        $country = get_theme_mod('additional_phone_country_code', '+7');
        $region = get_theme_mod('additional_phone_region_code', '');
        $number = get_theme_mod('additional_phone_number', '');
    }

    if (empty($number)) {
        return '';
    }

    // Удаляем все символы кроме цифр и +
    $clean = preg_replace('/[^0-9+]/', '', $country . $region . $number);

    return $clean;
}

// Подключение стилей и скриптов
function mytheme_enqueue_scripts()
{
    // Общие стили для всех страниц
    wp_enqueue_style('mytheme-fonts', get_template_directory_uri() . '/assets/css/fonts.css');
    wp_enqueue_style('mytheme-root', get_template_directory_uri() . '/assets/css/root.css');
    wp_enqueue_style('mytheme-animate', get_template_directory_uri() . '/assets/css/animate.min.css');

    // Стили для главной страницы
    if (is_front_page()) {
        wp_enqueue_style('mytheme-style', get_template_directory_uri() . '/assets/css/style.css');
        wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/css/slick/slick.css');
        wp_enqueue_style('slick-theme', get_template_directory_uri() . '/assets/css/slick/slick-theme.css');
    } else {
        // Стили для внутренних страниц
        wp_enqueue_style('mytheme-o-kurse', get_template_directory_uri() . '/assets/css/o-kurse.css');
        wp_enqueue_style('mytheme-archive-kurse', get_template_directory_uri() . '/assets/css/archive-kurse.css');
    }

    // Отключаем стандартный jQuery и подключаем свой
    wp_deregister_script('jquery');
    wp_register_script('jquery', get_template_directory_uri() . '/assets/js/jquery.min.js', array(), null, true);
    wp_enqueue_script('jquery');

    // Общие скрипты (зависят от jQuery)
    wp_enqueue_script('wow-js', get_template_directory_uri() . '/assets/js/wow.min.js', array('jquery'), null, true);
    wp_enqueue_script('mytheme-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), null, true);
    wp_enqueue_script('mytheme-animations', get_template_directory_uri() . '/assets/js/animations.js', array('jquery', 'wow-js'), null, true);

    // Скрипты для главной страницы
    if (is_front_page()) {
        wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/js/slick/slick.min.js', array('jquery'), null, true);
        wp_enqueue_script('mytheme-do-slick', get_template_directory_uri() . '/assets/js/do.slick.js', array('jquery', 'slick-js'), null, true);
    }

    // Инлайн скрипт для категорий (для страниц с категориями)
    if (is_page_template('page-catalog.php') || is_page_template('page-article.php') || is_page_template('page-requisites.php') || is_archive() || is_single()) {
        $categories_script = "
        jQuery(document).ready(function($) {
            $(document).ready(function () {
				// Обработчик для кнопки
				$(document).on('click', '.g-categories__button', function (e) {
					e.preventDefault();
					e.stopPropagation();
					$(this).parent().toggleClass('g-categories--opened');
				});

				// Закрытие меню при клике вне элемента
				$(document).on('click', function (e) {
					if (!$(e.target).closest('.g-categories').length) {
						$('.g-categories').removeClass('g-categories--opened');
					}
				});
			});
        });
        ";
        wp_add_inline_script('mytheme-main', $categories_script);
    }
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_scripts');

// Добавление поддержки меню
function mytheme_setup()
{
    register_nav_menus(array(
        'primary' => 'Основное меню',
    ));

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'mytheme_setup');

// Регистрируем кастомный тип записи и таксономию
function mytheme_register_post_types()
{
    // Тип записи "Новости"
    register_post_type('novosti', array(
        'labels' => array(
            'name'               => 'Новости',
            'singular_name'      => 'Новость',
            'add_new'            => 'Добавить новость',
            'add_new_item'       => 'Добавить новую новость',
            'edit_item'          => 'Редактировать новость',
            'new_item'           => 'Новая новость',
            'view_item'          => 'Посмотреть новость',
            'search_items'       => 'Найти новость',
            'not_found'          => 'Новость не найдена',
            'not_found_in_trash' => 'В корзине новость не найдена',
            'menu_name'          => 'Новости',
        ),
        'public'              => true,
        'has_archive'         => 'o-kurse/archive-kurse',
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-media-document',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
        'show_in_nav_menus'   => true,
        'taxonomies'          => array('category_novosti'),
    ));

    // Таксономия (категории) для советов
    register_taxonomy('category_novosti', 'novosti', array(
        'labels' => array(
            'name'              => 'Категории новостей',
            'singular_name'     => 'Категория',
            'search_items'      => 'Найти категорию',
            'all_items'         => 'Все категории',
            'edit_item'         => 'Редактировать категорию',
            'update_item'       => 'Обновить категорию',
            'add_new_item'      => 'Добавить категорию',
            'new_item_name'     => 'Название новой категории',
            'menu_name'         => 'Категории',
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'show_in_nav_menus' => true,
        'rewrite'           => array(
            'slug'         => 'o-kurse/archive-kurse',
            'with_front'   => false,
            'hierarchical' => true,
        ),
    ));

    // Тип записи "Каталог курсов"
    register_post_type('catalog', array(
        'labels' => array(
            'name'               => 'Каталог курсов',
            'singular_name'      => 'Курс',
            'add_new'            => 'Добавить курс',
            'add_new_item'       => 'Добавить новый курс',
            'edit_item'          => 'Редактировать курс',
            'new_item'           => 'Новый курс',
            'view_item'          => 'Посмотреть курс',
            'search_items'       => 'Найти курс',
            'not_found'          => 'Курсов не найдено',
            'not_found_in_trash' => 'В корзине курсов не найдено',
            'menu_name'          => 'Каталог курсов',
        ),
        'public'              => true,
        'has_archive'         => false,
        'rewrite'             => array(
            'slug'       => 'catalog',
            'with_front' => false,
        ),
        'menu_icon'           => 'dashicons-book',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
        'show_in_nav_menus' => true,
    ));

    // Тип записи "Отзывы"
    register_post_type('reviews', array(
        'labels' => array(
            'name'               => 'Отзывы',
            'singular_name'      => 'Отзыв',
            'add_new'            => 'Добавить отзыв',
            'add_new_item'       => 'Добавить новый отзыв',
            'edit_item'          => 'Редактировать отзыв',
            'new_item'           => 'Новый отзыв',
            'view_item'          => 'Посмотреть отзыв',
            'search_items'       => 'Найти отзыв',
            'not_found'          => 'Отзывы не найдены',
            'not_found_in_trash' => 'В корзине отзывов не найдено',
            'menu_name'          => 'Отзывы',
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array(
            'slug'       => 'reviews',
            'with_front' => false,
        ),
        'menu_icon'           => 'dashicons-testimonial',
        'supports'            => array('title', 'editor', 'thumbnail'),
        'show_in_rest'        => false,
        'show_in_nav_menus' => true,
    ));
}
add_action('init', 'mytheme_register_post_types');

// Добавление мета-бокса для внешней ссылки
add_action('add_meta_boxes', 'catalog_add_external_link_metabox');
function catalog_add_external_link_metabox() {
    add_meta_box(
        'catalog_external_link',
        'Внешняя ссылка',
        'catalog_external_link_callback',
        'catalog',
        'side',
        'default'
    );
}

// Callback для вывода поля
function catalog_external_link_callback($post) {
    wp_nonce_field('catalog_save_external_link', 'catalog_external_link_nonce');
    $value = get_post_meta($post->ID, 'external_link', true);
    ?>
    <p>
        <label for="catalog_external_link">URL:</label><br>
        <input type="url" 
               id="catalog_external_link" 
               name="catalog_external_link" 
               value="<?php echo esc_attr($value); ?>" 
               style="width: 100%;" 
               placeholder="https://example.com">
    </p>
    <p class="description">
        Если указана, кнопка "Подробнее" будет вести на этот адрес.<br>
        Если не указана - на страницу статьи.
    </p>
    <?php
}

// Сохранение мета-поля
add_action('save_post_catalog', 'catalog_save_external_link');
function catalog_save_external_link($post_id) {
    // Проверка nonce
    if (!isset($_POST['catalog_external_link_nonce']) || 
        !wp_verify_nonce($_POST['catalog_external_link_nonce'], 'catalog_save_external_link')) {
        return;
    }

    // Проверка автосохранения
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Проверка прав
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Сохранение или удаление значения
    if (isset($_POST['catalog_external_link'])) {
        $external_link = esc_url_raw($_POST['catalog_external_link']);
        if (!empty($external_link)) {
            update_post_meta($post_id, 'external_link', $external_link);
        } else {
            delete_post_meta($post_id, 'external_link');
        }
    }
}

// Фильтр для сохранения HTML в отрывке (excerpt)
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'catalog_custom_excerpt', 10, 1);
function catalog_custom_excerpt($text) {
    global $post;
    
    if ($post->post_type === 'catalog' && has_excerpt()) {
        // Возвращаем отрывок с сохранением HTML
        $excerpt = $post->post_excerpt;
        // Разрешаем определенные HTML-теги
        $allowed_tags = '<a><br><strong><em><u><p><span><b><i>';
        $excerpt = strip_tags($excerpt, $allowed_tags);
        // Конвертируем переносы строк в <br>
        $excerpt = nl2br($excerpt);
        return $excerpt;
    }
    
    return wp_trim_excerpt($text);
}

// Добавляем кастомное правило rewrite для постов с категорией
function mytheme_add_rewrite_rules()
{
    add_rewrite_rule(
        '^o-kurse/archive-kurse/([^/]+)/([^/]+)/?$',
        'index.php?novosti=$matches[2]&category_novosti=$matches[1]',
        'top'
    );
}
add_action('init', 'mytheme_add_rewrite_rules');

// Фильтр для корректной обработки URL с категорией и записью
function mytheme_post_type_permalink($url, $post)
{
    if ($post->post_type === 'novosti') {
        $categories = get_the_terms($post->ID, 'category_novosti');
        if ($categories && !is_wp_error($categories)) {
            $category = array_shift($categories);
            $url = home_url('/o-kurse/archive-kurse/' . $category->slug . '/' . $post->post_name . '/');
        }
    }
    return $url;
}
add_filter('post_type_link', 'mytheme_post_type_permalink', 10, 2);

// Сбрасываем rewrite rules при активации темы (запустите один раз)
function mytheme_flush_rewrite_rules()
{
    mytheme_register_post_types();
    mytheme_add_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'mytheme_flush_rewrite_rules');

// Вывод кода счетчиков в head
function mytheme_analytics_head()
{
    $counter_code = get_theme_mod('mytheme_counter_head', '');
    if (!empty($counter_code)) {
        echo $counter_code;
    }
}
add_action('wp_head', 'mytheme_analytics_head');

// Вывод кода счетчиков перед </body>
function mytheme_analytics_body()
{
    $counter_code = get_theme_mod('mytheme_counter_body', '');
    if (!empty($counter_code)) {
        echo $counter_code;
    }
}
add_action('wp_footer', 'mytheme_analytics_body');

/*** Отзывы ***/
// Добавление мета-боксов для дополнительных полей
function reviews_add_meta_boxes()
{
    add_meta_box(
        'reviews_details',
        'Информация об авторе отзыва',
        'reviews_meta_box_callback',
        'reviews',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'reviews_add_meta_boxes');

// Вывод полей в мета-боксе
function reviews_meta_box_callback($post)
{
    wp_nonce_field('reviews_save_meta', 'reviews_nonce');

    $author_name = get_post_meta($post->ID, '_review_author_name', true);
    $author_position = get_post_meta($post->ID, '_review_author_position', true);
?>
    <p>
        <label><strong>Имя автора отзыва:</strong></label><br>
        <input type="text" name="review_author_name" value="<?php echo esc_attr($author_name); ?>" style="width: 100%;">
    </p>
    <p>
        <label><strong>Должность:</strong></label><br>
        <input type="text" name="review_author_position" value="<?php echo esc_attr($author_position); ?>" style="width: 100%;">
    </p>
<?php
}

// Сохранение данных мета-полей
function reviews_save_meta_box_data($post_id)
{
    if (!isset($_POST['reviews_nonce']) || !wp_verify_nonce($_POST['reviews_nonce'], 'reviews_save_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['review_author_name'])) {
        update_post_meta($post_id, '_review_author_name', sanitize_text_field($_POST['review_author_name']));
    }
    if (isset($_POST['review_author_position'])) {
        update_post_meta($post_id, '_review_author_position', sanitize_text_field($_POST['review_author_position']));
    }
}
add_action('save_post', 'reviews_save_meta_box_data');

/*** НАСТРОЙКИ ТЕМЫ В CUSTOMIZER ***/
function mytheme_customize_register($wp_customize)
{

    // СЕКЦИЯ: Аналитика и счетчики
    $wp_customize->add_section('mytheme_analytics', array(
        'title'      => 'Аналитика и счетчики',
        'priority'   => 200,
        'capability' => 'edit_theme_options',
    ));

    // Код счетчика (head)
    $wp_customize->add_setting('mytheme_counter_head', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('mytheme_counter_head', array(
        'label'       => 'Код счетчика (в <head>)',
        'description' => 'Вставьте код, который должен быть в <head> (например, Google Analytics, Meta Pixel)',
        'section'     => 'mytheme_analytics',
        'type'        => 'textarea',
    ));

    // Код счетчика (body)
    $wp_customize->add_setting('mytheme_counter_body', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
        'capability'        => 'edit_theme_options',
    ));
    $wp_customize->add_control('mytheme_counter_body', array(
        'label'       => 'Код счетчика (перед </body>)',
        'description' => 'Вставьте код, который должен быть перед закрывающим тегом </body> (например, Яндекс.Метрика)',
        'section'     => 'mytheme_analytics',
        'type'        => 'textarea',
    ));

    // ПАНЕЛЬ: Контакты
    $wp_customize->add_panel('contact_panel', array(
        'title'       => 'Контакты',
        'description' => 'Настройки контактной информации',
        'priority'    => 300,
        'capability'  => 'edit_theme_options',
    ));

    // СЕКЦИЯ: Основной номер телефона
    $wp_customize->add_section('mytheme_contacts', array(
        'title'      => 'Основной номер телефона',
        'panel'      => 'contact_panel',
        'priority'   => 5,
        'capability' => 'edit_theme_options',
    ));

    // Код страны основного телефона
    $wp_customize->add_setting('mytheme_main_phone_country_code', array(
        'default'           => '+7',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_main_phone_country_code', array(
        'label'       => 'Код страны',
        'description' => 'Например: 8 или +7',
        'section'     => 'mytheme_contacts',
        'type'        => 'text',
    ));

    // Код региона основного телефона
    $wp_customize->add_setting('mytheme_main_phone_region_code', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_main_phone_region_code', array(
        'label'       => 'Код региона',
        'description' => 'Например: 800, без скобок',
        'section'     => 'mytheme_contacts',
        'type'        => 'text',
    ));

    // Основной номер телефона
    $wp_customize->add_setting('mytheme_main_phone_number', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_main_phone_number', array(
        'label'       => 'Номер телефона',
        'description' => 'Например: 880-80-88',
        'section'     => 'mytheme_contacts',
        'type'        => 'text',
    ));

    // Рабочее время телефона
    $wp_customize->add_setting('mytheme_phone_work_time', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_phone_work_time', array(
        'label'       => 'Рабочее время телефона',
        'description' => 'Например: с 9:00 до 18:00',
        'section'     => 'mytheme_contacts',
        'type'        => 'text',
    ));

    // СЕКЦИЯ: Дополнительный номер телефона
    $wp_customize->add_section('additional_phone_number', array(
        'title'    => 'Дополнительный номер телефона',
        'panel'    => 'contact_panel',
        'priority' => 10
    ));

    // Код страны дополнительного телефона
    $wp_customize->add_setting('additional_phone_country_code', array(
        'default'           => '+7',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('additional_phone_country_code', array(
        'label'       => 'Код страны',
        'description' => 'Например: 8 или +7',
        'section'     => 'additional_phone_number',
        'type'        => 'text',
    ));

    // Код региона дополнительного телефона
    $wp_customize->add_setting('additional_phone_region_code', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('additional_phone_region_code', array(
        'label'       => 'Код региона',
        'description' => 'Например: 800, без скобок',
        'section'     => 'additional_phone_number',
        'type'        => 'text',
    ));

    // Дополнительный номер телефона
    $wp_customize->add_setting('additional_phone_number', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('additional_phone_number', array(
        'label'       => 'Номер телефона',
        'description' => 'Например: 880-80-88',
        'section'     => 'additional_phone_number',
        'type'        => 'text',
    ));

    // СЕКЦИЯ: Email
    $wp_customize->add_section('mytheme_contacts_email', array(
        'title'    => 'Email',
        'panel'    => 'contact_panel',
        'priority' => 15
    ));

    $wp_customize->add_setting('mytheme_email', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('mytheme_email', array(
        'label'   => 'Email',
        'section' => 'mytheme_contacts_email',
        'type'    => 'email',
    ));

    // СЕКЦИЯ: Telegram
    $wp_customize->add_section('mytheme_contacts_telegram', array(
        'title'    => 'Telegram',
        'panel'    => 'contact_panel',
        'priority' => 20
    ));

    $wp_customize->add_setting('mytheme_telegram', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('mytheme_telegram', array(
        'label'       => 'Telegram',
        'description' => 'Укажите ссылку на Telegram',
        'section'     => 'mytheme_contacts_telegram',
        'type'        => 'url',
    ));

    // СЕКЦИЯ: WhatsApp
    $wp_customize->add_section('mytheme_contacts_whatsapp', array(
        'title'    => 'WhatsApp',
        'panel'    => 'contact_panel',
        'priority' => 25
    ));

    $wp_customize->add_setting('mytheme_whatsapp', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('mytheme_whatsapp', array(
        'label'       => 'WhatsApp',
        'description' => 'Укажите ссылку на WhatsApp',
        'section'     => 'mytheme_contacts_whatsapp',
        'type'        => 'url',
    ));

    // СЕКЦИЯ: Вконтакте
    $wp_customize->add_section('mytheme_contacts_vk', array(
        'title'    => 'Вконтакте',
        'panel'    => 'contact_panel',
        'priority' => 30
    ));

    $wp_customize->add_setting('mytheme_vk', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('mytheme_vk', array(
        'label'       => 'Вконтакте',
        'description' => 'Укажите ссылку на Вконтакте',
        'section'     => 'mytheme_contacts_vk',
        'type'        => 'url',
    ));

    // СЕКЦИЯ: Одноклассники
    $wp_customize->add_section('mytheme_contacts_ok', array(
        'title'    => 'Одноклассники',
        'panel'    => 'contact_panel',
        'priority' => 35
    ));

    $wp_customize->add_setting('mytheme_ok', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('mytheme_ok', array(
        'label'       => 'Одноклассники',
        'description' => 'Укажите ссылку на Одноклассники',
        'section'     => 'mytheme_contacts_ok',
        'type'        => 'url',
    ));

    // СЕКЦИЯ: Рутуб
    $wp_customize->add_section('mytheme_contacts_rutube', array(
        'title'    => 'Рутуб',
        'panel'    => 'contact_panel',
        'priority' => 40
    ));

    $wp_customize->add_setting('mytheme_rutube', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('mytheme_rutube', array(
        'label'       => 'Рутуб',
        'description' => 'Укажите ссылку на Рутуб',
        'section'     => 'mytheme_contacts_rutube',
        'type'        => 'url',
    ));

    // СЕКЦИЯ: Адрес
    $wp_customize->add_section('mytheme_contacts_address', array(
        'title'    => 'Адрес',
        'panel'    => 'contact_panel',
        'priority' => 45
    ));

    $wp_customize->add_setting('mytheme_address', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_address', array(
        'label'       => 'Адрес',
        'description' => 'Укажите адрес организации',
        'section'     => 'mytheme_contacts_address',
        'type'        => 'text',
    ));

    // СЕКЦИЯ: Время работы
    $wp_customize->add_section('mytheme_contacts_job_time', array(
        'title'    => 'Время работы',
        'panel'    => 'contact_panel',
        'priority' => 50
    ));

    $wp_customize->add_setting('mytheme_job_time', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_job_time', array(
        'label'       => 'Рабочее время',
        'description' => 'Например: Пн-Пт: 9:00-18:00',
        'section'     => 'mytheme_contacts_job_time',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('mytheme_weekend', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_weekend', array(
        'label'       => 'Выходные дни',
        'description' => 'Например: Сб, Вс - выходные',
        'section'     => 'mytheme_contacts_job_time',
        'type'        => 'text',
    ));

    // СЕКЦИЯ: Реквизиты
    $wp_customize->add_section('mytheme_contacts_requisites', array(
        'title'    => 'Реквизиты',
        'panel'    => 'contact_panel',
        'priority' => 55
    ));

    $wp_customize->add_setting('mytheme_company_name', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_company_name', array(
        'label'       => 'Название компании',
        'description' => 'Например: ООО Компания «Название»',
        'section'     => 'mytheme_contacts_requisites',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('mytheme_legal_address', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_legal_address', array(
        'label'       => 'Юридический адрес',
        'description' => 'Полный юридический адрес организации',
        'section'     => 'mytheme_contacts_requisites',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('mytheme_inn', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_inn', array(
        'label'   => 'ИНН',
        'section' => 'mytheme_contacts_requisites',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('mytheme_ogrn', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_ogrn', array(
        'label'   => 'ОГРН',
        'section' => 'mytheme_contacts_requisites',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('mytheme_kpp', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_kpp', array(
        'label'   => 'КПП',
        'section' => 'mytheme_contacts_requisites',
        'type'    => 'text',
    ));

    // СЕКЦИЯ: Координаты для карт
    $wp_customize->add_section('mytheme_contacts_coordinates', array(
        'title'    => 'Координаты для карт',
        'panel'    => 'contact_panel',
        'priority' => 60
    ));

    $wp_customize->add_setting('mytheme_latitude', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_latitude', array(
        'label'       => 'Широта',
        'description' => 'Например: 55.751244',
        'section'     => 'mytheme_contacts_coordinates',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('mytheme_longitude', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('mytheme_longitude', array(
        'label'       => 'Долгота',
        'description' => 'Например: 37.618423',
        'section'     => 'mytheme_contacts_coordinates',
        'type'        => 'text',
    ));

    // СЕКЦИЯ: Изображения документов
    $wp_customize->add_section('document_images_section', array(
        'title'    => 'Изображения документов',
        'priority' => 200
    ));

    // Настройка для хранения ID изображений (через запятую)
    $wp_customize->add_setting('document_image_ids', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('document_image_ids', array(
        'label'       => 'ID изображений документов (через запятую)',
        'description' => 'Например: 123,456,789. Чтобы узнать ID - откройте изображение в Медиабиблиотеке и посмотрите в адресной строке (post=123)',
        'section'     => 'document_images_section',
        'type'        => 'text',
    ));
	
	// СЕКЦИЯ: Лицензии и аккредитации
	$wp_customize->add_section('mytheme_licenses', array(
		'title'    => 'Галерея документов',
		'priority' => 210,
	));

	$wp_customize->add_setting('mytheme_licenses_json', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control(new Mytheme_License_Repeater_Control(
		$wp_customize,
		'mytheme_licenses_json',
		array(
			'label'       => 'Галерея документов',
			'description' => 'Добавьте документы: название, картинка-превью и PDF (необязательно).',
			'section'     => 'mytheme_licenses',
		)
	));
}
add_action('customize_register', 'mytheme_customize_register');

// Кастомный контрол для повторителя документ
if (class_exists('WP_Customize_Control')) {

    class Mytheme_License_Repeater_Control extends WP_Customize_Control
    {
        public $type = 'license_repeater';

        public function enqueue()
        {
            wp_enqueue_media();
        }

        public function render_content()
        {
            $values = json_decode($this->value(), true);
            if (!is_array($values)) {
                $values = [];
            }
            $control_id = esc_js($this->id);
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
            </label>

            <div class="license-repeater-list" id="license-repeater-list-<?php echo $control_id; ?>">
                <?php foreach ($values as $index => $item) : ?>
                    <div class="license-repeater-item" style="margin-bottom: 15px; padding: 12px; border: 1px solid #ddd; border-radius: 4px; background: #fafafa;">
                        <p style="margin: 0 0 8px;"><strong>документ <?php echo $index + 1; ?></strong></p>
                        <input type="text"
                            placeholder="Название (напр: Свидетельство СРО)"
                            value="<?php echo esc_attr($item['title'] ?? ''); ?>"
                            class="license-title widefat"
                            style="margin-bottom: 8px;">

                        <p style="margin: 0 0 4px; font-size: 12px; color: #555;">Картинка-превью:</p>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <div class="license-img-preview" style="width: 60px; height: 60px; border: 1px solid #ddd; background: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                <?php if (!empty($item['img_url'])) : ?>
                                    <img src="<?php echo esc_attr($item['img_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else : ?>
                                    <span style="font-size: 10px; color: #999;">нет</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <input type="hidden" class="license-img-url" value="<?php echo esc_attr($item['img_url'] ?? ''); ?>">
                                <button type="button" class="button license-upload-img" style="display: block; margin-bottom: 4px;">Выбрать картинку</button>
                                <button type="button" class="button license-remove-img" style="display: block; color: #a00; <?php echo empty($item['img_url']) ? 'display:none!important;' : ''; ?>">Удалить</button>
                            </div>
                        </div>

                        <p style="margin: 0 0 4px; font-size: 12px; color: #555;">PDF файл (необязательно):</p>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <input type="hidden" class="license-pdf-url" value="<?php echo esc_attr($item['pdf_url'] ?? ''); ?>">
                            <span class="license-pdf-name" style="font-size: 12px; color: #333; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo !empty($item['pdf_url']) ? basename($item['pdf_url']) : '—'; ?>
                            </span>
                            <button type="button" class="button license-upload-pdf">Выбрать PDF</button>
                            <button type="button" class="button license-remove-pdf" style="color: #a00; <?php echo empty($item['pdf_url']) ? 'display:none;' : ''; ?>">✕</button>
                        </div>

                        <button type="button" class="button license-remove-item" style="color: #a00;">Удалить документ</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="button button-primary license-add-item" style="margin-top: 10px;">+ Добавить документ</button>
            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" class="license-repeater-value">

            <script type="text/javascript">
                (function($) {
                    var listId = 'license-repeater-list-<?php echo $control_id; ?>';
                    var $control = $('#customize-control-<?php echo $control_id; ?>');
                    var $list = $('#' + listId);

                    function updateValue() {
                        var licenses = [];
                        $list.find('.license-repeater-item').each(function() {
                            licenses.push({
                                title:   $(this).find('.license-title').val(),
                                img_url: $(this).find('.license-img-url').val(),
                                pdf_url: $(this).find('.license-pdf-url').val(),
                            });
                        });
                        $control.find('.license-repeater-value').val(JSON.stringify(licenses)).trigger('change');
                    }

                    function openMediaUploader(options, callback) {
                        var frame = wp.media({
                            title:   options.title  || 'Выбрать файл',
                            button:  { text: options.button || 'Выбрать' },
                            library: { type: options.type || 'image' },
                            multiple: false,
                        });
                        frame.on('select', function() {
                            callback(frame.state().get('selection').first().toJSON());
                        });
                        frame.open();
                    }

                    $list.on('click', '.license-upload-img', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        openMediaUploader({ title: 'Выбрать картинку', button: 'Использовать', type: 'image' }, function(att) {
                            $item.find('.license-img-url').val(att.url);
                            $item.find('.license-img-preview').html('<img src="' + att.url + '" style="width:100%;height:100%;object-fit:cover;">');
                            $item.find('.license-remove-img').show();
                            updateValue();
                        });
                    });

                    $list.on('click', '.license-remove-img', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        $item.find('.license-img-url').val('');
                        $item.find('.license-img-preview').html('<span style="font-size:10px;color:#999;">нет</span>');
                        $(this).hide();
                        updateValue();
                    });

                    $list.on('click', '.license-upload-pdf', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        openMediaUploader({ title: 'Выбрать PDF', button: 'Использовать', type: 'application/pdf' }, function(att) {
                            $item.find('.license-pdf-url').val(att.url);
                            $item.find('.license-pdf-name').text(att.filename || att.url.split('/').pop());
                            $item.find('.license-remove-pdf').show();
                            updateValue();
                        });
                    });

                    $list.on('click', '.license-remove-pdf', function() {
                        var $item = $(this).closest('.license-repeater-item');
                        $item.find('.license-pdf-url').val('');
                        $item.find('.license-pdf-name').text('—');
                        $(this).hide();
                        updateValue();
                    });

                    $control.on('click', '.license-add-item', function() {
                        var count = $list.find('.license-repeater-item').length + 1;
                        var $item = $('<div class="license-repeater-item" style="margin-bottom:15px;padding:12px;border:1px solid #ddd;border-radius:4px;background:#fafafa;">' +
                            '<p style="margin:0 0 8px;"><strong>документ ' + count + '</strong></p>' +
                            '<input type="text" placeholder="Название (напр: Свидетельство СРО)" class="license-title widefat" style="margin-bottom:8px;">' +
                            '<p style="margin:0 0 4px;font-size:12px;color:#555;">Картинка-превью:</p>' +
                            '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">' +
                                '<div class="license-img-preview" style="width:60px;height:60px;border:1px solid #ddd;background:#eee;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;"><span style="font-size:10px;color:#999;">нет</span></div>' +
                                '<div><input type="hidden" class="license-img-url" value="">' +
                                '<button type="button" class="button license-upload-img" style="display:block;margin-bottom:4px;">Выбрать картинку</button>' +
                                '<button type="button" class="button license-remove-img" style="display:none;color:#a00;">Удалить</button></div>' +
                            '</div>' +
                            '<p style="margin:0 0 4px;font-size:12px;color:#555;">PDF файл (необязательно):</p>' +
                            '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">' +
                                '<input type="hidden" class="license-pdf-url" value="">' +
                                '<span class="license-pdf-name" style="font-size:12px;color:#333;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">—</span>' +
                                '<button type="button" class="button license-upload-pdf">Выбрать PDF</button>' +
                                '<button type="button" class="button license-remove-pdf" style="display:none;color:#a00;">✕</button>' +
                            '</div>' +
                            '<button type="button" class="button license-remove-item" style="color:#a00;">Удалить документ</button>' +
                        '</div>');
                        $list.append($item);
                    });

                    $list.on('click', '.license-remove-item', function() {
                        $(this).closest('.license-repeater-item').remove();
                        updateValue();
                    });

                    $list.on('input', '.license-title', function() {
                        updateValue();
                    });

                })(jQuery);
            </script>
            <?php
        }
    }
}

/**
 * Получить документы из повторителя
 */
function mytheme_get_licenses()
{
    $json = get_theme_mod('mytheme_licenses_json', '');
    $data = json_decode($json, true);
    return is_array($data)
        ? array_filter($data, fn($item) => !empty($item['title']) || !empty($item['img_url']))
        : [];
}

/**
 * Регистрация кастомного блока для изображения с подписью
 */

function register_image_caption_block()
{
    // Регистрируем блок
    register_block_type('mytheme/image-caption', array(
        'render_callback' => 'render_image_caption_block',
        'attributes' => array(
            'imageUrl' => array(
                'type' => 'string',
                'default' => ''
            ),
            'imageId' => array(
                'type' => 'number',
                'default' => 0
            ),
            'caption' => array(
                'type' => 'string',
                'default' => 'Краткое описание'
            ),
            'alignment' => array(
                'type' => 'string',
                'default' => 'right'
            )
        )
    ));
}
add_action('init', 'register_image_caption_block');

// Функция рендеринга блока
function render_image_caption_block($attributes)
{
    $image_url = !empty($attributes['imageUrl']) ? esc_url($attributes['imageUrl']) : '';
    $caption = !empty($attributes['caption']) ? esc_html($attributes['caption']) : 'Краткое описание';
    $alignment = !empty($attributes['alignment']) ? esc_attr($attributes['alignment']) : 'right';

    if (empty($image_url)) {
        return '';
    }

    ob_start();
?>
    <div class="lpc-content-wrapper">
        <div class="decor-wrap">
            <div class="lpc-elements-text-3 lpc-block" style="max-width: 1309px">
                <div class="lpc-elements-text-3__wrap lpc-wrap">
                    <div class="lpc-elements-text-3__inner">
                        <div class="lpc-elements-text-3__row lpc-row js-lg-init">
                            <div class="lpc-col-4-xl lpc-col-4-lg lpc-col-6-md lpc-col-6-sm lpc-col-12-xs lpc-elements-text-3__photos _<?php echo $alignment; ?>">
                                <div class="lpc-elements-text-3__photo-wrap">
                                    <a href="<?php echo $image_url; ?>" target="_blank" class="lpc-image-type-1 lg-item lpc-elements-text-3__photo lp-selected-element">
                                        <img src="<?php echo $image_url; ?>" alt="<?php echo $caption; ?>" class="">
                                    </a>
                                </div>
                                <div class="lpc-elements-text-3__img-title lp-header-title-6"><?php echo $caption; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}

// Подключаем JavaScript для редактора
function enqueue_image_caption_block_editor_assets()
{
    wp_enqueue_script(
        'image-caption-block-editor',
        get_template_directory_uri() . '/js/image-caption-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/js/image-caption-block.js')
    );
}
add_action('enqueue_block_editor_assets', 'enqueue_image_caption_block_editor_assets');

/**
 * Оборачиваем все теги <strong> в <span style="font-weight: bold">
 */
function wrap_strong_tags_in_span($content)
{
    // Заменяем <strong> на <span style="font-weight: bold"><strong>
    $content = preg_replace('/<strong>/i', '<span style="font-weight: bold"><strong>', $content);

    // Заменяем </strong> на </strong></span>
    $content = preg_replace('/<\/strong>/i', '</strong></span>', $content);

    return $content;
}
add_filter('the_content', 'wrap_strong_tags_in_span', 20);


/***
 * Перевод языка при сохранении
 */
require_once get_template_directory() . '/inc/transliteration.php';


// Кастомный Walker для меню
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu
{

    // Начало элемента меню
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // Классы для li
        if ($depth == 0) {
            $li_class = 'hor-menu__item hor-menu__item--u-iggcx09k2';
            if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
                $li_class .= ' is-current';
            }
            if (in_array('menu-item-has-children', $classes)) {
                $li_class .= ' has-child';
            }
        } else {
            $li_class = 'hor-menu__sub_item hor-menu__sub_item--u-i39wcd8zh';
        }

        $output .= '<li class="' . esc_attr($li_class) . '">';

        // Ссылка
        $link_class = ($depth == 0) ? 'hor-menu__link hor-menu__link--u-ix575hv02' : 'hor-menu__sub_link hor-menu__sub_link--u-i2g1xria8';
        $text_class = ($depth == 0) ? 'hor-menu__text hor-menu__text--u-ihigo1c04' : 'hor-menu__sub_text hor-menu__sub_text--u-i4ranael9';

        $output .= '<a href="' . esc_url($item->url) . '" class="' . $link_class . '">';
        $output .= '<span class="' . $text_class . '">';
        $output .= '<span class="text-block-wrap-div">' . esc_html($item->title) . '</span>';
        $output .= '</span>';

        // Иконка для пунктов с подменю
        if ($depth == 0 && in_array('menu-item-has-children', $classes)) {
            $output .= '<span class="hor-menu__icon hor-menu__icon--u-i9c9ipswi"></span>';
        }

        $output .= '</a>';
    }

    // Начало подменю
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '<ul class="hor-menu__sub_list hor-menu__sub_list--u-isqrmm3bo">';
    }

    // Конец подменю
    function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '</ul>';
    }
}

// Кастомный Walker для мобильного меню
class Custom_Walker_Mobile_Menu extends Walker_Nav_Menu {
    
    // Начало элемента меню
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        // Классы для li
        if ($depth == 0) {
            $li_class = 'ver-menu__item ver-menu__item--u-iqzz9fwq5';
            if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
                $li_class .= ' is-current';
            }
        } else {
            $li_class = 'ver-menu__sub_item ver-menu__sub_item--u-iyx2p1dql';
        }
        
        $output .= '<li class="' . esc_attr($li_class) . '">';
        
        // Ссылка
        $link_class = ($depth == 0) ? 'ver-menu__link ver-menu__link--u-iuy5nlram' : 'ver-menu__sub_link ver-menu__sub_link--u-if7yalyxx';
        $text_class = ($depth == 0) ? 'ver-menu__text ver-menu__text--u-ios9ejiha' : 'ver-menu__sub_text ver-menu__sub_text--u-if56rjs3u';
        
        $output .= '<a href="' . esc_url($item->url) . '" class="' . $link_class . '">';
        $output .= '<span class="' . $text_class . '">';
        $output .= '<span class="text-block-wrap-div">' . esc_html($item->title) . '</span>';
        $output .= '</span>';
        
        // Иконка для пунктов с подменю
        if ($depth == 0 && in_array('menu-item-has-children', $classes)) {
            $output .= '<span class="ver-menu__icon ver-menu__icon--u-iwh6la5fa"></span>';
        }
        
        $output .= '</a>';
    }
    
    // Начало подменю
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="ver-menu__sub_list ver-menu__sub_list--u-i2jwjmlfb">';
    }
    
    // Конец подменю
    function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }
}