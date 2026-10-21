#!/usr/bin/env python3
"""Generuje domyślne treści podstron (bloki WordPressa) do miriam-lse/tresci/*.html.
Uruchom: python3 narzedzia/tresci.py
Treści trafiają do WordPressa tylko raz (migracja w inc/pages.php); potem edytuje się je w panelu."""
import json, os, html

OUT = os.path.join(os.path.dirname(__file__), '..', 'miriam-lse', 'tresci')
MAPS = 'https://maps.google.com/?q=Filaret%C3%B3w+7,+Lublin'
IG = 'https://www.instagram.com/lse_miriam/'

def attrs(d):
    return (' ' + json.dumps(d, ensure_ascii=False, separators=(',', ':'))) if d else ''

def p(text, cls=None):
    a = {'className': cls} if cls else {}
    c = f' class="{cls}"' if cls else ''
    return f'<!-- wp:paragraph{attrs(a)} -->\n<p{c}>{text}</p>\n<!-- /wp:paragraph -->'

def h(text, level=2, cls=None):
    a = {}
    if level != 2: a['level'] = level
    if cls: a['className'] = cls
    c = 'wp-block-heading' + (' ' + cls if cls else '')
    return f'<!-- wp:heading{attrs(a)} -->\n<h{level} class="{c}">{text}</h{level}>\n<!-- /wp:heading -->'

def group(children, cls=None, anchor=None):
    a = {}
    if anchor: a['anchor'] = anchor
    if cls: a['className'] = cls
    idattr = f' id="{anchor}"' if anchor else ''
    c = 'wp-block-group' + (' ' + cls if cls else '')
    return f'<!-- wp:group{attrs(a)} -->\n<div{idattr} class="{c}">' + '\n\n'.join(children) + '</div>\n<!-- /wp:group -->'

def buttons(items, cls='btn-row'):
    bs = []
    for label, href, style, *rest in items:
        small = bool(rest and rest[0])
        bcls = f'is-style-{style}' + (' small' if small else '')
        bs.append(f'<!-- wp:button {{"className":"{bcls}"}} -->\n<div class="wp-block-button {bcls}"><a class="wp-block-button__link wp-element-button" href="{html.escape(href)}">{label}</a></div>\n<!-- /wp:button -->')
    return f'<!-- wp:buttons {{"className":"{cls}"}} -->\n<div class="wp-block-buttons {cls}">' + '\n\n'.join(bs) + '</div>\n<!-- /wp:buttons -->'

def verse(text, ref):
    return ('<!-- wp:quote {"className":"verse"} -->\n<blockquote class="wp-block-quote verse">'
            + p('„' + text + '”') + f'<cite>{ref}</cite></blockquote>\n<!-- /wp:quote -->')

def faq(q, a):
    return (f'<!-- wp:details {{"className":"faq"}} -->\n<details class="wp-block-details faq"><summary>{q}</summary>'
            + p(a) + '</details>\n<!-- /wp:details -->')

def sc(code):
    return f'<!-- wp:shortcode -->\n{code}\n<!-- /wp:shortcode -->'

def steps(items):
    lis = ''.join(f'<!-- wp:list-item -->\n<li><strong>{t}</strong>{d}</li>\n<!-- /wp:list-item -->' for t, d in items)
    return f'<!-- wp:list {{"ordered":true,"className":"sd-steps"}} -->\n<ol class="wp-block-list sd-steps">{lis}</ol>\n<!-- /wp:list -->'

def hero(kicker, title, lead=None, extra=()):
    kids = [p(kicker, 'kicker'), h(title, 1, 'display')]
    if lead: kids.append(p(lead, 'lead'))
    kids += list(extra)
    return group(kids, 'page-hero glow has-photo')

def head(title, text=None, kicker=None, level=2, hcls='display h-md'):
    first = h(title, level, hcls) if not kicker else group([p(kicker, 'kicker'), h(title, level, hcls)])
    kids = [first] + ([p(text)] if text else [])
    return group(kids, 'sec-head')

def card(*kids, cls='card'):
    return group(list(kids), cls)

def cards(cs, cls='cards-3'):
    return group(cs, cls)

def sec(kids, cls='sec light', anchor=None):
    return group(kids, cls, anchor)

pages = {}

