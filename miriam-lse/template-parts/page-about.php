<?php
/**
 * O nas: historia, charyzmat, pasterz i odpowiedzialni.
 */
$story = miriam_section_url( 'stories' );
?>
<main id="main">
	<section class="page-hero glow has-photo" style="<?php echo esc_attr( miriam_photo_style( 'wspolnota' ) ); ?>">
		<div class="wrap" style="display:flex;flex-direction:column;gap:22px">
			<p class="kicker">O nas</p>
			<h1 class="display">Uczniowie Jezusa,<br><span class="accent">którzy prowadzą innych do Niego.</span></h1>
			<p class="lead">Lubelska Szkoła Ewangelizacji Miriam jest częścią Międzynarodowej Szkoły Ewangelizacji św. Andrzeja (SESA) i Katolickiej Odnowy w Duchu Świętym Archidiecezji Lubelskiej.</p>
			<div class="btn-row"><a class="btn btn-ghost btn-sm" href="#charyzmat">Charyzmat</a><a class="btn btn-ghost btn-sm" href="#historia">Historia</a><a class="btn btn-ghost btn-sm" href="#pasterz">Pasterz i odpowiedzialni</a></div>
		</div>
	</section>

	<div class="light">
		<section class="sec" id="charyzmat">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Charyzmat</h2>
					<p>Chcemy być uczniami, którzy każdego dnia idą za Jezusem jako Panem i Zbawicielem, i Jego świadkami wszędzie, gdzie jesteśmy. Wzorem jest dla nas Maryja — pierwsza i najwierniejsza uczennica.</p>
				</div>
				<?php echo miriam_verse( 'Nie możemy być spokojni, myśląc o milionach naszych braci i sióstr, również odkupionych krwią Chrystusa, którzy żyją, nie znając miłości Boga', 'św. Jan Paweł II, Redemptoris missio 86' ); // phpcs:ignore ?>
				<div class="cards-3" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
					<div class="card"><span class="num">01</span><h3>Uwielbienie</h3><p>Modlitwa, która stawia Boga w centrum.</p></div>
					<div class="card"><span class="num">02</span><h3>Ewangelizacja</h3><p>Głoszenie Jezusa na wiele sposobów.</p></div>
					<div class="card"><span class="num">03</span><h3>Uczniostwo</h3><p>Formacja, która przemienia życie.</p></div>
					<div class="card"><span class="num">04</span><h3>Relacje</h3><p>Braterstwo i siostrzeństwo na co dzień.</p></div>
					<div class="card"><span class="num">05</span><h3>Służba</h3><p>Dary dane po to, by służyć innym.</p></div>
				</div>
			</div>
		</section>

		<section class="sec stories" id="historia">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Historia</h2>
					<p>Od małej grupy w Lubartowie do wspólnoty w Lublinie.</p>
				</div>
				<div class="courses">
					<?php
					$timeline = array(
						array( '2016', 'Lubartów', 'Ks. Krzysztof Krzaczek, zainspirowany kursami Szkoły Ewangelizacji św. Andrzeja, gromadzi pierwszych uczniów. W kwietniu odbywa się pierwszy kurs — przy wsparciu Wspólnoty Miriam „Brama Niebios” z Lubartowa i Odnowy w Duchu Świętym ze Świdnika.' ),
						array( '2017', 'Uznanie', 'Szkoła podpisuje porozumienie z SESA Polska, a abp Stanisław Budzik wydaje dekret potwierdzający jej działalność.' ),
						array( '2019', 'Lublin', 'Po Seminarium Odnowy Wiary w parafii św. Józefa rodzi się Wspólnota Lubelskiej Szkoły Ewangelizacji Miriam.' ),
						array( '2020', 'Silent Disco', 'Startują comiesięczne uwielbienia Silent Disco, a nagrania z nich docierają do milionów słuchaczy.' ),
						array( 'Dziś', 'Wspólnota', 'Kilkadziesiąt osób, wtorkowe spotkania, kursy SESA we współpracy z zaprzyjaźnionymi wspólnotami i ewangelizacja na ulicach Lublina.' ),
					);
					foreach ( $timeline as $t ) :
						?>
						<div class="course" style="background:var(--light-bg);align-items:flex-start">
							<span class="course-date"><strong><?php echo esc_html( $t[0] ); ?></strong><span><?php echo esc_html( $t[1] ); ?></span></span>
							<span style="flex:1 1 360px;color:var(--ink-soft)"><?php echo esc_html( $t[2] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="sec" id="pasterz">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Pasterz i odpowiedzialni</h2>
					<p>Za życie wspólnoty odpowiada Rada Wspólnoty, na której czele stoją Pasterz i Liderzy.</p>
				</div>
				<div class="cards-3" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
					<div class="card"><span class="fact-label" style="color:var(--blue)">Pasterz i dyrektor Szkoły</span><h3>ks. Krzysztof Krzaczek</h3></div>
					<div class="card"><span class="fact-label" style="color:var(--blue)">Liderzy wspólnoty</span><h3>Arek Borzęcki<br>Amadeusz Świta</h3></div>
					<div class="card"><span class="fact-label" style="color:var(--blue)">Organizacja kursów SESA</span><h3>Wiktor Terelak</h3></div>
					<div class="card"><span class="fact-label" style="color:var(--blue)">Rada Wspólnoty</span><p style="color:var(--ink);font-weight:500">Katarzyna Borzęcka, Sylwia Krzemińska, Agata Nawrocka, Paweł Pyszel, Aleksandra Terelak</p></div>
				</div>
				<?php if ( $story ) : ?>
					<a class="btn btn-blue" href="<?php echo esc_url( $story ); ?>">Poznaj historie naszych ludzi</a>
				<?php endif; ?>
			</div>
		</section>
	</div>

	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
