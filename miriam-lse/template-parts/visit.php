<?php
/**
 * Pasek zaproszenia na dole podstron.
 */
$l = miriam_links();
?>
<section class="sec join glow" style="padding:88px 0">
	<div class="wrap">
		<div class="join-copy">
			<h2 class="display h-md">Do zobaczenia<br><span class="accent">we wtorek o 19:00.</span></h2>
			<p class="lead">Dolny kościół parafii św. Józefa, ul. Filaretów 7, Lublin. Wejście z tyłu kościoła, od ul. Rymwida (od strony kładki).</p>
			<div class="btn-row">
				<a class="btn btn-orange" href="<?php echo esc_url( $l['maps'] ); ?>"><?php echo miriam_icon( 'pin', 18 ); ?>Jak dotrzeć</a>
				<a class="btn btn-ghost" href="<?php echo esc_url( miriam_section_url( 'first', home_url( '/#pierwszy-raz' ) ) ); ?>">Pierwszy raz?</a>
			</div>
		</div>
	</div>
</section>