pages['pierwszy-raz'] = [
    hero('Pierwszy raz?', 'Nie musisz mieć poukładanego życia,<br><em>żeby spotkać Boga.</em>',
         'Wystarczy przyjść. Nie trzeba znać pieśni ani wiedzieć, jak się zachować. Poniżej znajdziesz wszystko, czego możesz się spodziewać.'),
    sec([head('Dla kogo jest Miriam?', 'Dla każdego, kto szuka. Bez warunków wstępnych. Większość z nas to młodzi ludzie, ale są wśród nas osoby w każdym wieku.'),
         cards([card(h('Wracasz do wiary', 3), p('Po przerwie, po latach, po trudnych doświadczeniach — i nie wiesz, od czego zacząć.')),
                card(h('Szukasz wspólnoty', 3), p('Miejsca, gdzie można się modlić, rozmawiać i wzrastać razem z innymi.')),
                card(h('Chcesz czegoś żywego', 3), p('Jesteś zmęczony religijnością bez doświadczenia i szukasz osobistego spotkania z Jezusem.')),
                card(h('Chcesz służyć', 3), p('Uczyć się modlitwy i ewangelizacji, brać odpowiedzialność za swoją wiarę i innych.'))], 'cards-3 cols-4')]),
    sec([head('Jak wygląda wtorek?', 'Zaczynamy o 19:00. Możesz włączyć się we wszystko albo po prostu być i słuchać.'),
         cards([card(p('01', 'num'), h('Uwielbienie', 3), p('Śpiewamy i modlimy się — głośno i w ciszy. Nie musisz znać pieśni, szybko je podłapiesz.')),
                card(p('02', 'num'), h('Konferencja', 3), p('Krótkie nauczanie o Bogu i życiu wiarą — temat zależy od bieżącej formacji wspólnoty.')),
                card(p('03', 'num'), h('Rozmowy', 3), p('Zostań chwilę. Podejdź do kogoś z nas, zapytaj o cokolwiek — chętnie Cię poznamy.'))]),
         p('Raz w miesiącu wtorkowe spotkanie to otwarta Msza Święta z modlitwą o uzdrowienie. <a href="/spotkania/#uzdrowienie">Zobacz terminy</a>.', 'note')],
        'sec light stories'),
    sec([head('Jak do nas trafić?', 'Dolny kościół parafii św. Józefa, ul. Filaretów 7 w Lublinie. Wejście z tyłu kościoła, od ul. Rymwida (od strony kładki).'),
         buttons([('Otwórz w mapach', MAPS, 'blue'), ('Zmiany ogłaszamy na Instagramie', IG, 'outline')])]),
    sec([h('Często pytacie', 2, 'display h-md faq-title'),
         faq('Czy spotkania są dla każdego?', 'Tak. Nie ma warunków wstępnych. Możesz przyjść także wtedy, gdy dopiero szukasz swojej drogi wiary albo masz więcej pytań niż odpowiedzi.'),
         faq('Czy muszę znać pieśni i sposób modlitwy?', 'Nie. Możesz po prostu słuchać, a w modlitwie uczestniczyć tak, jak czujesz się swobodnie.'),
         faq('Czy spotkania odbywają się w wakacje?', 'Tak, spotykamy się przez cały rok. Gdy spotkanie odbywa się w innym miejscu, informujemy o tym w mediach społecznościowych.'),
         faq('Czy mogę przyjść z kimś?', 'Oczywiście — przyprowadź przyjaciela, rodzeństwo, kogokolwiek. We dwoje łatwiej zrobić pierwszy krok.'),
         faq('Co dalej, jeśli mi się spodoba?', 'Przychodź we wtorki, a gdy będziesz gotowy, zapisz się na kurs Nowe Życie — to najlepszy pierwszy krok w formacji.')],
        'sec light stories narrow'),
]

