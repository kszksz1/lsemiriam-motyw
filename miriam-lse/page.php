<?php
/**
 * Podstrona.
 * - Pierwszy raz, Spotkania, Kursy, Silent Disco, O nas, Kontakt: treść z edytora
 *   WordPressa (bloki) w układzie strony; elementy automatyczne to krótkie kody
 *   [miriam_…] (terminy, formularz modlitwy, mapa…).
 * - Gdy treść nie jest jeszcze w blokach, działa dawny układ z template-parts/.
 * - Pozostałe strony: zwykła treść z edytora.
 */
$page_id = get_queried_object_id();
$key     = miriam_section_key( get_post_field( 'post_name', $page_id ) );

get_header();

if ( $key && miriam_is_block_page( $page_id ) ) {
	$s     = miriam_sections()[ $key ];
	$style = $s['photo'] ? miriam_photo_style( $s['photo'] ) : '';
	while ( have_posts() ) :
		the_post();
		?>
<main id="main" class="page-blocks page-<?php echo esc_attr( $key ); ?>" style="<?php echo esc_attr( $style ); ?>">
		<?php if ( 'silent-disco' === $key ) : ?>
	<div class="sd-band" role="img" aria-label="Górny kościół św. Józefa w niebieskim świetle podczas uwielbienia"></div>
		<?php endif; ?>
		<?php the_content(); ?>
		<?php
		if ( $s['visit'] ) {
			get_template_part( 'template-parts/visit' );
		}
		?>
</main>
		<?php
	endwhile;
} elseif ( $key && miriam_section_template( $key ) ) {
	get_template_part( 'template-parts/page', miriam_section_template( $key ) );
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
