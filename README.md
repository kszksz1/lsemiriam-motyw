# Motyw strony lsemiriam.pl

Motyw WordPress **„Miriam LSE”** strony Lubelskiej Szkoły Ewangelizacji Miriam.

- `miriam-lse/` — kod motywu (to, co jest na serwerze w `wp-content/themes/miriam-lse/`)
- `.github/workflows/wdrozenie.yml` — automatyczne wdrożenie na serwer po zmianie w gałęzi `main`

## Jak wprowadzać zmiany

1. Zmiana trafia do osobnej gałęzi i prośby o scalenie (pull request).
2. Po przejrzeniu klikasz **Merge** — GitHub sam wyśle motyw na lsemiriam.pl (zakładka **Actions** pokazuje przebieg).
3. Nie edytuj plików motywu w panelu WordPressa (Wygląd → Edytor plików) — następne wdrożenie je nadpisze.

Treści, wydarzenia (menu **Wydarzenia**), prośby o modlitwę i ustawienia (**Wygląd → Dostosuj → Strona Miriam**) zmienia się w panelu WordPressa jak dotąd — nie są w tym repozytorium.

## Konfiguracja wdrożenia (jednorazowo)

1. DirectAdmin → **Konta FTP** → nowe konto z katalogiem `public_html/wp-content/themes/miriam-lse`.
2. GitHub → **Settings → Secrets and variables → Actions → New repository secret**:
   - `FTP_SERVER` = `h43.seohost.pl`
   - `FTP_USERNAME` = nazwa konta FTP (zwykle z dopiskiem `@lsemiriam.pl`)
   - `FTP_PASSWORD` = hasło konta FTP
3. Jeśli konto FTP wskazuje na inny katalog niż folder motywu, dodaj zmienną `FTP_DIR` (zakładka **Variables**) ze ścieżką do folderu motywu, np. `./domains/lsemiriam.pl/public_html/wp-content/themes/miriam-lse/`.
4. **Actions → Wdrożenie motywu → Run workflow**, żeby sprawdzić połączenie.

## Wersje

Numer wersji jest w nagłówku `miriam-lse/style.css`. Przy każdej zmianie podnieś go (np. 1.5.7 → 1.5.8), żeby przeglądarki pobrały nowe style.

## Awaryjnie

- Wdrożenie się nie udało → strona działa na poprzedniej wersji; szczegóły w zakładce Actions.
- Coś się zepsuło na stronie → cofnij ostatnie scalenie (Revert na GitHubie) albo w panelu WordPressa włącz na chwilę poprzedni motyw.

## Logo i znaki

Folder `marka/` zawiera oryginalne logo (PDF, wektor) i ich wersje SVG: pełne logo kolorowe i białe, logo na okręgu, sygnet. Nie jest wysyłany na serwer — to materiały źródłowe.

## Zdjęcia

Zdjęcia wspólnoty są w `miriam-lse/assets/photos/` (WebP, zmniejszone). Zdjęcia w kafelkach na górze strony głównej można podmienić bez programisty: **Wygląd → Dostosuj → Strona Miriam → Plakat kafelka 1–3**. Pozostałe zdjęcia podmienia się, wgrywając plik o tej samej nazwie.

## Treści podstron

Podstrony Pierwszy raz, Spotkania, Kursy, Silent Disco, O nas i Kontakt edytuje się w panelu WordPressa (Strony → Edytuj), jak zwykły tekst w blokach.
- Pomarańczowy fragment nagłówka = tekst w kursywie (zaznacz i Ctrl+I).
- Bloki „Krótki kod” `[miriam_…]` to elementy automatyczne: terminy (`[miriam_terminy rodzaj="kurs"]`, `rodzaj="uzdrowienie"`), formularz modlitwy (`[miriam_modlitwa]`), kalendarz i WhatsApp (`[miriam_kalendarz]`), mapa (`[miriam_mapa]`), e-mail i telefon (`[miriam_kontakt]`), Silent Disco (`[miriam_silent_disco]`), filmy z YouTube (`[miriam_filmy]`). Można je przesuwać i usuwać.
- Przyciski: w panelu bloku wybierz styl (pomarańczowy, niebieski, kontur…).
- Domyślne treści są w `miriam-lse/tresci/` (generuje je `narzedzia/tresci.py`). Trafiły do WordPressa jednorazowo w wersji 1.9.0; poprzednie wersje stron są w rewizjach.

Adresy podstron: /pierwszy-raz/, /spotkania/, /kursy/, /silent-disco/, /o-nas/, /kontakt/, /swiadectwa/. Stare adresy (np. /historia/, /jakie-kursy-sa-dla-mnie/, /uwielbienie-silent-disco/) przekierowują na nowe (inc/pages.php). Stare, nieużywane strony są w szkicach z dopiskiem [archiwum].
