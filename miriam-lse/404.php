<?php
/**
 * Nie znaleziono.
 */
get_header();
?>
<main id="main">
	<section class="sec glow" style="min-height:60vh">
		<div class="wrap" style="display:flex;flex-direction:column;gap:24px">
			<p class="kicker">Błąd 404</p>
			<h1 class="display h-lg">Tej strony tu nie ma.<br><span class="accent">Ale Ty jesteś mile widziany.</span></h1>
			<div class="btn-row"><a class="btn btn-orange" href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a></div>
		</div>
	</section>
</main>
<?php
get_footer();
