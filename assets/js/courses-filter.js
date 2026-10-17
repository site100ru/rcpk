(function () {
    var form = document.querySelector('.courses-filter');
    var results = document.getElementById('courses-results');
    if (!form || !results || !window.rcpkCourses || !window.fetch) {
        return;
    }

    var base = form.getAttribute('action');
    var isSearch = /\/kursy\/poisk\/?$/.test(new URL(base, location.href).pathname);
    var timer;

    function load(paged, push) {
        var data = new FormData(form);
        var query = new URLSearchParams();
        data.forEach(function (value, key) {
            if (value !== '') {
                query.append(key, value);
            }
        });

        if (isSearch && !query.get('q')) {
            return;
        }

        var url = base.replace(/\/?$/, '/') + (paged > 1 ? 'page/' + paged + '/' : '');
        var qs = query.toString();
        if (qs) {
            url += '?' + qs;
        }

        data.append('action', 'rcpk_courses');
        data.append('term', form.dataset.term || '0');
        data.append('paged', paged);
        data.append('base', base);

        results.setAttribute('aria-busy', 'true');
        fetch(rcpkCourses.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (json) {
                if (!json.success) {
                    throw new Error('bad response');
                }
                results.innerHTML = json.data.html;
                if (push) {
                    history.pushState({ rcpkCourses: true }, '', url);
                }
            })
            .catch(function () {
                location.href = url;
            })
            .finally(function () {
                results.removeAttribute('aria-busy');
            });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        load(1, true);
    });

    form.addEventListener('change', function (e) {
        if (e.target.name !== 'q') {
            load(1, true);
        }
    });

    form.addEventListener('input', function (e) {
        if (e.target.name === 'q') {
            clearTimeout(timer);
            timer = setTimeout(function () { load(1, true); }, 400);
        }
    });

    results.addEventListener('click', function (e) {
        var link = e.target.closest('.courses-results__pagination a');
        if (!link) {
            return;
        }
        e.preventDefault();
        var match = link.getAttribute('href').match(/\/page\/(\d+)\//);
        load(match ? parseInt(match[1], 10) : 1, true);
        results.scrollIntoView({ behavior: 'smooth' });
    });

    window.addEventListener('popstate', function () {
        location.reload();
    });
})();
