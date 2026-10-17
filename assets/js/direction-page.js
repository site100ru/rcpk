/* Страница направления курсов: таймер акции, копирование промокода, кнопка «Вверх». */
(function () {
    'use strict';

    function plural(n, forms) {
        var n10 = n % 10, n100 = n % 100;
        if (n10 === 1 && n100 !== 11) { return forms[0]; }
        if (n10 >= 2 && n10 <= 4 && (n100 < 12 || n100 > 14)) { return forms[1]; }
        return forms[2];
    }

    // Таймер: циклы по period секунд от общей точки anchor — после 0 начинается заново
    document.querySelectorAll('.rcpk-promo__timer').forEach(function (timer) {
        var anchor = parseInt(timer.dataset.anchor, 10);
        var period = parseInt(timer.dataset.period, 10);
        if (!anchor || !period) { return; }

        function tick() {
            var elapsed = (Math.floor(Date.now() / 1000) - anchor) % period;
            if (elapsed < 0) { elapsed += period; }
            var left = period - elapsed;
            var values = {
                days: Math.floor(left / 86400),
                hours: Math.floor(left % 86400 / 3600),
                minutes: Math.floor(left % 3600 / 60),
                seconds: left % 60
            };
            Object.keys(values).forEach(function (key) {
                var num = timer.querySelector('[data-unit="' + key + '"]');
                var label = timer.querySelector('[data-label="' + key + '"]');
                if (num) { num.textContent = (values[key] < 10 ? '0' : '') + values[key]; }
                if (label) { label.textContent = plural(values[key], label.dataset.forms.split('|')); }
            });
        }

        tick();
        setInterval(tick, 1000);
    });

    // Копирование промокода
    document.querySelectorAll('.rcpk-promo__copy').forEach(function (button) {
        button.addEventListener('click', function () {
            var text = button.dataset.copy;
            var done = function () {
                button.classList.add('is-copied');
                button.setAttribute('title', 'Скопировано');
                setTimeout(function () {
                    button.classList.remove('is-copied');
                    button.setAttribute('title', 'Скопировать промокод');
                }, 2000);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(done);
                return;
            }
            var area = document.createElement('textarea');
            area.value = text;
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            try { document.execCommand('copy'); done(); } catch (e) { }
            document.body.removeChild(area);
        });
    });

    // AJAX-фильтры (часы, теги, поиск): страница запрашивается целиком и из неё берутся зоны data-dir-*,
    // так ответ совпадает с обычной загрузкой (и с кэшем страниц). Без JS работают обычные ссылки.
    var zones = ['[data-dir-filters]', '[data-dir-results]', '[data-dir-seo]'];
    var results = document.querySelector('[data-dir-results]');
    if (results && window.fetch && window.DOMParser && window.history.pushState) {
        var basePath = location.pathname;
        var loadedSearch = location.search;
        var controller = null;

        var isFilterUrl = function (url) {
            return url.origin === location.origin && url.pathname === basePath;
        };

        var scrollToCourses = function () {
            var courses = document.getElementById('courses');
            if (!courses) { return; }
            var top = courses.getBoundingClientRect().top;
            if (top < 0 || top > window.innerHeight * 0.5) {
                courses.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        var load = function (href, push) {
            var url = new URL(href, location.href);
            url.hash = '';
            if (controller) { controller.abort(); }
            controller = window.AbortController ? new AbortController() : null;
            results.classList.add('is-loading');

            fetch(url.toString(), { credentials: 'same-origin', signal: controller ? controller.signal : undefined })
                .then(function (response) {
                    if (!response.ok) { throw new Error(response.status); }
                    return response.text();
                })
                .then(function (html) {
                    var doc = new DOMParser().parseFromString(html, 'text/html');
                    zones.forEach(function (selector) {
                        var current = document.querySelector(selector);
                        var fresh = doc.querySelector(selector);
                        if (current && fresh) { current.innerHTML = fresh.innerHTML; }
                    });
                    if (doc.title) { document.title = doc.title; }
                    loadedSearch = url.search;
                    if (push) {
                        history.pushState({ rcpkDir: true }, '', url.pathname + url.search + '#courses');
                    }
                    results.classList.remove('is-loading');
                    scrollToCourses();
                })
                .catch(function (error) {
                    if (error && error.name === 'AbortError') { return; }
                    location.href = url.pathname + url.search + '#courses';
                });
        };

        document.addEventListener('click', function (e) {
            if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) { return; }
            var link = e.target.closest('[data-dir-filters] a[href], [data-dir-results] .rcpk-dir__empty a[href]');
            if (!link) { return; }
            var url = new URL(link.href, location.href);
            if (!isFilterUrl(url)) { return; } // дочерние категории — обычный переход на свою страницу
            e.preventDefault();
            load(url.toString(), true);
        });

        document.addEventListener('submit', function (e) {
            var form = e.target.closest('.rcpk-dir__search');
            if (!form) { return; }
            e.preventDefault();
            var url = new URL(form.getAttribute('action') || location.href, location.href);
            url.search = '';
            new FormData(form).forEach(function (value, key) {
                value = String(value).trim();
                if (value !== '') { url.searchParams.set(key, value); }
            });
            load(url.toString(), true);
        });

        history.replaceState({ rcpkDir: true }, '', location.href);
        window.addEventListener('popstate', function () {
            if (location.pathname === basePath && location.search !== loadedSearch) {
                load(location.href, false);
            }
        });
    }

    // Кнопка «Вверх»: появляется после прокрутки первого экрана
    var toTop = document.querySelector('.rcpk-to-top');
    if (toTop) {
        var toggle = function () {
            toTop.classList.toggle('is-visible', window.scrollY > window.innerHeight * 0.6);
        };
        window.addEventListener('scroll', toggle, { passive: true });
        toggle();
        toTop.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();
