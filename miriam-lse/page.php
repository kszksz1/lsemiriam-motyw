<?php
/**
 * Podstrona. Znane adresy (Pierwszy raz, Spotkania, Kursy, Silent Disco,
 * O nas, Kontakt) mają własne układy w template-parts/; pozostałe pokazują
 * treść z edytora.
 */
$slug = get_post_field( 'post_name', get_queried_object_id() );
$map  = array(
	'pierwsza-wizyta'          => 'first',
	'pierwszy-raz-2'           => 'first',
	'pierwszy-raz'             => 'first',
	'spotkania'                => 'meetings',
	'spotkania-wspolnoty'      => 'meetings',
	'kursy-i-formacja'         => 'courses',
	'czym-sa-kursy-sesa'       => 'courses',
	'jakie-kursy-sa-dla-mnie'  => 'courses',
	'zapisz-sie'               => 'courses',
	'kursy'                    => 'courses',
	'uwielbienie'              => 'silent-disco',
	'uwielbienie-silent-disco' => 'silent-disco',
	'silent-disco'             => 'silent-disco',
	'poznaj-miriam'            => 'about',
	'o-nas'                    => 'about',
	'historia'                 => 'about',
	'charyzmat'                => 'about',
	'pasterz-i-odpowiedzialni' => 'about',
	'kontakt'                  => 'contact',
	'odwiedz-nas'              => 'contact',
	'polityka-prywatnosci'     => 'privacy',
	'swiadectwa'               => 'stories',
	'privacy-policy'           => 'privacy',
);

get_header();

if ( isset( $map[ $slug ] ) && ! get_post_meta( get_queried_object_id(), 'miriam_use_editor', true ) ) {
	get_template_part( 'template-parts/page', $map[ $slug ] );
} else {
	while ( have_posts() ) :
		the_post();
		?>
<main id="main">
	<section class="page-hero glow">
		<div class="wrap">
			<p class="kicker">LSE Miriam</p>
			<h1 class="display"><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="page-content">
		<div class="wrap">
			<?php
			the_content();
			wp_link_pages();
			?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
		<?php
	endwhile;
}

get_footer();
