/**
 * Выгрузка курсов из GetCourse в Excel для импорта на сайт.
 *
 * Как запустить:
 *   1. Открыть в Chrome https://centr.rcpk62.ru/ (лучше залогиниться администратором —
 *      тогда скрипт дополнительно сверит цену и актуальность по разделу «Предложения»).
 *   2. F12 → вкладка Console → вставить весь этот файл → Enter.
 *      Если Chrome пишет «Type "allow pasting"…» — напечатать вручную allow pasting, Enter,
 *      и вставить файл ещё раз (Chrome спрашивает это один раз).
 *   3. Дождаться «готово» в консоли и нажать зелёную кнопку «Скачать Excel» вверху страницы.
 *   4. На сайте: Курсы → Импорт из GetCourse → загрузить этот файл.
 *
 * Откуда данные: скрипт сам берёт список страниц сайта («Сайт → Страницы», /pl/cms/page),
 * открывает каждую и ищет карточки курсов (блок «tsr051»). Направление — страница с карточками
 * и заголовком «Программы обучения <Направление>»; название берётся из этого заголовка,
 * фон — из первого блока страницы. Новое направление на GetCourse подхватится само.
 * Из карточки берутся часы, название, документ, срок действия, требования, стоимость,
 * картинка и offer_id из кнопки «Пройти обучение». Образец документа — фон шаблона
 * «Обучение → Дипломы» с тем же названием и часами (на сайте на него накладывается «ОБРАЗЕЦ»).
 */
