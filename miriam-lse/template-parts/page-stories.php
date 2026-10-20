<?php
/**
 * Historie (świadectwa).
 */
$post_id = get_queried_object_id();
$stories = miriam_parse_stories( get_post_field( 'post_content', $post_id ) );
?>
<main id="main">
	<section class="page-hero glow">
		<div class="wrap" style="display:flex;flex-direction:column;gap:22px">
			<p class="kicker">Historie spotkania</p>
			<h1 class="display">Co Bóg dał mi<br><span class="accent">przez LSE Miriam?</span></h1>
			<p class="lead">Każdy przychodzi z inną historią. Oto kilka z nich — opowiedzianych własnymi słowami.</p>
		</div>
	</section>
	<section class="page-content">
		<div class="wrap" style="max-width:1240px">
			<?php if ( $stories ) : ?>
				<div class="stories-grid">
					<?php foreach ( $stories as $s ) : ?>
						<figure class="story-card">
							<blockquote>
								<p class="story-full"><?php echo esc_html( $s['text'] ); ?></p>
							</blockquote>
							<figcaption><span class="story-avatar" aria-hidden="true"><?php echo esc_html( mb_substr( $s['name'], 0, 1 ) ); ?></span><?php echo esc_html( $s['name'] ); ?></figcaption>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<?php the_content(); ?>
			<?php endif; ?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/visit' ); ?>
</main>
