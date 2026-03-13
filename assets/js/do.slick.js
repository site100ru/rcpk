$(document).ready(function () {
    var isAnimating = false;

    var $slider = $('.blocklist__list--u-i2gsh9bze').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: true,
        arrows: true,
        prevArrow: $('.blocklist--u-i5kkm2vwu .blocklist__arrow--prev'),
        nextArrow: $('.blocklist--u-i5kkm2vwu .blocklist__arrow--next'),
        appendDots: $('.blocklist--u-i5kkm2vwu .blocklist__pagination'),
        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 600,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ],
        customPaging: function (slider, i) {
            return '<div class="blocklist__pagination_item blocklist__pagination_item--u-i532xvp3u"></div>';
        }
    });

    function updateActiveClasses(currentSlide, totalSlides) {
        $('.blocklist--u-i5kkm2vwu .blocklist__pagination_item').removeClass('is-active');
        $('.blocklist--u-i5kkm2vwu .blocklist__item').removeClass('is-current');

        $('.blocklist--u-i5kkm2vwu .blocklist__pagination_item').eq(currentSlide).addClass('is-active');

        var $currentSlide = $($slider.slick('getSlick').$slides.get(currentSlide));
        $currentSlide.find('.blocklist__item').addClass('is-current');

        var current = currentSlide + 1;
        $('.blocklist--u-i5kkm2vwu .blocklist__page--amount span').text(totalSlides);

        $('.blocklist--u-i5kkm2vwu .blocklist__page--active').empty();
        for (var i = 0; i < current; i++) {
            $('.blocklist--u-i5kkm2vwu .blocklist__page--active').append('<div class="blocklist__page--bullet1"></div>');
        }
    }

    $slider.on('afterChange', function (event, slick, currentSlide) {
        isAnimating = false;
        updateActiveClasses(currentSlide, slick.slideCount);
    });

    $(document).on('click', '.blocklist--u-i5kkm2vwu .blocklist__pagination_item', function () {
        if (isAnimating) return;

        var $clickedItem = $(this);
        var index = $clickedItem.parent('li').index();

        $('.blocklist--u-i5kkm2vwu .blocklist__pagination_item').removeClass('is-active');

        $clickedItem.addClass('is-active');

        setTimeout(function () {
            isAnimating = true;
            $slider.slick('slickGoTo', index);
        }, 50); 
    });

    setTimeout(function () {
        var slick = $slider.slick('getSlick');
        updateActiveClasses(slick.currentSlide, slick.slideCount);
    }, 100);
});