console.log('[GC export] запуск…');
(async () => {
    // Черновые страницы (по названию в «Сайт → Страницы»): направлениями не считаются,
    // но offer_id с их карточек используются, если на основной странице кнопка без offer_id
    const SKIP_PAGES = /тест|test|копи|шаблон/i;
    const HERO_MARK = /Программы обучения/i;
    const CHECK_OFFERS = true; // сверка с «Продажи → Предложения»

    const log = (...a) => console.log('%c[GC export]', 'color:#199c68;font-weight:bold', ...a);
    const LABELS = [
        ['document', /Вид(?: выдаваемого документа)?\s*:/i],
        ['hours', /Количество часов\s*:?/i],
        ['validity', /Срок действия\s*:?/i],
        ['requirements', /Требования к образованию\s*:?/i],
        ['price', /Стоимость\s*:?/i],
    ];

    // xlsx-js-style — SheetJS с поддержкой цвета ячеек (у обычного SheetJS стили не записываются)
    const loadXlsx = () => window.XLSX?.style_version ? Promise.resolve() : new Promise((resolve, reject) => {
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js';
        s.onload = resolve;
        s.onerror = () => reject(new Error('Не удалось загрузить библиотеку Excel (SheetJS)'));
        document.head.appendChild(s);
    });

    const fetchDoc = async (url) => {
        const res = await fetch(url, { credentials: 'include' });
        if (!res.ok) throw new Error(`${url}: HTTP ${res.status}`);
        return new DOMParser().parseFromString(await res.text(), 'text/html');
    };

    // Текст с переносами строк вместо <p>/<br> и обычными пробелами вместо &nbsp;
    const plain = (el) => {
        if (!el) return '';
        const html = el.innerHTML.replace(/<br\s*\/?>/gi, '\n').replace(/<\/(p|div|li)>/gi, '\n');
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent.replace(/ /g, ' ').replace(/[ \t]+/g, ' ')
            .split('\n').map((l) => l.trim()).filter(Boolean).join('\n');
    };

    const absUrl = (u) => (u ? (u.startsWith('//') ? 'https:' + u : u) : '');

    // Разбор описания карточки: значение — всё от подписи до следующей подписи
    const parseDescription = (text) => {
        const flat = text.replace(/\n/g, ' ');
        const marks = [];
        LABELS.forEach(([key, re]) => {
            const m = re.exec(flat);
            if (m) marks.push({ key, start: m.index, end: m.index + m[0].length });
        });
        marks.sort((a, b) => a.start - b.start);
        const out = { extra: marks.length ? flat.slice(0, marks[0].start).trim() : flat.trim() };
        marks.forEach((m, i) => {
            out[m.key] = flat.slice(m.end, i + 1 < marks.length ? marks[i + 1].start : undefined).trim();
        });
        return out;
    };

    const parseCard = (card) => {
        const desc = parseDescription(plain(card.querySelector('.description')));
        const hoursText = plain(card.querySelector('.subheader'));
        const offer = /"offer_id"\s*:\s*"?(\d+)/.exec(card.innerHTML);
        return {
            offer_id: offer ? offer[1] : '',
            title: plain(card.querySelector('.header')).replace(/\n/g, ' '),
            // Часы из подзаголовка «254 часа», иначе из описания «Количество часов: 254»
            hours: (hoursText.match(/\d+/) || (desc.hours || '').match(/\d+/) || [''])[0],
            hours_text: hoursText.replace(/\n/g, ' '),
            document: desc.document || '',
            validity: desc.validity || '',
            requirements: desc.requirements || '',
            price: ((desc.price || '').replace(/\s/g, '').match(/\d+/) || [''])[0],
            extra: desc.extra || '',
            image: absUrl(card.querySelector('.image')?.getAttribute('data-bg')),
        };
    };

    // Параллельная обработка списка не более чем в limit потоков
    const pool = async (items, limit, fn) => {
        const queue = [...items];
        const out = [];
        await Promise.all(Array.from({ length: limit }, async () => {
            while (queue.length) out.push(await fn(queue.shift()));
        }));
        return out;
    };

    // Таблица админки GetCourse (Yii GridView) по всем страницам пагинации: строки tr[data-key]
    const loadGrid = async (url, label) => {
        let first = await fetchDoc(`${url}?page=1`);
        let perPage = first.querySelectorAll('tr[data-key]').length;
        if (!perPage) {
            // GetCourse иногда отдаёт пустую страницу-заглушку — одна повторная попытка через 3 с
            await new Promise((r) => setTimeout(r, 3000));
            first = await fetchDoc(`${url}?page=1`);
            perPage = first.querySelectorAll('tr[data-key]').length;
        }
        if (!perPage) return null;
        const total = parseInt((first.querySelector('.summary')?.textContent.match(/из\s*([\d\s]+)/) || ['', '0'])[1].replace(/\s/g, ''), 10);
        const pages = Math.max(1, Math.ceil(total / perPage));
        let done = 1;
        const docs = [first, ...await pool(Array.from({ length: pages - 1 }, (_, i) => i + 2), 8, async (p) => {
            const doc = await fetchDoc(`${url}?page=${p}`);
            if (++done % 20 === 0 || done === pages) log(`${label}: загружено ${done} из ${pages} страниц`);
            return doc;
        })];
        return docs.flatMap((doc) => [...doc.querySelectorAll('tr[data-key]')]);
    };

    // Список предложений: offer_id → {title, price, actual}
    const loadOffers = async () => {
        const rows = await loadGrid('/pl/sales/offer/index', 'Предложения');
        if (!rows) return null;
        const map = {};
        rows.forEach((tr) => {
            const td = tr.querySelectorAll('td');
            map[tr.dataset.key] = {
                title: td[1]?.textContent.trim() || '',
                price: (td[5]?.textContent.replace(/\s/g, '').match(/\d+/) || [''])[0],
                actual: /Актуален/.test(tr.textContent) && !/Неактуален/.test(tr.textContent),
            };
        });
        return map;
    };

    // Шаблоны документов «Обучение → Дипломы»: [{id, title, issued}]; фон шаблона — образец документа
    const loadDiplomas = async () => {
        const rows = await loadGrid('/pl/teach/control/diploma/index', 'Шаблоны документов');
        if (!rows) return null;
        return rows.map((tr) => {
            const td = tr.querySelectorAll('td');
            return {
                id: tr.dataset.key,
                title: td[1]?.textContent.trim().replace(/\s+/g, ' ') || '',
                issued: parseInt(td[2]?.textContent.replace(/\D/g, '') || '0', 10),
            };
        }).filter((t) => !/^\s*тест/i.test(t.title));
    };

    // Картинка шаблона документа (миниатюра GetCourse, 800 px по ширине: вдвое легче 1200 px,
    // а импорт на сайте почти всё время тратит на скачивание бланков)
    const diplomaImage = async (id) => {
        const doc = await fetchDoc(`/pl/teach/control/diploma/update-template?id=${id}`);
        const src = doc.querySelector('.image-field img')?.getAttribute('src') || '';
        return absUrl(src.replace(/\/s\/\d*x\d*\//, '/s/800x/'));
    };

    // Страницы сайта: [{title, slug}] из «Сайт → Страницы»
    const loadSitePages = async () => {
        const rows = await loadGrid('/pl/cms/page/index', 'Список страниц сайта');
        if (!rows) throw new Error('Нет доступа к «Сайт → Страницы». Войдите на GetCourse администратором и запустите скрипт ещё раз.');
        return rows.map((tr) => {
            const td = tr.querySelectorAll('td');
            return { title: td[1]?.textContent.trim() || '', slug: td[3]?.textContent.trim() || '' };
        }).filter((p) => p.slug && !p.slug.includes('/'));
    };

    // Название направления из первого блока: «Программы обучения <Направление>»
    const heroTitle = (doc) => {
        const bld = doc.querySelector('.bld01')?.cloneNode(true);
        if (!bld) return '';
        bld.querySelectorAll('style, script').forEach((el) => el.remove());
        const text = bld.textContent.replace(/\s+/g, ' ').trim();
        if (!HERO_MARK.test(text)) return '';
        return text.replace(HERO_MARK, ' ').replace(/\s+/g, ' ').trim();
    };

    log('шаг 1/6: загружаем библиотеку Excel…');
    await loadXlsx();

    const startedAt = Date.now();
    const cardsAll = [];
    const spareOffers = {}; // offer_id с черновых страниц: «название|часы» → offer_id
    const directions = new Map();

    // Страницы сайта и список предложений грузим параллельно
    const offersPromise = !CHECK_OFFERS ? Promise.resolve(null) : loadOffers().catch((e) => {
        console.warn('[GC export] сверка не удалась:', e.message);
        return null;
    });
    const diplomasPromise = loadDiplomas().catch((e) => {
        console.warn('[GC export] шаблоны документов не загружены:', e.message);
        return null;
    });

    log('шаг 2/6: получаем список страниц сайта (параллельно в фоне грузятся «Предложения» и шаблоны документов)…');
    const sitePages = await loadSitePages();
    log(`шаг 3/6: страниц на сайте ${sitePages.length}, ищем на них карточки курсов…`);
    let pagesDone = 0;
    const loaded = await pool(sitePages, 8, (p) => fetchDoc('/' + p.slug).then((doc) => ({ ...p, doc })).catch(() => null)
        .finally(() => { if (++pagesDone % 10 === 0) log(`открыто страниц: ${pagesDone} из ${sitePages.length}`); }));

    loaded.filter(Boolean).forEach(({ title, slug, doc }) => {
        const cards = [...doc.querySelectorAll('.lt-tsr-block')]
            .filter((card) => LABELS.some(([, re]) => re.test(card.textContent)))
            .map(parseCard);
        if (!cards.length) return;
        const name = heroTitle(doc);
        if (SKIP_PAGES.test(title) || !name) {
            cards.forEach((c) => { if (c.offer_id) spareOffers[c.title + '|' + c.hours] = c.offer_id; });
            log(`пропущена страница «${title}» (/${slug}, карточек ${cards.length}) — ${name ? 'черновая' : 'нет заголовка «Программы обучения»'}`);
            return;
        }
        const source = location.origin + '/' + slug;
        if (!directions.has(name)) {
            directions.set(name, {
                'Направление': name,
                'Страница GetCourse': source,
                'Фон (URL)': absUrl(doc.querySelector('.bld01 [data-bg], .bld01[data-bg]')?.getAttribute('data-bg')),
            });
        }
        cards.forEach((c) => cardsAll.push({ ...c, direction: name, source }));
        log(`${name} (/${slug}): карточек ${cards.length}`);
    });

    if (CHECK_OFFERS) log('шаг 4/6: ждём загрузку «Предложений» для сверки (до минуты)…');
    const offers = await offersPromise;
    if (CHECK_OFFERS && !offers) log('нет доступа к «Предложениям» (нужен вход администратора) — сверка пропущена');

    // Сравнение названий: доля слов предложения, которые есть в названии курса
    // (по первым 4 буквам — сокращения вида «доп.», «проф.» и кавычки не мешают)
    const stems = (s) => s.toLowerCase().replace(/ё/g, 'е').replace(/\(\s*\d+\s*(час|ак)[^)]*\)/g, ' ')
        .split(/[^a-zа-я0-9]+/).filter((w) => w.length >= 3 && !/^\d+$/.test(w)).map((w) => w.slice(0, 4));
    const similar = (offerTitle, title) => {
        const a = stems(offerTitle);
        const b = new Set(stems(title));
        return !a.length || a.filter((w) => b.has(w)).length / a.length >= 0.6;
    };
    const offerHours = (t) => (t.match(/\(\s*(\d+)\s*(?:час|ак)/i) || [])[1] || '';

    // Что не так с предложением для этой карточки; fatal — оплатить по нему нельзя
    const offerProblem = (c, id) => {
        const o = offers[id];
        if (!o) return { fatal: true, text: `предложение ${id} не найдено` };
        if (!o.actual) return { fatal: true, text: `предложение ${id} неактуально` };
        if (!similar(o.title, c.title)) return { other: true, text: `в предложении ${id} другой курс: «${o.title}»` };
        const h = offerHours(o.title);
        if (h && c.hours && h !== c.hours) return { other: true, text: `в предложении ${id} ${h} ч, на сайте ${c.hours} ч` };
        return null;
    };

    // Запасные варианты offer_id: тот же курс на черновой странице, затем «Название (N часов)» в «Предложениях»
    const norm = (s) => s.toLowerCase().replace(/ё/g, 'е').replace(/[^a-zа-я0-9]+/g, '');
    const byTitle = {};
    if (offers) {
        Object.entries(offers).forEach(([id, o]) => {
            const key = norm(o.title);
            if (!byTitle[key] || (o.actual && !offers[byTitle[key]].actual)) byTitle[key] = id;
        });
    }
    const candidates = (c) => [spareOffers[c.title + '|' + c.hours], byTitle[norm(`${c.title} (${c.hours_text})`)],
        byTitle[norm(`${c.title} (${c.hours} часов)`)], byTitle[norm(`${c.title} (${c.hours} часа)`)],
        byTitle[norm(`${c.title} (${c.hours} час)`)], byTitle[norm(c.title)]].filter(Boolean);

    cardsAll.forEach((c) => {
        c.notes = [];
        if (!offers) {
            // Без сверки берём offer_id с черновой страницы как есть
            if (!c.offer_id && spareOffers[c.title + '|' + c.hours]) {
                c.offer_id = spareOffers[c.title + '|' + c.hours];
                c.notes.push('offer_id взят с черновой страницы — проверьте');
            }
            return;
        }
        const own = c.offer_id && offerProblem(c, c.offer_id);
        if (c.offer_id && !own) return;
        // Кнопка без offer_id или с несуществующим/неактуальным/чужим — ищем подходящее предложение
        const alt = candidates(c).find((id) => id !== c.offer_id && !offerProblem(c, id));
        if (alt) {
            // Найденное без offer_id у кнопки уже сверено по названию и часам — не замечание
            if (c.offer_id) c.notes.push(`${own.text}; на сайте будет ${alt} «${offers[alt].title}»`);
            c.offer_id = alt;
        } else if (!c.offer_id) {
            c.notes.push('у кнопки нет offer_id, подходящее предложение не найдено');
        } else if (own.fatal) {
            c.notes.push(own.text + ', замены нет');
            c.offer_id = '';
        } else {
            c.notes.push(own.text + ' — проверьте кнопку на GetCourse');
        }
    });

    // Один курс может стоять на нескольких страницах — объединяем направления
    const courses = new Map();
    cardsAll.forEach((c) => {
        const key = c.title + '|' + c.hours;
        const prev = courses.get(key);
        if (prev) {
            if (!prev.directions.includes(c.direction)) prev.directions.push(c.direction);
            if (!prev.offer_id && c.offer_id) Object.assign(prev, { offer_id: c.offer_id, notes: c.notes });
            return;
        }
        courses.set(key, { ...c, directions: [c.direction] });
    });

    // Одно предложение у разных курсов — при оплате один из них продаст чужой курс
    const offerUsers = {};
    courses.forEach((c) => { if (c.offer_id) (offerUsers[c.offer_id] = offerUsers[c.offer_id] || []).push(c); });
    Object.entries(offerUsers).forEach(([id, list]) => {
        if (list.length > 1) list.forEach((c) => c.notes.push(`предложение ${id} стоит ещё у: ${list.filter((x) => x !== c).map((x) => `«${x.title}» ${x.hours} ч`).join(', ')}`));
    });

    // Образец документа: шаблон «Название (N часов)», иначе самый похожий шаблон с теми же часами
    log('шаг 5/6: подбираем образцы документов…');
    const diplomas = await diplomasPromise;
    if (!diplomas) log('нет доступа к «Обучение → Дипломы» — образцы документов пропущены');
    if (diplomas) {
        const tplHours = (t) => (t.match(/(\d+)\s*(?:ч|ак)[^\d]*$/i) || [])[1] || '';
        const tplName = (t) => t.replace(/^(?:ИМЕННАЯ|ЕСТЬ ДАТЫ)\.\s*/i, '')
            .replace(/^(?:Диплом|Удостоверение|Свидетельство|Протокол)[^.]*\.\s*/i, '')
            .replace(/[\s(]*\d+\s*(?:ч|ак)[^\d]*$/i, '');
        const tplByKey = {};
        const tplByHours = {};
        diplomas.forEach((t) => {
            const h = tplHours(t.title);
            const key = norm(tplName(t.title)) + '|' + h;
            if (!tplByKey[key] || t.issued > tplByKey[key].issued) tplByKey[key] = t;
            (tplByHours[h] = tplByHours[h] || []).push({ ...t, stems: new Set(stems(tplName(t.title))) });
        });
        // Похожесть в обе стороны: слова шаблона есть в названии курса и наоборот
        const overlap = (a, b) => (a.size && b.size ? [...a].filter((w) => b.has(w)).length / Math.max(a.size, b.size) : 0);
        // Замена слова (вид спорта, категория «В»/«С»): в курсе есть слово, которого нет в шаблоне,
        // и наоборот. Сокращения («доп.», «проф.») совпадают по началу слова.
        const words = (s) => s.toLowerCase().replace(/ё/g, 'е').replace(/\d+\s*(ч|ак)[а-я.]*/g, ' ')
            .replace(/«\s*([a-zа-я])\s*»/g, ' кат$1 ')
            .split(/[^a-zа-я0-9]+/).filter((w) => w.length >= 3 && !/^\d+$/.test(w));
        const prefix = (a, b) => { let i = 0; while (i < a.length && a[i] === b[i]) i++; return i; };
        const has = (w, list) => list.some((v) => { const p = prefix(w, v); return p >= 5 || (p >= 3 && (p === w.length || p === v.length)); });
        const replaced = (a, b) => a.some((w) => !has(w, b)) && b.some((w) => !has(w, a));
        const tplFor = (c) => {
            const exact = tplByKey[norm(c.title) + '|' + c.hours];
            if (exact) return exact;
            const own = new Set(stems(c.title));
            const ownWords = words(c.title);
            let best = null;
            let score = 0.75;
            (tplByHours[c.hours] || []).forEach((t) => {
                if (replaced(ownWords, words(tplName(t.title)))) return;
                const s = overlap(own, t.stems);
                if (s > score || (s === score && best && t.issued > best.issued)) { best = t; score = s; }
            });
            return best;
        };
        const tplIds = new Set();
        courses.forEach((c) => {
            c.tpl = tplFor(c);
            if (!c.tpl) return;
            tplIds.add(c.tpl.id);
            if (!tplByKey[norm(c.title) + '|' + c.hours]) c.tplFuzzy = tplName(c.tpl.title);
        });
        const images = {};
        let tplDone = 0;
        await pool([...tplIds], 8, async (id) => {
            images[id] = await diplomaImage(id).catch(() => '');
            if (++tplDone % 100 === 0 || tplDone === tplIds.size) log(`образцы документов: ${tplDone} из ${tplIds.size}`);
        });
        courses.forEach((c) => { c.sample = c.tpl ? images[c.tpl.id] : ''; });
    }

    const rows = [...courses.values()].map((c) => {
        const o = offers && c.offer_id ? offers[c.offer_id] : null;
        if (o && c.price && o.price && c.price !== o.price) c.notes.push(`на сайте ${c.price} р., в предложении ${o.price} р.`);
        const check = c.notes.join('; ');
        // Что проверить вручную — по этой колонке удобно фильтровать
        const doubt = [];
        if (!c.offer_id) doubt.push('нет offer_id');
        if (check) doubt.push('замечания сверки');
        if (diplomas && !c.sample) doubt.push('нет образца');
        else if (c.tplFuzzy) doubt.push('образец по похожему названию: ' + c.tplFuzzy);
        return {
            'Проверить': doubt.join('; '),
            'ID предложения (offer_id)': c.offer_id,
            'Название': c.title,
            'Часы': c.hours,
            'Направление': c.directions.join('; '),
            'Документ': c.document,
            'Срок действия': c.validity,
            'Требования': c.requirements,
            'Стоимость': c.price,
            'Картинка (URL)': c.image,
            'Образец документа (URL)': c.sample || '',
            'Краткое описание': '',
            'Полное описание': '',
            'Теги': '',
            'Прочий текст карточки': c.extra,
            'Сверка с GetCourse': check,
            'Источник': c.source,
        };
    });

    log('шаг 6/6: собираем Excel-файл…');
    const wb = XLSX.utils.book_new();
    // Шапка жирная на сером фоне, фильтры на всех колонках
    const sheet = (data, widths) => {
        const ws = XLSX.utils.json_to_sheet(data);
        ws['!cols'] = widths.map((w) => ({ wch: w }));
        const range = XLSX.utils.decode_range(ws['!ref']);
        ws['!autofilter'] = { ref: XLSX.utils.encode_range({ s: range.s, e: { r: range.e.r, c: range.e.c } }) };
        for (let c = range.s.c; c <= range.e.c; c++) {
            const cell = ws[XLSX.utils.encode_cell({ r: 0, c })];
            if (cell) cell.s = { font: { bold: true }, fill: { fgColor: { rgb: 'D9D9D9' } }, alignment: { wrapText: true, vertical: 'center' } };
        }
        return ws;
    };
    // Сомнительные находки: красный — нет offer_id или замечания сверки,
    // оранжевый — нет образца документа, жёлтый — образец подобран по похожему названию
    const RED = 'FFC7CE', ORANGE = 'FFD8A8', YELLOW = 'FFEB9C';
    const paint = (ws, data) => {
        const keys = Object.keys(data[0] || {});
        const fill = (r, key, rgb) => {
            const ref = XLSX.utils.encode_cell({ r: r + 1, c: keys.indexOf(key) });
            ws[ref] = ws[ref] || { t: 's', v: '' };
            ws[ref].s = { fill: { fgColor: { rgb } } };
        };
        data.forEach((row, r) => {
            const doubt = row['Проверить'];
            if (!doubt) return;
            const sample = doubt.includes('нет образца') ? ORANGE : doubt.includes('образец по похожему') ? YELLOW : '';
            if (sample) fill(r, 'Образец документа (URL)', sample);
            if (!row['ID предложения (offer_id)']) fill(r, 'ID предложения (offer_id)', RED);
            if (row['Сверка с GetCourse']) fill(r, 'Сверка с GetCourse', RED);
            fill(r, 'Проверить', /offer_id|сверки/.test(doubt) ? RED : sample);
        });
    };
    const coursesSheet = sheet(rows, [40, 14, 60, 7, 30, 30, 30, 40, 10, 40, 40, 30, 30, 20, 30, 28, 40]);
    paint(coursesSheet, rows);
    XLSX.utils.book_append_sheet(wb, coursesSheet, 'Курсы');
    XLSX.utils.book_append_sheet(wb, sheet([...directions.values()], [45, 45, 60]), 'Направления');
    const fileName = `getcourse-courses-${new Date().toISOString().slice(0, 10)}.xlsx`;
    // Скачивание по кнопке: без клика пользователя Chrome может молча заблокировать загрузку
    const blob = new Blob([XLSX.write(wb, { bookType: 'xlsx', type: 'array' })],
        { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    document.getElementById('gc-export-download')?.remove();
    const link = document.createElement('a');
    link.id = 'gc-export-download';
    link.href = URL.createObjectURL(blob);
    link.download = fileName;
    link.textContent = `⬇ Скачать Excel (${fileName})`;
    link.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);z-index:2147483647;'
        + 'padding:18px 32px;background:#199c68;color:#fff;font:bold 18px/1.2 Arial,sans-serif;'
        + 'border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,.35);text-decoration:none;cursor:pointer;';
    link.addEventListener('click', () => setTimeout(() => link.remove(), 1000));
    document.body.appendChild(link);

    const noOffer = rows.filter((r) => !r['ID предложения (offer_id)']).length;
    const warn = rows.filter((r) => r['Сверка с GetCourse']).length;
    const noSample = rows.filter((r) => !r['Образец документа (URL)']).length;
    const doubtful = rows.filter((r) => r['Проверить']).length;
    log(`готово: курсов ${rows.length}, направлений ${directions.size}, без offer_id ${noOffer}, без образца документа ${noSample}, с замечаниями ${warn}; на проверку ${doubtful} (колонка «Проверить», цветные ячейки, фильтр в шапке). Время: ${Math.round((Date.now() - startedAt) / 1000)} с.`);
    log('▶ Нажмите зелёную кнопку «Скачать Excel» вверху страницы.');
})().catch((e) => console.error('[GC export] ОШИБКА, выгрузка остановлена:', e.message));
