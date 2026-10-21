<?php
/**
 * Pierwszy raz?
 */
$l = miriam_links();
?>
<main id="main">
	<section class="page-hero glow has-photo" style="<?php echo esc_attr( miriam_photo_style( 'grupa' ) ); ?>">
		<div class="wrap" style="display:flex;flex-direction:column;gap:22px">
			<p class="kicker">Pierwszy raz?</p>
			<h1 class="display">Nie musisz mieć poukładanego życia,<br><span class="accent">żeby spotkać Boga.</span></h1>
			<p class="lead">Wystarczy przyjść. Nie trzeba znać pieśni ani wiedzieć, jak się zachować. Poniżej znajdziesz wszystko, czego możesz się spodziewać.</p>
		</div>
	</section>

	<div class="light">
		<section class="sec">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Dla kogo jest Miriam?</h2>
					<p>Dla każdego, kto szuka. Bez warunków wstępnych. Większość z nas to młodzi ludzie, ale są wśród nas osoby w każdym wieku.</p>
				</div>
				<div class="cards-3" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
					<div class="card"><h3>Wracasz do wiary</h3><p>Po przerwie, po latach, po trudnych doświadczeniach — i nie wiesz, od czego zacząć.</p></div>
					<div class="card"><h3>Szukasz wspólnoty</h3><p>Miejsca, gdzie można się modlić, rozmawiać i wzrastać razem z innymi.</p></div>
					<div class="card"><h3>Chcesz czegoś żywego</h3><p>Jesteś zmęczony religijnością bez doświadczenia i szukasz osobistego spotkania z Jezusem.</p></div>
					<div class="card"><h3>Chcesz służyć</h3><p>Uczyć się modlitwy i ewangelizacji, brać odpowiedzialność za swoją wiarę i innych.</p></div>
				</div>
			</div>
		</section>

		<section class="sec stories">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Jak wygląda wtorek?</h2>
					<p>Zaczynamy o 19:00. Możesz włączyć się we wszystko albo po prostu być i słuchać.</p>
				</div>
				<div class="cards-3">
					<div class="card" style="background:var(--light-bg)"><span class="num">01</span><h3>Uwielbienie</h3><p>Śpiewamy i modlimy się — głośno i w ciszy. Nie musisz znać pieśni, szybko je podłapiesz.</p></div>
					<div class="card" style="background:var(--light-bg)"><span class="num">02</span><h3>Konferencja</h3><p>Krótkie nauczanie o Bogu i życiu wiarą — temat zależy od bieżącej formacji wspólnoty.</p></div>
					<div class="card" style="background:var(--light-bg)"><span class="num">03</span><h3>Rozmowy</h3><p>Zostań chwilę. Podejdź do kogoś z nas, zapytaj o cokolwiek — chętnie Cię poznamy.</p></div>
				</div>
				<p style="margin:0;color:var(--ink-soft)">Raz w miesiącu wtorkowe spotkanie to otwarta Msza Święta z modlitwą o uzdrowienie. <a href="<?php echo esc_url( home_url( '/#uzdrowienie' ) ); ?>">Zobacz terminy</a>.</p>
			</div>
		</section>

		<section class="sec">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Jak do nas trafić?</h2>
					<p>Dolny kościół parafii św. Józefa, ul. Filaretów 7 w Lublinie. Wejście z tyłu kościoła, od ul. Rymwida (od strony kładki).</p>
				</div>
				<div class="btn-row">
					<a class="btn btn-blue" href="<?php echo esc_url( $l['maps'] ); ?>"><?php echo miriam_icon( 'pin', 18 ); ?>Otwórz w mapach</a>
					<a class="btn btn-outline" href="<?php echo esc_url( $l['instagram'] ); ?>">Zmiany ogłaszamy na Instagramie</a>
				</div>
			</div>
		</section>

		<section class="sec stories">
			<div class="wrap" style="max-width:900px">
				<h2 class="display h-md" style="margin-bottom:32px">Często pytacie</h2>
				<?php
				$faq = array(
					array( 'Czy spotkania są dla każdego?', 'Tak. Nie ma warunków wstępnych. Możesz przyjść także wtedy, gdy dopiero szukasz swojej drogi wiary albo masz więcej pytań niż odpowiedzi.' ),
					array( 'Czy muszę znać pieśni i sposób modlitwy?', 'Nie. Możesz po prostu słuchać, a w modlitwie uczestniczyć tak, jak czujesz się swobodnie.' ),
					array( 'Czy spotkania odbywają się w wakacje?', 'Tak, spotykamy się przez cały rok. Gdy spotkanie odbywa się w innym miejscu, informujemy o tym w mediach społecznościowych.' ),
					array( 'Czy mogę przyjść z kimś?', 'Oczywiście — przyprowadź przyjaciela, rodzeństwo, kogokolwiek. We dwoje łatwiej zrobić pierwszy krok.' ),
					array( 'Co dalej, jeśli mi się spodoba?', 'Przychodź we wtorki, a gdy będziesz gotowy, zapisz się na kurs Nowe Życie — to najlepszy pierwszy krok w formacji.' ),
				);
				foreach ( $faq as $q ) :
					?>
					<details class="faq">
						<summary><?php echo esc_html( $q[0] ); ?></summary>
						<p><?php echo esc_html( $q[1] ); ?></p>
					</details>
				<?php endforeach; ?>
			</div>
		</section>
	</div>

	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
