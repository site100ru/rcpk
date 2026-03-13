jQuery(function ($) {
    var CLOSE_DELAY = 220;

    $('.hor-menu__item.has-child').each(function () {
        $(this).data('menuTimer', null);
    });

    function openItem($item) {
        $('.hor-menu__item.has-child').not($item).each(function () {
            var $other = $(this);
            var t = $other.data('menuTimer');
            if (t) { clearTimeout(t); $other.data('menuTimer', null); }
            $other.removeClass('is-opened').children('.hor-menu__sub_list').stop(true, true).fadeOut(120);
        });

        $item.addClass('is-opened').children('.hor-menu__sub_list').stop(true, true).fadeIn(180);
    }

    function scheduleClose($item) {
        var t = $item.data('menuTimer');
        if (t) clearTimeout(t);

        t = setTimeout(function () {
            $item.removeClass('is-opened').children('.hor-menu__sub_list').stop(true, true).fadeOut(120);
            $item.data('menuTimer', null);
        }, CLOSE_DELAY);

        $item.data('menuTimer', t);
    }

    // Навешиваем обработчики на LI и на сам UL (подменю)
    $('.hor-menu__item.has-child').each(function () {
        var $li = $(this);
        var $sub = $li.children('.hor-menu__sub_list');

        // Когда заходим на li — открываем (и отменяем таймер)
        $li.on('mouseenter', function () {
            var t = $li.data('menuTimer');
            if (t) { clearTimeout(t); $li.data('menuTimer', null); }
            openItem($li);
        });

        // Когда уходим с li — ставим таймер на закрытие
        $li.on('mouseleave', function () {
            scheduleClose($li);
        });

        // Если курсор попадает на подменю — отменяем закрытие и держим открытым
        $sub.on('mouseenter', function (e) {
            var t = $li.data('menuTimer');
            if (t) { clearTimeout(t); $li.data('menuTimer', null); }
            // на всякий случай — убедимся, что родитель открыт
            $li.addClass('is-opened').children('.hor-menu__sub_list').stop(true, true).fadeIn(0);
        });

        // Уход с подменю — запланировать закрытие
        $sub.on('mouseleave', function (e) {
            scheduleClose($li);
        });
    });

    // Дополнительно: закрываем все меню при клике вне
    $(document).on('click touchstart', function (e) {
        if ($(e.target).closest('.hor-menu__item.has-child').length === 0) {
            $('.hor-menu__item.has-child').each(function () {
                var $it = $(this);
                var t = $it.data('menuTimer');
                if (t) { clearTimeout(t); $it.data('menuTimer', null); }
                $it.removeClass('is-opened').children('.hor-menu__sub_list').stop(true, true).fadeOut(120);
            });
        }
    });

});


$(document).ready(function () {
    // Инициализация начальных стилей при загрузке
    $('.side-panel__mask').css({
        'display': 'none',
        'opacity': '0',
        'transition': 'opacity 0.3s ease'
    });

    $('.side-panel__content').css({
        'display': 'none',
        'transform': 'translate3d(315px, 0px, 0px)',
        'transition': 'transform 0.3s ease'
    });

    // Клик по кнопке открытия
    $('.side-panel__button-open').on('click', function () {
        var $sidePanel = $(this).closest('.side-panel');
        var $mask = $sidePanel.find('.side-panel__mask');
        var $content = $sidePanel.find('.side-panel__content');

        // Добавляем класс is-open
        $sidePanel.addClass('is-open');

        // Показываем маску с плавным появлением
        $mask.css('display', 'block');
        setTimeout(function () {
            $mask.css('opacity', '1');
        }, 10);

        // Показываем контент с анимацией выезда
        $content.css('display', 'block');
        setTimeout(function () {
            $content.css('transform', 'translate3d(0px, 0px, 0px)');
        }, 10);
    });

    // Клик по кнопке закрытия
    $('.side-panel__button-close').on('click', function () {
        var $sidePanel = $(this).closest('.side-panel');
        var $mask = $sidePanel.find('.side-panel__mask');
        var $content = $sidePanel.find('.side-panel__content');

        // Убираем класс
        $sidePanel.removeClass('is-open');

        // Скрываем контент с анимацией
        $content.css('transform', 'translate3d(315px, 0px, 0px)');
        $mask.css('opacity', '0');

        // Скрываем после анимации (300ms)
        setTimeout(function () {
            $mask.css('display', 'none');
            $content.css('display', 'none');
        }, 300);
    });

    // Клик по оверлею - закрытие
    $('.side-panel__mask--u-ie2ugt4qt').on('click', function () {
        var $sidePanel = $(this).closest('.side-panel');
        var $mask = $(this);
        var $content = $sidePanel.find('.side-panel__content');

        // Убираем класс
        $sidePanel.removeClass('is-open');

        // Скрываем контент с анимацией
        $content.css('transform', 'translate3d(315px, 0px, 0px)');
        $mask.css('opacity', '0');

        // Скрываем после анимации (300ms)
        setTimeout(function () {
            $mask.css('display', 'none');
            $content.css('display', 'none');
        }, 300);
    });
});

