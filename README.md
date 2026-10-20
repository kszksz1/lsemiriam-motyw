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
