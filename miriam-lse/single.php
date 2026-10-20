<?php
/**
 * Pojedynczy wpis.
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>
<main id="main">
	<section class="page-hero glow">
		<div class="wrap">
			<p class="kicker"><?php echo esc_html( get_the_date() ); ?></p>
			<h1 class="display"><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="page-content">
		<div class="wrap">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large', array( 'style' => 'margin-bottom:32px' ) );
			}
			the_content();
			?>
		</div>
	</section>
</main>
	<?php
endwhile;
get_footer();