pages['spotkania'] = [
    hero('Spotkania wspólnoty', 'Każdy wtorek, 19:00.<br><em>Przez cały rok.</em>',
         'Dolny kościół parafii św. Józefa, ul. Filaretów 7 w Lublinie — wejście z tyłu kościoła, od ul. Rymwida (od strony kładki). Otwarte dla każdego, bez zapisów i bez warunków.'),
    sec([head('Jak wyglądają spotkania?', 'Najważniejsza jest modlitwa i osobiste spotkanie z Bogiem. Wszystko inne temu służy.'),
         cards([card(h('Uwielbienie', 3), p('Śpiewamy i wielbimy Boga — głośno i w ciszy. Prowadzi nas zespół muzyczny wspólnoty.')),
                card(h('Słowo', 3), p('Konferencja na temat związany z bieżącą formacją: jak żyć z Jezusem na co dzień.')),
                card(h('Wspólnota', 3), p('Relacje, rozmowy, wzajemna modlitwa. Nie idziemy tą drogą w pojedynkę.'))])]),
    sec([head('Msza z modlitwą o uzdrowienie', 'W jeden wtorek w miesiącu spotkanie w dolnym kościele jest otwartą Mszą Świętą z mocną modlitwą charyzmatyczną i uwielbieniem. Przyjdź ze swoją chorobą, zranieniem, pytaniem — i przyprowadź bliskich.', kicker='Raz w miesiącu'),
         sc('[miriam_terminy rodzaj="uzdrowienie"]')], 'sec light stories', 'uzdrowienie'),
    sec([head('Wychodzimy na ulice', 'Wiarą w Zmartwychwstałego Jezusa dzielimy się także poza kościołem — na ulicach Lublina, w parafiach, podczas uwielbień Silent Disco. Jeśli chcesz się włączyć, przyjdź we wtorek i powiedz nam o tym.')], 'sec light'),
    sc('[miriam_modlitwa]'),
    sc('[miriam_kalendarz]'),
]

catalog = [
    ('Nowe Życie', 'Jeśli chcesz zacząć od nowa', 'Najczęściej najlepszy pierwszy krok. Doświadczysz, że Bóg kocha, przebacza i daje nowy początek, a Ewangelia jest dobrą nowiną o Twoim życiu.', 'Dla osób, które wracają do wiary, czują rutynę albo chcą zaprosić Jezusa do swojego życia.'),
    ('Emaus', 'Jeśli chcesz słuchać Boga w Biblii', 'Pismo Święte jako żywe Słowo, które mówi dziś — do Twoich decyzji, relacji i codzienności.', 'Dla osób, które chcą czytać Biblię głębiej albo szukają światła w rozeznawaniu.'),
    ('Jan', 'Jeśli chcesz wejść głębiej w relację z Jezusem', 'Od wiary opartej na wiedzy czy emocjach do osobistej bliskości i uczniostwa.', 'Po kursie Emaus — dla tych, którzy pragną głębszej modlitwy i dojrzałej wiary.'),
    ('Jezus w 4 Ewangeliach', 'Jeśli chcesz lepiej poznać Jezusa', 'Cztery Ewangelie, jeden Jezus: Pan, Nauczyciel, Zbawiciel i Przyjaciel, który wzywa do konkretnej odpowiedzi.', 'Po kursie Jan — dla tych, którzy chcą świadomiej głosić Jezusa.'),
    ('Historia Zbawienia', 'Jeśli chcesz zobaczyć swoje życie w Bożym planie', 'Od stworzenia, przez przymierze, po Jezusa, Kościół i misję — i Twoje miejsce w tej historii.', 'Po kursie Jan — łączy Stary i Nowy Testament.'),
    ('Mojżesz', 'Jeśli prowadzisz innych', 'Przywództwo jako służba, formacja serca i odpowiedzialność przed Bogiem.', 'Dla liderów, animatorów i osób rozeznających większą odpowiedzialność.'),
]
pages['kursy'] = [
    hero('Kursy Szkoły Ewangelizacji św. Andrzeja', 'Kilka dni,<br><em>które zmieniają życie.</em>',
         'Kursy SESA to intensywne rekolekcje: głoszenie, modlitwa, praca w małych grupach, świadectwa i osobista decyzja wiary. Nie wykład — spotkanie z Bogiem, który kocha, zbawia, uzdrawia i posyła.',
         [verse('Idźcie więc i czyńcie uczniów ze wszystkich narodów', 'Mt 28,19'),
          buttons([('Najbliższe terminy', '#terminy', 'orange'), ('Który kurs dla mnie?', '#ktory-kurs', 'ghost')])]),
    sec([head('Najbliższe terminy', 'Nie wiesz, od czego zacząć? Wybierz <strong>Nowe Życie</strong>.'),
         sc('[miriam_terminy rodzaj="kurs"]')], anchor='terminy'),
    sec([head('Który kurs jest dla mnie?', 'Nie musisz znać całej drogi, żeby zrobić pierwszy krok. Liczy się to, czy chcesz zrobić kolejny.'),
         cards([card(p(k[1], 'fact-label'), h(k[0], 3), p(k[2]), p(k[3], 'small')) for k in catalog], 'cards-3 cols-wide')],
        'sec light stories', 'ktory-kurs'),
    sec([head('Dla kogo są kursy?', 'Dla każdego, kto chce odnowić relację z Bogiem, wrócić do wiary, lepiej zrozumieć Ewangelię, znaleźć miejsce we wspólnocie, nauczyć się głosić Jezusa albo pogłębić modlitwę i kontakt ze Słowem.'),
         p('Pytania o kursy? Napisz: <a href="mailto:lublin.se@gmail.com">lublin.se@gmail.com</a>', 'note')]),
]

