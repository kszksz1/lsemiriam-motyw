<?php
/**
 * Polityka prywatności.
 */
$admin   = miriam_opt( 'rodo_admin' );
$details = miriam_opt( 'rodo_details' );
$email   = miriam_opt( 'email' );
$phone   = miriam_opt( 'phone' );
?>
<main id="main">
	<section class="page-hero glow">
		<div class="wrap">
			<p class="kicker">LSE Miriam</p>
			<h1 class="display">Polityka prywatności</h1>
		</div>
	</section>
	<section class="page-content">
		<div class="wrap">
			<h2>Administrator danych</h2>
			<p>Administratorem danych osobowych przekazywanych przez tę stronę jest <strong><?php echo esc_html( $admin ); ?></strong>, w ramach którego działa Lubelska Szkoła Ewangelizacji Miriam.</p>
			<?php if ( $details ) : ?><p style="white-space:pre-line"><?php echo esc_html( $details ); ?></p><?php endif; ?>
			<p>W sprawach danych osobowych możesz pisać bezpośrednio do KSM (dane powyżej) albo do nas: <a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a><?php if ( $phone ) : ?> lub zadzwoń: <?php echo esc_html( $phone ); ?><?php endif; ?>.</p>

			<h2>Prośby o modlitwę</h2>
			<p>Przez formularz „Prośba o modlitwę” zbieramy treść intencji oraz — jeśli je podasz — imię i adres e-mail. Intencja może zawierać informacje o zdrowiu Twoim lub bliskich.</p>
			<ul>
				<li><strong>Cel:</strong> modlitwa wspólnoty w Twojej intencji oraz odpowiedź, jeśli podasz e-mail.</li>
				<li><strong>Podstawa:</strong> Twoja wyraźna zgoda (art. 6 ust. 1 lit. a oraz art. 9 ust. 2 lit. a RODO).</li>
				<li><strong>Kto ma dostęp:</strong> wyłącznie osoby ze wspólnoty odpowiedzialne za modlitwę wstawienniczą.</li>
				<li><strong>Jak długo:</strong> <?php echo (int) MIRIAM_PRAYER_DAYS; ?> dni — potem intencja jest automatycznie usuwana.</li>
				<li>Zgodę możesz wycofać w każdej chwili, pisząc na adres podany wyżej.</li>
			</ul>

			<h2>Zapisy na kursy</h2>
			<p>Zapisy na kursy prowadzimy przez formularze Microsoft Forms. Administratorem danych uczestników jest również <?php echo esc_html( $admin ); ?>. W formularzu wyrażasz zgodę na przetwarzanie danych, a pełna klauzula RODO jest do podpisania na recepcji na początku rekolekcji.</p>

			<h2>Ciasteczka i usługi zewnętrzne</h2>
			<p>Strona nie używa ciasteczek analitycznych ani reklamowych. Czcionki i miniatury filmów są pobierane z naszego serwera.</p>
			<p>Film z YouTube, odtwarzacz Spotify i mapa Google ładują się dopiero wtedy, gdy klikniesz odpowiedni przycisk. Od tego momentu obowiązują zasady prywatności tych serwisów (Google/YouTube, Spotify), które mogą zapisywać własne ciasteczka.</p>
			<p>Linki do Instagrama, Facebooka, WhatsAppa, Patronite i formularzy zapisów prowadzą do zewnętrznych serwisów, które mają własne zasady prywatności.</p>

			<h2>Kalendarz wspólnoty</h2>
			<p>Subskrypcja kalendarza nie wymaga podawania żadnych danych. Twoja aplikacja kalendarza pobiera od nas jedynie listę wydarzeń.</p>

			<h2>Dobrowolność i profilowanie</h2>
			<p>Podanie danych jest dobrowolne. W formularzu modlitwy możesz pozostać całkowicie anonimowy. Przy zapisach na kursy brak danych może uniemożliwić udział. Dane nie są profilowane ani wykorzystywane do automatycznego podejmowania decyzji.</p>

			<h2>Twoje prawa</h2>
			<p>Masz prawo dostępu do swoich danych, ich sprostowania, usunięcia, ograniczenia przetwarzania, przenoszenia, wniesienia sprzeciwu oraz cofnięcia zgody w dowolnym momencie (bez wpływu na zgodność z prawem przetwarzania przed jej cofnięciem). Masz też prawo złożyć skargę do Prezesa Urzędu Ochrony Danych Osobowych (ul. Stawki 2, 00-193 Warszawa). Jeśli we wspólnocie Kościoła katolickiego obowiązują Cię przepisy Dekretu ogólnego KEP w sprawie ochrony osób fizycznych w związku z przetwarzaniem danych osobowych w Kościele katolickim, skargę możesz złożyć także do Kościelnego Inspektora Ochrony Danych.</p>

			<p style="margin-top:40px;color:var(--ink-soft);font-size:15px">Ostatnia aktualizacja: <?php echo esc_html( wp_date( 'j.m.Y', filemtime( __FILE__ ) ) ); ?></p>
		</div>
	</section>
</main>
