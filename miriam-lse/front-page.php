<?php
/**
 * Strona główna.
 */
get_header();

$l        = miriam_links();
$sd       = miriam_get_events( 'silent-disco', 12 );
$courses  = miriam_get_events( 'kurs', 12 );
$heal     = miriam_get_events( 'uzdrowienie', 6 );
$videos   = miriam_latest_videos( 7 );
$feat_id  = miriam_yt_id( miriam_opt( 'featured_video' ) );
$feat_tit = miriam_opt( 'featured_title' );
$months_s = miriam_months( 'short' );

$first_url = miriam_page_url( array( 'pierwsza-wizyta', 'pierwszy-raz-2', 'pierwszy-raz' ), home_url( '/#pierwszy-raz' ) );
$story_url = miriam_page_url( array( 'swiadectwa' ), '' );

/* Hero tiles: video → photo → YouTube thumbnail. */
$thumbs = array();
foreach ( $videos as $v ) {
	if ( $v['id'] !== $feat_id ) {
		$th = miriam_yt_thumb( $v['id'] );
		if ( $th ) {
			$thumbs[] = $th;
		}
	}
}
if ( $feat_id ) {
	$th = miriam_yt_thumb( $feat_id );
	if ( $th ) {
		array_unshift( $thumbs, $th );
	}
}
$tiles = array();
for ( $i = 1; $i <= 3; $i++ ) {
	$video  = miriam_opt( 'hero_video_' . $i );
	$poster = miriam_img_url( 'hero_poster_' . $i );
	$photo  = array( 1 => 'hero-perkusja', 2 => 'hero-spiew', 3 => 'hero-adoracja' );
	$tiles[] = array( 'video' => $video, 'img' => $poster ? $poster : miriam_photo( $photo[ $i ] ) );
}

$sd_poster = miriam_img_url( 'sd_poster' );
$sd_poster = $sd_poster ? $sd_poster : miriam_asset( 'silent-disco.webp' );
$next_sd   = $sd ? $sd[0] : null;
$next_c    = $courses ? $courses[0] : null;
$next_h    = $heal ? $heal[0] : null;
?>

<main id="main">

