<?php
/**
 * Kontakt i dojazd.
 */
$l     = miriam_links();
$email = miriam_opt( 'email' );
$phone = miriam_opt( 'phone' );
?>
<main id="main">
	<section class="page-hero glow has-photo" style="<?php echo esc_attr( miriam_photo_style( 'sd-kosciol' ) ); ?>">
		<div class="wrap" style="display:flex;flex-direction:column;gap:22px">
			<p class="kicker">Kontakt i dojazd</p>
			<h1 class="display">Napisz, zadzwoń<br><span class="accent">albo po prostu przyjdź.</span></h1>
		</div>
	</section>

	<div class="light">
		<section class="sec">
			<div class="wrap">
				<div class="cards-3" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr))">
					<div class="card">
						<span class="fact-label" style="color:var(--blue)">Spotkania</span>
						<h3>Wtorki, 19:00</h3>
						<p>Dolny kościół parafii św. Józefa<br>ul. Filaretów 7, 20-609 Lublin</p>
						<p style="font-size:15px">Wejście z tyłu kościoła, od ul. Rymwida (od strony kładki).</p>
						<a class="btn btn-blue btn-sm" href="<?php echo esc_url( $l['maps'] ); ?>" style="align-self:flex-start"><?php echo miriam_icon( 'pin', 18 ); ?>Jak dotrzeć</a>
					</div>
					<div class="card">
						<span class="fact-label" style="color:var(--blue)">Napisz lub zadzwoń</span>
						<?php if ( $email ) : ?><h3 style="font-size:22px;word-break:break-all"><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></h3><?php endif; ?>
						<?php if ( $phone ) : ?><h3 style="font-size:22px"><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></h3><p style="font-size:15px;margin-top:-6px">Biuro wspólnoty</p><?php endif; ?>
						<p>Odpowiadamy na pytania o spotkania, kursy i Silent Disco. Jeśli chcesz, żebyśmy się za Ciebie pomodlili — też napisz.</p>
					</div>
					<div class="card">
						<span class="fact-label" style="color:var(--blue)">Bądź blisko</span>
						<p><a href="<?php echo esc_url( $l['instagram'] ); ?>">Instagram @lse_miriam</a><br><a href="<?php echo esc_url( $l['facebook'] ); ?>">Facebook</a><br><a href="<?php echo esc_url( $l['youtube'] ); ?>">YouTube</a><br><a href="<?php echo esc_url( $l['spotify'] ); ?>">Spotify</a></p>
						<p style="font-size:15px">Zmiany miejsca i terminów ogłaszamy na Instagramie i Facebooku.</p>
					</div>
				</div>
			</div>
		</section>

		<section class="sec stories">
			<div class="wrap">
				<div class="join-map" style="min-height:420px"><?php echo miriam_map_gate( 420 ); // phpcs:ignore ?></div>
			</div>
		</section>

		<section class="sec">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Wesprzyj nas</h2>
					<p>Uwielbienia, kursy i ewangelizacja są możliwe dzięki ludziom, którzy w nas wierzą. Możesz nas wspierać regularnie przez Patronite.</p>
				</div>
				<a class="btn btn-blue" href="<?php echo esc_url( $l['patronite'] ); ?>">Wesprzyj na Patronite</a>
			</div>
		</section>
	</div>
	<section class="sec" style="background:var(--navy)"><div class="wrap"><?php echo miriam_prayer_form(); // phpcs:ignore ?></div></section>
	<?php get_template_part( 'template-parts/connect' ); ?>
</main>
