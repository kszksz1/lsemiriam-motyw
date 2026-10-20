<?php
/**
 * Stopka.
 */
$l     = miriam_links();
$email = miriam_opt( 'email' );
$phone = miriam_opt( 'phone' );
?>
<footer class="site-footer" id="o-nas">
	<div class="wrap cols">
		<div class="footer-about">
			<img class="footer-logo" src="<?php echo esc_url( miriam_asset( 'logo-white.webp' ) ); ?>" alt="Miriam — Lubelska Szkoła Ewangelizacji" width="300" height="133" loading="lazy">
			<span>Część Międzynarodowej Szkoły Ewangelizacji św. Andrzeja (SESA) i Centrum Duszpasterstwa Młodzieży w Lublinie.</span>
			<span>Spotkania: wtorki, 19:00 · dolny kościół parafii św. Józefa, ul. Filaretów 7, Lublin</span>
		</div>
		<ul class="footer-links">
			<li><a href="<?php echo esc_url( $l['instagram'] ); ?>">Instagram</a></li>
			<li><a href="<?php echo esc_url( $l['youtube'] ); ?>">YouTube</a></li>
			<li><a href="<?php echo esc_url( $l['spotify'] ); ?>">Spotify</a></li>
			<li><a href="<?php echo esc_url( $l['facebook'] ); ?>">Facebook</a></li>
			<li><a href="<?php echo esc_url( $l['patronite'] ); ?>">Wesprzyj nas na Patronite</a></li>
			<?php if ( $email ) : ?><li><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></li><?php endif; ?>
			<?php if ( $phone ) : ?><li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
		</ul>
	</div>
	<div class="wrap footer-bottom">
		© <?php echo esc_html( wp_date( 'Y' ) ); ?> LSE Miriam
		<?php
		$privacy = miriam_privacy_url();
		if ( $privacy ) {
			echo ' · <a href="' . esc_url( $privacy ) . '">Polityka prywatności</a>';
		}
		?>
	</div>
</footer>

<nav class="mobile-bar" aria-label="Szybkie linki">
	<a href="<?php echo esc_url( home_url( '/#dolacz' ) ); ?>">Wtorek 19:00</a>
	<a href="<?php echo esc_url( $l['maps'] ); ?>">Jak dotrzeć</a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
