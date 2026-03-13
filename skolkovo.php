<?php

/**
 * Template Name: Сколково страница
 * Description: Шаблон для Сколково страницы
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="content content--u-iwo7oqyms">
        <?php the_content(); ?>
    </div>
</body>

</html>