timeline = [
    ('2016', 'Lubartów', 'Ks. Krzysztof Krzaczek, zainspirowany kursami Szkoły Ewangelizacji św. Andrzeja, gromadzi pierwszych uczniów. W kwietniu odbywa się pierwszy kurs — przy wsparciu Wspólnoty Miriam „Brama Niebios” z Lubartowa i Odnowy w Duchu Świętym ze Świdnika.'),
    ('2017', 'Uznanie', 'Szkoła podpisuje porozumienie z SESA Polska, a abp Stanisław Budzik wydaje dekret potwierdzający jej działalność.'),
    ('2019', 'Lublin', 'Po Seminarium Odnowy Wiary w parafii św. Józefa rodzi się Wspólnota Lubelskiej Szkoły Ewangelizacji Miriam.'),
    ('2020', 'Silent Disco', 'Startują comiesięczne uwielbienia Silent Disco, a nagrania z nich docierają do milionów słuchaczy.'),
    ('Dziś', 'Wspólnota', 'Kilkadziesiąt osób, wtorkowe spotkania, kursy SESA we współpracy z zaprzyjaźnionymi wspólnotami i ewangelizacja na ulicach Lublina.'),
]
pages['o-nas'] = [
    hero('O nas', 'Uczniowie Jezusa,<br><em>którzy prowadzą innych do Niego.</em>',
         'Lubelska Szkoła Ewangelizacji Miriam jest częścią Międzynarodowej Szkoły Ewangelizacji św. Andrzeja (SESA) i Katolickiej Odnowy w Duchu Świętym Archidiecezji Lubelskiej.',
         [buttons([('Charyzmat', '#charyzmat', 'ghost', True), ('Historia', '#historia', 'ghost', True), ('Pasterz i odpowiedzialni', '#pasterz', 'ghost', True)])]),
    sec([head('Charyzmat', 'Chcemy być uczniami, którzy każdego dnia idą za Jezusem jako Panem i Zbawicielem, i Jego świadkami wszędzie, gdzie jesteśmy. Wzorem jest dla nas Maryja — pierwsza i najwierniejsza uczennica.'),
         verse('Nie możemy być spokojni, myśląc o milionach naszych braci i sióstr, również odkupionych krwią Chrystusa, którzy żyją, nie znając miłości Boga', 'św. Jan Paweł II, Redemptoris missio 86'),
         cards([card(p(n, 'num'), h(t, 3), p(d)) for n, t, d in [
             ('01', 'Uwielbienie', 'Modlitwa, która stawia Boga w centrum.'),
             ('02', 'Ewangelizacja', 'Głoszenie Jezusa na wiele sposobów.'),
             ('03', 'Uczniostwo', 'Formacja, która przemienia życie.'),
             ('04', 'Relacje', 'Braterstwo i siostrzeństwo na co dzień.'),
             ('05', 'Służba', 'Dary dane po to, by służyć innym.')]], 'cards-3 cols-5')], anchor='charyzmat'),
    sec([head('Historia', 'Od małej grupy w Lubartowie do wspólnoty w Lublinie.'),
         group([group([p(f'<strong>{y}</strong>{pl}', 'course-date'), p(t, 'course-text')], 'course') for y, pl, t in timeline], 'courses')],
        'sec light stories', 'historia'),
    sec([head('Pasterz i odpowiedzialni', 'Za życie wspólnoty odpowiada Rada Wspólnoty, na której czele stoją Pasterz i Liderzy.'),
         cards([card(p('Pasterz i dyrektor Szkoły', 'fact-label'), h('ks. Krzysztof Krzaczek', 3)),
                card(p('Liderzy wspólnoty', 'fact-label'), h('Arek Borzęcki<br>Amadeusz Świta', 3)),
                card(p('Organizacja kursów SESA', 'fact-label'), h('Wiktor Terelak', 3)),
                card(p('Rada Wspólnoty', 'fact-label'), p('Katarzyna Borzęcka, Sylwia Krzemińska, Agata Nawrocka, Paweł Pyszel, Aleksandra Terelak', 'names'))], 'cards-3 cols-4'),
         buttons([('Poznaj historie naszych ludzi', '/swiadectwa/', 'blue')])], anchor='pasterz'),
]

