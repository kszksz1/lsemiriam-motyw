<?php
/**
 * Kursy SESA.
 */
$l       = miriam_links();
$courses = miriam_get_events( 'kurs', 20 );

$catalog = array(
	array( 'Nowe Życie', 'Jeśli chcesz zacząć od nowa', 'Najczęściej najlepszy pierwszy krok. Doświadczysz, że Bóg kocha, przebacza i daje nowy początek, a Ewangelia jest dobrą nowiną o Twoim życiu.', 'Dla osób, które wracają do wiary, czują rutynę albo chcą zaprosić Jezusa do swojego życia.' ),
	array( 'Emaus', 'Jeśli chcesz słuchać Boga w Biblii', 'Pismo Święte jako żywe Słowo, które mówi dziś — do Twoich decyzji, relacji i codzienności.', 'Dla osób, które chcą czytać Biblię głębiej albo szukają światła w rozeznawaniu.' ),
	array( 'Jan', 'Jeśli chcesz wejść głębiej w relację z Jezusem', 'Od wiary opartej na wiedzy czy emocjach do osobistej bliskości i uczniostwa.', 'Po kursie Emaus — dla tych, którzy pragną głębszej modlitwy i dojrzałej wiary.' ),
	array( 'Jezus w 4 Ewangeliach', 'Jeśli chcesz lepiej poznać Jezusa', 'Cztery Ewangelie, jeden Jezus: Pan, Nauczyciel, Zbawiciel i Przyjaciel, który wzywa do konkretnej odpowiedzi.', 'Po kursie Jan — dla tych, którzy chcą świadomiej głosić Jezusa.' ),
	array( 'Historia Zbawienia', 'Jeśli chcesz zobaczyć swoje życie w Bożym planie', 'Od stworzenia, przez przymierze, po Jezusa, Kościół i misję — i Twoje miejsce w tej historii.', 'Po kursie Jan — łączy Stary i Nowy Testament.' ),
	array( 'Mojżesz', 'Jeśli prowadzisz innych', 'Przywództwo jako służba, formacja serca i odpowiedzialność przed Bogiem.', 'Dla liderów, animatorów i osób rozeznających większą odpowiedzialność.' ),
);
?>
<main id="main">
	<section class="page-hero glow">
		<div class="wrap" style="display:flex;flex-direction:column;gap:22px">
			<p class="kicker">Kursy Szkoły Ewangelizacji św. Andrzeja</p>
			<h1 class="display">Kilka dni,<br><span class="accent">które zmieniają życie.</span></h1>
			<p class="lead">Kursy SESA to intensywne rekolekcje: głoszenie, modlitwa, praca w małych grupach, świadectwa i osobista decyzja wiary. Nie wykład — spotkanie z Bogiem, który kocha, zbawia, uzdrawia i posyła.</p>
			<div class="btn-row"><a class="btn btn-orange" href="#terminy">Najbliższe terminy</a><a class="btn btn-ghost" href="#ktory-kurs">Który kurs dla mnie?</a></div>
		</div>
	</section>

	<div class="light">
		<section class="sec" id="terminy">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Najbliższe terminy</h2>
					<p>Nie wiesz, od czego zacząć? Wybierz <strong>Nowe Życie</strong>.</p>
				</div>
				<?php if ( $courses ) : ?>
					<div class="courses">
						<?php foreach ( $courses as $c ) { echo miriam_course_row( $c ); } // phpcs:ignore ?>
					</div>
				<?php else : ?>
					<p>Nowe terminy wkrótce.</p>
				<?php endif; ?>
			</div>
		</section>

		<section class="sec stories" id="ktory-kurs">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Który kurs jest dla mnie?</h2>
					<p>Nie musisz znać całej drogi, żeby zrobić pierwszy krok. Liczy się to, czy chcesz zrobić kolejny.</p>
				</div>
				<div class="cards-3" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr))">
					<?php foreach ( $catalog as $i => $k ) : ?>
						<div class="card" style="background:var(--light-bg)">
							<span class="fact-label" style="color:var(--blue)"><?php echo esc_html( $k[1] ); ?></span>
							<h3><?php echo esc_html( $k[0] ); ?></h3>
							<p><?php echo esc_html( $k[2] ); ?></p>
							<p style="font-size:15px"><?php echo esc_html( $k[3] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="sec">
			<div class="wrap">
				<div class="sec-head">
					<h2 class="display h-md">Dla kogo są kursy?</h2>
					<p>Dla każdego, kto chce odnowić relację z Bogiem, wrócić do wiary, lepiej zrozumieć Ewangelię, znaleźć miejsce we wspólnocie, nauczyć się głosić Jezusa albo pogłębić modlitwę i kontakt ze Słowem.</p>
				</div>
				<p style="margin:0;color:var(--ink-soft)">Pytania o kursy? Napisz: <a href="mailto:<?php echo esc_attr( antispambot( miriam_opt( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( miriam_opt( 'email' ) ) ); ?></a></p>
			</div>
		</section>
	</div>

	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
