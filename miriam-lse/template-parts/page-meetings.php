<?php
/**
 * Spotkania wspólnoty.
 */
$heal = miriam_get_events( 'uzdrowienie', 6 );
$mg   = miriam_months();
?>
<main id="main">
	<section class="page-hero glow">
		<div class="wrap" style="display:flex;flex-direction:column;gap:22px">
			<p class="kicker">Spotkania wspólnoty</p>
			<h1 class="display">Każdy wtorek, 19:00.<br><span class="accent">Przez cały rok.</span></h1>
			<p class="lead">Dolny kościół parafii św. Józefa, ul. Filaretów 7 w Lublinie — wejście z tyłu kościoła, od ul. Rymwida (od strony kładki). Otwarte dla każdego, bez zapisów i bez warunków.</p>
		</div>
	</section>

	<div class="light">
		<section class="sec">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Jak wyglądają spotkania?</h2>
					<p>Najważniejsza jest modlitwa i osobiste spotkanie z Bogiem. Wszystko inne temu służy.</p>
				</div>
				<div class="cards-3">
					<div class="card"><h3>Uwielbienie</h3><p>Śpiewamy i wielbimy Boga — głośno i w ciszy. Prowadzi nas zespół muzyczny wspólnoty.</p></div>
					<div class="card"><h3>Słowo</h3><p>Konferencja na temat związany z bieżącą formacją: jak żyć z Jezusem na co dzień.</p></div>
					<div class="card"><h3>Wspólnota</h3><p>Relacje, rozmowy, wzajemna modlitwa. Nie idziemy tą drogą w pojedynkę.</p></div>
				</div>
			</div>
		</section>

		<section class="sec stories" id="uzdrowienie">
			<div class="wrap">
				<div class="sec-head">
					<div>
						<p class="kicker" style="margin-bottom:14px">Raz w miesiącu</p>
						<h2 class="display h-md">Msza z modlitwą o uzdrowienie</h2>
					</div>
					<p>W jeden wtorek w miesiącu spotkanie w dolnym kościele jest otwartą Mszą Świętą z mocną modlitwą charyzmatyczną i uwielbieniem. Przyjdź ze swoją chorobą, zranieniem, pytaniem — i przyprowadź bliskich.</p>
				</div>
				<?php if ( $heal ) : ?>
					<div class="courses">
						<?php foreach ( $heal as $ev ) : $ts = strtotime( $ev['start'] . ' 12:00:00' ); ?>
							<div class="course" style="background:var(--light-bg)">
								<span class="course-date"><strong><?php echo esc_html( gmdate( 'j', $ts ) ); ?></strong><span><?php echo esc_html( $mg[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts ) ); ?></span></span>
								<span class="course-title"><?php echo esc_html( miriam_ucfirst( miriam_weekday( $ev['start'] ) ) . ', ' . ( $ev['time'] ? $ev['time'] : '19:00' ) ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p style="margin:0;color:var(--ink-soft)">Najbliższe terminy podamy wkrótce.</p>
				<?php endif; ?>
			</div>
		</section>

		<section class="sec">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Wychodzimy na ulice</h2>
					<p>Wiarą w Zmartwychwstałego Jezusa dzielimy się także poza kościołem — na ulicach Lublina, w parafiach, podczas uwielbień Silent Disco. Jeśli chcesz się włączyć, przyjdź we wtorek i powiedz nam o tym.</p>
				</div>
			</div>
		</section>
	</div>

	<section class="sec" style="background:var(--navy)"><div class="wrap"><?php echo miriam_prayer_form(); // phpcs:ignore ?></div></section>
	<?php get_template_part( 'template-parts/connect' ); ?>
	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