pages['kontakt'] = [
    hero('Kontakt i dojazd', 'Napisz, zadzwoń<br><em>albo po prostu przyjdź.</em>'),
    sec([cards([
        card(p('Spotkania', 'fact-label'), h('Wtorki, 19:00', 3), p('Dolny kościół parafii św. Józefa<br>ul. Filaretów 7, 20-609 Lublin'),
             p('Wejście z tyłu kościoła, od ul. Rymwida (od strony kładki).', 'small'), buttons([('Jak dotrzeć', MAPS, 'blue', True)])),
        card(p('Napisz lub zadzwoń', 'fact-label'), sc('[miriam_kontakt]'),
             p('Odpowiadamy na pytania o spotkania, kursy i Silent Disco. Jeśli chcesz, żebyśmy się za Ciebie pomodlili — też napisz.')),
        card(p('Bądź blisko', 'fact-label'),
             p(f'<a href="{IG}">Instagram @lse_miriam</a><br><a href="https://www.facebook.com/LubSEMiriam">Facebook</a><br><a href="https://www.youtube.com/@LSEMiriam">YouTube</a><br><a href="https://open.spotify.com/artist/7rzeMzZRiC8A39RMqgyK9t">Spotify</a>'),
             p('Zmiany miejsca i terminów ogłaszamy na Instagramie i Facebooku.', 'small'))], 'cards-3 cols-wide')]),
    sec([sc('[miriam_mapa]')], 'sec light stories'),
    sec([head('Wesprzyj nas', 'Uwielbienia, kursy i ewangelizacja są możliwe dzięki ludziom, którzy w nas wierzą. Możesz nas wspierać regularnie przez Patronite.'),
         buttons([('Wesprzyj na Patronite', 'https://patronite.pl/PlonacyKrzak', 'blue')])]),
    sc('[miriam_modlitwa]'),
    sc('[miriam_kalendarz]'),
]

pages['silent-disco'] = [
    group([head('Duch Święty to najlepszy DJ.<br><em>Zna Twój rytm.</em>',
                'Jesteśmy razem, a w słuchawkach każdy słyszy to, co Bóg mówi właśnie do jego serca. Muzyka, światło, krótka konferencja, adoracja Najświętszego Sakramentu i zaproszenie, by oddać życie Jezusowi.',
                kicker='Uwielbienie Silent Disco', level=1, hcls='display h-lg'),
           sc('[miriam_silent_disco]')], 'sec sd sd-top'),
    group([head('Jak wygląda Silent Disco?', 'Pierwszy raz? Nie musisz nic umieć ani niczego przygotowywać. Załóż słuchawki i pozwól, żeby muzyka pomogła Ci wyciszyć wszystko inne.'),
           steps([('Modlitwa o Ducha Świętego', 'Zaczynamy od zaproszenia Go, żeby to On prowadził ten wieczór.'),
                  ('Uwielbienie w słuchawkach', 'Pieśni w klimatycznych aranżacjach i światło, które pomagają się skupić.'),
                  ('Krótka konferencja', 'Słowo, które porusza i wyjaśnia, Kim jest Bóg dla Ciebie.'),
                  ('Adoracja', 'Cisza przed Jezusem w Najświętszym Sakramencie.'),
                  ('Twoja decyzja', 'Zaproszenie, by — jeśli chcesz — oddać swoje życie Jezusowi.')])], 'sec sd-dark tight-bottom'),
    group([head('Historia', 'Uwielbienia Silent Disco odbywają się co miesiąc od września 2020 roku. Przez trzy lata służyliśmy w parafii św. Józefa, w sezonie 2023/24 ruszyliśmy w Silent Disco Tour po parafiach Archidiecezji Lubelskiej, a od 2024 roku znów spotykamy się u św. Józefa. Większość uwielbień jest nagrywana — pieśni znajdziesz na YouTube i Spotify.'),
           sc('[miriam_filmy]'),
           buttons([('Słuchaj na Spotify', 'https://open.spotify.com/artist/7rzeMzZRiC8A39RMqgyK9t', 'white', True), ('Kanał YouTube', 'https://www.youtube.com/@LSEMiriam', 'ghost', True)])],
          'sec sd-dark tight-top'),
]

os.makedirs(OUT, exist_ok=True)
for slug, blocks in pages.items():
    with open(os.path.join(OUT, slug + '.html'), 'w') as f:
        f.write('\n\n'.join(blocks) + '\n')
    print(slug, sum(len(b) for b in blocks))
