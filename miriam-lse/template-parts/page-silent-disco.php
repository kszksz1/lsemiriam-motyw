<?php
/**
 * Uwielbienie Silent Disco.
 */
$l        = miriam_links();
$sd       = miriam_get_events( 'silent-disco', 12 );
$months_s = miriam_months( 'short' );
$feat_id  = miriam_yt_id( miriam_opt( 'featured_video' ) );
$poster   = miriam_img_url( 'sd_poster' );
$poster   = $poster ? $poster : miriam_asset( 'silent-disco.webp' );
$videos   = miriam_latest_videos( 7 );
?>
<main id="main">
	<div class="sd-band" style="<?php echo esc_attr( miriam_photo_style( 'sd-kosciol' ) ); ?>" role="img" aria-label="Górny kościół św. Józefa w niebieskim świetle podczas uwielbienia"></div>
	<section class="sec sd" style="padding-top:64px">
		<div class="wrap">
			<div class="sec-head">
				<div>
					<p class="kicker"><?php echo miriam_icon( 'headphones', 26 ); ?>Uwielbienie Silent Disco</p>
					<h1 class="display h-lg">Duch Święty to najlepszy DJ.<br><span style="color: var(--sd-pink)">Zna Twój rytm.</span></h1>
				</div>
				<p>Jesteśmy razem, a w słuchawkach każdy słyszy to, co Bóg mówi właśnie do jego serca. Muzyka, światło, krótka konferencja, adoracja Najświętszego Sakramentu i zaproszenie, by oddać życie Jezusowi.</p>
			</div>

			<div class="sd-grid">
				<figure class="sd-poster"><img src="<?php echo esc_url( $poster ); ?>" alt="Plakat Silent Disco"></figure>
				<div class="sd-dates">
					<span class="fact-label">Terminy</span>
					<?php if ( $sd ) : ?>
						<ul class="date-grid">
							<?php foreach ( array_slice( $sd, 0, 9 ) as $i => $ev ) : $ts = strtotime( $ev['start'] . ' 12:00:00' ); ?>
								<li class="<?php echo 0 === $i ? 'is-next' : ''; ?>"><strong><?php echo esc_html( gmdate( 'j', $ts ) ); ?></strong><span><?php echo esc_html( $months_s[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts ) ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<p class="sd-note">Niedziela: 19:00 Msza Święta, 20:00 Silent Disco. Górny kościół św. Józefa, ul. Filaretów 7. Wstęp wolny.</p>
					<a class="btn btn-white btn-sm" href="<?php echo esc_url( add_query_arg( 'miriam_ics', 'silent-disco', home_url( '/' ) ) ); ?>"><?php echo miriam_icon( 'calendar', 18 ); ?>Dodaj do kalendarza</a>
				</div>
				<?php if ( $feat_id ) : ?><div class="sd-video"><?php echo miriam_youtube( $feat_id, miriam_opt( 'featured_title' ) ); // phpcs:ignore ?></div><?php endif; ?>
				<a class="sd-stat" href="<?php echo esc_url( $l['youtube'] ); ?>" target="_blank" rel="noopener"><strong>2 mln+</strong><span>wyświetleń jednej pieśni z naszych uwielbień na YouTube</span><span class="sd-stat-link">Zobacz kanał →</span></a>
			</div>
		</div>
	</section>

	<section class="sec" style="background:#2A1A4D;padding-bottom:40px">
		<div class="wrap">
			<div class="sec-head">
				<h2 class="display h-md">Jak wygląda Silent Disco?</h2>
				<p style="color:#E6DEF2">Pierwszy raz? Nie musisz nic umieć ani niczego przygotowywać. Załóż słuchawki i pozwól, żeby muzyka pomogła Ci wyciszyć wszystko inne.</p>
			</div>
			<ol class="sd-steps">
				<li><strong>Modlitwa o Ducha Świętego</strong><span>Zaczynamy od zaproszenia Go, żeby to On prowadził ten wieczór.</span></li>
				<li><strong>Uwielbienie w słuchawkach</strong><span>Pieśni w klimatycznych aranżacjach i światło, które pomagają się skupić.</span></li>
				<li><strong>Krótka konferencja</strong><span>Słowo, które porusza i wyjaśnia, Kim jest Bóg dla Ciebie.</span></li>
				<li><strong>Adoracja</strong><span>Cisza przed Jezusem w Najświętszym Sakramencie.</span></li>
				<li><strong>Twoja decyzja</strong><span>Zaproszenie, by — jeśli chcesz — oddać swoje życie Jezusowi.</span></li>
			</ol>
		</div>
	</section>

	<section class="sec" style="background:#2A1A4D;padding-top:40px">
		<div class="wrap">
			<div class="sec-head">
				<h2 class="display h-md">Historia</h2>
				<p style="color:#E6DEF2">Uwielbienia Silent Disco odbywają się co miesiąc od września 2020 roku. Przez trzy lata służyliśmy w parafii św. Józefa, w sezonie 2023/24 ruszyliśmy w Silent Disco Tour po parafiach Archidiecezji Lubelskiej, a od 2024 roku znów spotykamy się u św. Józefa. Większość uwielbień jest nagrywana — pieśni znajdziesz na YouTube i Spotify.</p>
			</div>
			<?php
			$grid = array();
			foreach ( $videos as $v ) {
				if ( $v['id'] !== $feat_id ) {
					$grid[] = $v;
				}
			}
			if ( $grid ) :
				?>
				<div class="videos"><?php foreach ( array_slice( $grid, 0, 6 ) as $v ) { echo miriam_youtube( $v['id'], $v['title'] ); } // phpcs:ignore ?></div>
			<?php endif; ?>
			<div class="btn-row" style="margin-top:32px">
				<a class="btn btn-white btn-sm" href="<?php echo esc_url( $l['spotify'] ); ?>">Słuchaj na Spotify</a>
				<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( $l['youtube'] ); ?>">Kanał YouTube</a>
			</div>
		</div>
	</section>
</main>