<section class="hero glow" id="start">
	<div class="wrap hero-main">
		<div class="hero-copy">
			<p class="kicker"><?php echo esc_html( miriam_opt( 'hero_kicker' ) ); ?></p>
			<h1 class="display h-xl">Wyłącz świat.<br><span class="accent">Usłysz Boga.</span></h1>
			<p class="lead">Modlimy się, uwielbiamy i głosimy Jezusa — z radością, nie po smutnokatolicku. Jesteśmy razem, a każdy słyszy to, co Bóg mówi właśnie do jego serca.</p>
			<div class="btn-row">
				<a class="btn btn-orange" href="<?php echo esc_url( $first_url ); ?>">Przyjdź pierwszy raz</a>
				<a class="btn btn-ghost" href="#posluchaj"><?php echo miriam_icon( 'play', 18 ); ?>Posłuchaj uwielbienia</a>
			</div>
		</div>
		<div class="hero-media" aria-hidden="true">
			<?php foreach ( $tiles as $t ) : ?>
				<div class="hero-tile">
					<?php if ( $t['video'] ) : ?>
						<video src="<?php echo esc_url( $t['video'] ); ?>" poster="<?php echo esc_url( $t['img'] ); ?>" autoplay muted loop playsinline preload="metadata"></video>
					<?php else : ?>
						<img src="<?php echo esc_url( $t['img'] ); ?>" alt="" loading="eager">
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="wrap">
		<div class="facts">
			<div class="fact">
				<span class="fact-label">Co tydzień</span>
				<span class="fact-title">Wtorek, 19:00</span>
				<span class="fact-meta">Spotkanie wspólnoty · Filaretów 7</span>
			</div>
			<?php if ( $next_h ) : ?>
			<div class="fact">
				<span class="fact-label is-orange">Msza o uzdrowienie</span>
				<span class="fact-title"><?php echo esc_html( miriam_ucfirst( miriam_weekday( $next_h['start'] ) ) . ', ' . miriam_short_date( $next_h['start'] ) ); ?></span>
				<span class="fact-meta"><?php echo esc_html( $next_h['time'] ? $next_h['time'] : '19:00' ); ?> · <a href="#uzdrowienie">więcej</a></span>
			</div>
			<?php endif; ?>
			<?php if ( $next_sd ) : ?>
			<div class="fact">
				<span class="fact-label is-sd">Najbliższe Silent Disco</span>
				<span class="fact-title"><?php echo esc_html( miriam_ucfirst( miriam_weekday( $next_sd['start'] ) ) . ', ' . miriam_short_date( $next_sd['start'] ) ); ?></span>
				<span class="fact-meta"><?php echo esc_html( trim( $next_sd['time'] . ' · ' . $next_sd['place'], ' ·' ) ); ?></span>
			</div>
			<?php endif; ?>
			<?php if ( $next_c ) : $cp = miriam_range_parts( $next_c['start'], $next_c['end'] ); ?>
			<div class="fact">
				<span class="fact-label">Najbliższy kurs</span>
				<span class="fact-title"><?php echo esc_html( $next_c['title'] ); ?></span>
				<span class="fact-meta"><?php echo esc_html( $cp[0] . ' ' . $cp[1] ); ?> · <a href="#kursy">zapisy</a></span>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="sec sd" id="silent-disco">
	<div class="wrap">
		<div class="sec-head">
			<div>
				<p class="kicker"><?php echo miriam_icon( 'headphones', 26 ); ?>Uwielbienie Silent Disco</p>
				<h2 class="display h-lg">Duch Święty to najlepszy DJ.<br><span style="color: var(--sd-pink)">Zna Twój rytm.</span></h2>
			</div>
			<p>Uwielbienie w słuchawkach: muzyka, światło, krótka konferencja i adoracja. Jesteśmy razem, a Bóg mówi do każdego serca osobno. Raz w miesiącu w niedzielę o 20:00, w górnym kościele św. Józefa — od 2020 roku.</p>
		</div>

		<div class="sd-grid">
			<figure class="sd-poster">
				<img src="<?php echo esc_url( $sd_poster ); ?>" alt="Plakat Silent Disco" loading="lazy">
				<?php if ( $next_sd ) : ?>
					<figcaption>Najbliższe · <?php echo esc_html( miriam_weekday( $next_sd['start'] ) . ' ' . miriam_short_date( $next_sd['start'] ) . ', ' . $next_sd['time'] ); ?></figcaption>
				<?php endif; ?>
			</figure>

			<div class="sd-dates">
				<span class="fact-label">Najbliższe terminy</span>
				<?php if ( $sd ) : ?>
					<ul class="date-grid">
						<?php foreach ( array_slice( $sd, 0, 9 ) as $i => $ev ) : $ts = strtotime( $ev['start'] . ' 12:00:00' ); ?>
							<li class="<?php echo 0 === $i ? 'is-next' : ''; ?>">
								<strong><?php echo esc_html( gmdate( 'j', $ts ) ); ?></strong>
								<span><?php echo esc_html( $months_s[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
					<p class="sd-note">Zawsze o 20:00 w górnym kościele, ul. Filaretów 7. Wcześniej, o 19:00, Msza Święta.</p>
					<a class="btn btn-white btn-sm" href="<?php echo esc_url( miriam_ics_url( 'silent-disco' ) ); ?>"><?php echo miriam_icon( 'calendar', 18 ); ?>Dodaj terminy do kalendarza</a>
				<?php else : ?>
					<p class="sd-note">Nowe terminy wkrótce — śledź nas na <a href="<?php echo esc_url( $l['instagram'] ); ?>">Instagramie</a>.</p>
				<?php endif; ?>
			</div>

			<?php if ( $feat_id ) : ?>
				<div class="sd-video" id="posluchaj"><?php echo miriam_youtube( $feat_id, $feat_tit ); // phpcs:ignore ?></div>
			<?php endif; ?>
			<a class="sd-stat" href="<?php echo esc_url( $l['youtube'] ); ?>" target="_blank" rel="noopener">
				<strong>2 mln+</strong>
				<span>wyświetleń jednej pieśni z naszych uwielbień na YouTube</span>
				<span class="sd-stat-link">Zobacz kanał →</span>
			</a>
		</div>
		<?php
		$grid = array();
		foreach ( $videos as $v ) {
			if ( $v['id'] !== $feat_id ) {
				$grid[] = $v;
			}
		}
		$grid = array_slice( $grid, 0, 3 );
		if ( $grid ) :
			?>
			<div class="sd-more">
				<div class="sd-more-head">
					<h3>Zobacz, jak u nas jest</h3>
					<div class="btn-row">
						<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( $l['instagram'] ); ?>"><?php echo miriam_icon( 'instagram', 18 ); ?>Rolki na Instagramie</a>
						<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( $l['youtube'] ); ?>">Wszystkie filmy</a>
					</div>
				</div>
				<div class="videos"><?php foreach ( $grid as $v ) { echo miriam_youtube( $v['id'], $v['title'] ); } // phpcs:ignore ?></div>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="sec music" id="muzyka">
	<div class="wrap">
		<div class="music-main">
			<p class="kicker">Muzyka Miriam</p>
			<h2 class="display h-md">Uwielbienie, które zabierzesz ze sobą.</h2>
			<p class="lead" style="color:#E4E9FA">Pieśni nagrane na żywo podczas uwielbień, w klimatycznych aranżacjach, które pomagają się skupić i modlić — w autobusie, na spacerze, w ciszy pokoju.</p>
			<div class="btn-row">
				<a class="btn btn-white btn-sm" href="<?php echo esc_url( $l['spotify'] ); ?>">Słuchaj na Spotify</a>
				<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( $l['youtube'] ); ?>">Kanał YouTube</a>
			</div>
		</div>
		<div class="music-side">
			<?php $sp = preg_replace( '/[^A-Za-z0-9]/', '', miriam_opt( 'spotify_id' ) ); if ( $sp ) : ?>
				<?php echo miriam_embed_gate( 'https://open.spotify.com/embed/artist/' . $sp . '?utm_source=generator&theme=0', 'Muzyka LSE Miriam na Spotify', 'Włącz odtwarzacz Spotify', 'Odtwarzacz ładuje się ze Spotify dopiero po kliknięciu.', 380, 'is-spotify' ); // phpcs:ignore ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="sec gallery-sec" id="galeria">
	<div class="wrap">
		<div class="sec-head">
			<div>
				<p class="kicker">Tak u nas jest</p>
				<h2 class="display h-md">Tu jest Twoje miejsce.</h2>
			</div>
			<p>Uwielbienie, adoracja, rozmowy do późna i wspólne wyjazdy. Więcej zdjęć i rolek wrzuca nasza ekipa medialna na Instagram.</p>
		</div>
		<div class="gallery">
			<?php
			$gal = array(
				'wspolnota'  => 'Wspólnota po wtorkowym spotkaniu',
				'muzyka'     => 'Zespół uwielbienia przy klawiszach',
				'adoracja'   => 'Adoracja Najświętszego Sakramentu',
				'jezus-zyje' => 'Grupa w koszulkach „Jezus żyje”',
				'sd-tekst'   => 'Kościół św. Józefa podczas uwielbienia',
				'twoje-miejsce' => 'Kartka z napisem „Tu jest Twoje miejsce”',
			);
			foreach ( $gal as $g => $alt ) :
				?>
				<figure class="gallery-item"><img src="<?php echo esc_url( miriam_photo( $g ) ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" width="1200" height="800"></figure>
			<?php endforeach; ?>
		</div>
		<a class="btn btn-ghost btn-sm" style="margin-top:24px" href="<?php echo esc_url( $l['instagram'] ); ?>"><?php echo miriam_icon( 'instagram', 18 ); ?>Więcej na Instagramie</a>
	</div>
</section>

<section class="sec" id="uzdrowienie">
	<div class="wrap">
		<div class="heal-card has-photo" style="<?php echo esc_attr( miriam_photo_style( 'uzdrowienie' ) ); ?>">
			<div class="heal-copy">
				<p class="kicker">Raz w miesiącu · wtorek 19:00 · dolny kościół</p>
				<h2 class="display h-md">Msza z modlitwą o uzdrowienie.</h2>
				<p class="lead">Otwarta Msza Święta z mocną modlitwą charyzmatyczną i uwielbieniem. Przyjdź ze swoją chorobą, zranieniem, pytaniem — i ze swoimi bliskimi. Jezus wciąż uzdrawia.</p>
				<div class="btn-row">
					<?php if ( $heal ) : ?>
						<a class="btn btn-orange" href="<?php echo esc_url( miriam_ics_url( 'uzdrowienie' ) ); ?>"><?php echo miriam_icon( 'calendar', 18 ); ?>Dodaj do kalendarza</a>
					<?php endif; ?>
					<a class="btn btn-ghost" href="#dolacz">Jak dotrzeć</a>
				</div>
			</div>
			<?php if ( $heal ) : ?>
				<ul class="heal-dates">
					<?php foreach ( array_slice( $heal, 0, 4 ) as $ev ) : $ts = strtotime( $ev['start'] . ' 12:00:00' ); $mg = miriam_months(); ?>
						<li>
							<strong><?php echo esc_html( gmdate( 'j', $ts ) . ' ' . $mg[ (int) gmdate( 'n', $ts ) ] ); ?></strong>
							<span><?php echo esc_html( miriam_weekday( $ev['start'] ) . ' · ' . ( $ev['time'] ? $ev['time'] : '19:00' ) . ( $ev['place'] ? ' · ' . $ev['place'] : '' ) ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p class="heal-empty">Najbliższe terminy podamy wkrótce. Znajdziesz je tutaj i na naszym <a href="<?php echo esc_url( $l['instagram'] ); ?>">Instagramie</a>.</p>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="sec" style="padding-top:0"><div class="wrap"><?php echo miriam_prayer_form(); // phpcs:ignore ?></div></section>

<div class="light">

	<section class="sec" id="pierwszy-raz">
		<div class="wrap">
			<div class="sec-head">
				<h2 class="display h-md">Pierwszy raz? Nie musisz być „gotowy”.</h2>
				<p>Nie musisz znać pieśni ani wiedzieć, jak się zachować. Przyjdź taki, jaki jesteś.</p>
			</div>
			<?php echo miriam_verse( 'Zróbcie wszystko, cokolwiek wam powie', 'J 2,5' ); // phpcs:ignore ?>
			<div class="cards-3">
				<div class="card"><span class="num">01</span><h3>Przyjdź we wtorek</h3><p>O 19:00 do dolnego kościoła przy Filaretów 7. Wejście z tyłu kościoła, od ul. Rymwida (od strony kładki).</p></div>
				<div class="card"><span class="num">02</span><h3>Pozwól sobie być</h3><p>Śpiewamy, modlimy się, słuchamy konferencji. Możesz się włączyć albo po prostu posłuchać.</p></div>
				<div class="card"><span class="num">03</span><h3>Poznaj ludzi</h3><p>Podejdź do kogoś z nas, zapytaj o cokolwiek. Nie musisz przechodzić tej drogi sam.</p></div>
			</div>
			<a class="btn btn-blue" href="<?php echo esc_url( $first_url ); ?>">Czego się spodziewać</a>
		</div>
	</section>

	<?php
	$stories = array_slice( miriam_get_stories(), 0, 3 );
	$quote   = miriam_opt( 'story_quote' );
	$author  = miriam_opt( 'story_author' );
	if ( $stories || $quote ) :
		?>
	<section class="sec stories" id="historie">
		<div class="wrap">
			<div class="sec-head">
				<div>
					<p class="kicker" style="margin-bottom:14px">Historie spotkania</p>
					<h2 class="display h-md">Za wiarą stoją ludzie.</h2>
				</div>
				<p>Co Bóg dał nam przez wspólnotę — naszymi słowami.</p>
			</div>
			<div class="quotes-3">
				<?php if ( $quote ) : ?>
					<figure class="quote-card"><blockquote>„<?php echo esc_html( $quote ); ?>”</blockquote><?php if ( $author ) : ?><figcaption><?php echo esc_html( $author ); ?></figcaption><?php endif; ?></figure>
				<?php endif; ?>
				<?php foreach ( array_slice( $stories, 0, $quote ? 2 : 3 ) as $st ) : ?>
					<figure class="quote-card">
						<blockquote>„<?php echo esc_html( miriam_story_quote( $st['text'], 150 ) ); ?>”</blockquote>
						<figcaption><span class="story-avatar" aria-hidden="true"><?php echo esc_html( mb_substr( $st['name'], 0, 1 ) ); ?></span><?php echo esc_html( $st['name'] ); ?></figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
			<?php if ( $story_url ) : ?>
				<a class="btn btn-outline" href="<?php echo esc_url( $story_url ); ?>">Przeczytaj całe historie</a>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="sec" id="kursy">
		<div class="wrap">
			<div class="sec-head">
				<h2 class="display h-md">Zrób krok w głąb.</h2>
				<p>Kursy Szkoły Ewangelizacji św. Andrzeja (SESA) — kilka dni, które potrafią zmienić życie.</p>
			</div>
			<?php echo miriam_verse( 'A co usłyszałeś ode mnie za pośrednictwem wielu świadków, to przekaż ludziom godnym zaufania, którzy będą zdolni i innych nauczać', '2 Tm 2,2' ); // phpcs:ignore ?>
			<?php if ( $courses ) : ?>
				<div class="courses">
					<?php foreach ( array_slice( $courses, 0, 4 ) as $c ) { echo miriam_course_row( $c ); } // phpcs:ignore ?>
				</div>
				<a class="btn btn-outline" style="margin-top:24px" href="<?php echo esc_url( miriam_page_url( array( 'kursy-i-formacja', 'jakie-kursy-sa-dla-mnie' ), home_url( '/#kursy' ) ) ); ?>">Wszystkie kursy<?php echo count( $courses ) > 4 ? ' (' . (int) count( $courses ) . ')' : ''; ?> i który wybrać</a>
			<?php else : ?>
				<p>Nowe terminy kursów wkrótce.</p>
			<?php endif; ?>
		</div>
	</section>

</div>

<?php get_template_part( 'template-parts/connect' ); ?>

<?php get_template_part( 'template-parts/support' ); ?>

<section class="sec join glow" id="dolacz">
	<div class="wrap">
		<div class="join-copy">
			<h2 class="display h-xl" style="font-size:clamp(44px,6.4vw,92px)">Do zobaczenia<br><span class="accent">we wtorek.</span></h2>
			<p class="lead">Dolny kościół parafii św. Józefa, ul. Filaretów 7, Lublin. Wejście z tyłu kościoła, od ul. Rymwida (od strony kładki).</p>
			<?php echo miriam_verse( 'Idźcie więc i czyńcie uczniów ze wszystkich narodów', 'Mt 28,19' ); // phpcs:ignore ?>
			<div class="btn-row">
				<a class="btn btn-orange" href="<?php echo esc_url( $l['maps'] ); ?>"><?php echo miriam_icon( 'pin', 18 ); ?>Prowadź mnie</a>
				<?php $email = miriam_opt( 'email' ); if ( $email ) : ?>
					<a class="btn btn-ghost" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>">Napisz do nas</a>
				<?php endif; ?>
			</div>
		</div>
		<div class="join-map"><?php echo miriam_map_gate(); // phpcs:ignore ?></div>
	</div>
</section>

</main>

<?php
get_footer();