$(document).ready(function () {
    // Инициализация
    $('.mosaic-popup__inner-bg--u-iim3w5fa1').css({
        'transition': 'opacity 0.3s ease',
        'opacity': '0',
        'display': 'none'
    });

    // Открытие окон - определяем по тексту кнопки
    $('.link-universal--u-ikofmis2h, [role="button"]').on('click', function () {
        var buttonText = $(this).text().trim();
        var popupId;
        
        // Определяем какое окно открыть по тексту кнопки
        if (buttonText.includes('Записаться') || buttonText.includes('Записатьс')) {
            popupId = 'popup-course';
        } else if (buttonText.includes('Оставить заявку')) {
            popupId = 'popup-course';
        } else if (buttonText.includes('Написать нам')) {
            popupId = 'popup-username';
        } else {
            popupId = 'popup-course'; // по умолчанию
        }
        
        var $popup = $('#' + popupId + ' .mosaic-popup__inner-bg--u-iim3w5fa1');
        $popup.css('display', 'flex');
        setTimeout(function () {
            $popup.css('opacity', '1');
        }, 10);
    });

    // Закрытие по крестику
    $('.mosaic-popup__close--u-iqxwzmwlp').on('click', function () {
        var $popup = $(this).closest('.mosaic-popup__inner-bg--u-iim3w5fa1');
        $popup.css('opacity', '0');
        setTimeout(function () {
            $popup.css('display', 'none');
        }, 300);
    });

    // Закрытие по оверлею
    $('.mosaic-popup__inner-bg--u-iim3w5fa1').on('click', function (e) {
        if ($(e.target).hasClass('mosaic-popup__inner-bg--u-iim3w5fa1')) {
            var $popup = $(this);
            $popup.css('opacity', '0');
            setTimeout(function () {
                $popup.css('display', 'none');
            }, 300);
        }
    });
});

$(document).ready(function () {
    // Кнопка "Наверх" - плавная прокрутка
    $('.button-up--u-igorgqpz7').on('click', function () {
        $('html, body').animate({
            scrollTop: 0
        }, 800); // 800ms - скорость анимации
    });
});

$(document).ready(function() {
    var currentIndex = 0;
    var galleryImages = [];
    
    // Клик на превью или любое изображение с галереей
    $(document).on('click', '[data-gallery-trigger], [data-gallery]', function(e) {
        e.preventDefault();
        
       var $el = $(this).closest('[data-gallery-trigger]').length 
          ? $(this).closest('[data-gallery-trigger]') 
          : $(this).closest('[data-gallery]');
        var galleryName = $el.data('gallery-trigger') || $el.data('gallery');
        var startIndex = parseInt($el.data('index')) || 0;
        
        // Собираем изображения
        galleryImages = [];
        $('[data-gallery="' + galleryName + '"]').each(function() {
            var src = $(this).attr('href');
            if (src) galleryImages.push(src);
        });
        
        if (galleryImages.length === 0) return;
        
        currentIndex = startIndex;
        showModal();
    });
    
    // Показать модалку
    function showModal() {
        updateImage();
        $('#pswp-mosaic').css({'display': 'block', 'opacity': '0'});
        setTimeout(function() {
            $('#pswp-mosaic').css('opacity', '1');
            $('#pswp-mosaic .pswp__bg').css('opacity', '0.7');
        }, 50);
        $('body').css('overflow', 'hidden');
    }
    
    // Обновить изображение
    function updateImage() {
        $('#pswp-mosaic .pswp__img').attr('src', galleryImages[currentIndex]);
        
        // Счетчик
        var $counter = $('#pswp-mosaic .pswp__counter');
        if ($counter.length === 0) {
            $counter = $('<div class="pswp__counter"></div>');
            $('#pswp-mosaic').append($counter);
        }
        $counter.text((currentIndex + 1) + ' / ' + galleryImages.length);
        
        // Стрелки
        if (galleryImages.length > 1) {
            if ($('#pswp-mosaic .pswp__button--arrow--left').length === 0) {
                $('#pswp-mosaic').append('<button class="pswp__button--arrow--left">‹</button>');
                $('#pswp-mosaic').append('<button class="pswp__button--arrow--right">›</button>');
            }
            $('.pswp__button--arrow--left, .pswp__button--arrow--right').show();
        }
    }
    
    // Навигация
    $(document).on('click', '.pswp__button--arrow--left', function(e) {
        e.stopPropagation();
        currentIndex = currentIndex > 0 ? currentIndex - 1 : galleryImages.length - 1;
        updateImage();
    });
    
    $(document).on('click', '.pswp__button--arrow--right', function(e) {
        e.stopPropagation();
        currentIndex = currentIndex < galleryImages.length - 1 ? currentIndex + 1 : 0;
        updateImage();
    });
    
    // Закрытие
    $(document).on('click', '.pswp__button--close, .pswp__bg', function() {
        $('#pswp-mosaic').css('opacity', '0');
        setTimeout(function() {
            $('#pswp-mosaic').css('display', 'none');
            $('body').css('overflow', '');
        }, 300);
    });
    
    // Клавиатура
    $(document).on('keydown', function(e) {
        if (!$('#pswp-mosaic').is(':visible')) return;
        if (e.key === 'Escape') $('.pswp__button--close').click();
        if (e.key === 'ArrowLeft') $('.pswp__button--arrow--left').click();
        if (e.key === 'ArrowRight') $('.pswp__button--arrow--right').click();
    });
});