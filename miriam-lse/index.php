<?php
/**
 * Lista wpisów / archiwum.
 */
get_header();
?>
<main id="main">
	<section class="page-hero glow">
		<div class="wrap">
			<p class="kicker">LSE Miriam</p>
			<h1 class="display"><?php echo is_search() ? 'Wyniki wyszukiwania' : esc_html( wp_strip_all_tags( get_the_archive_title() ? get_the_archive_title() : 'Aktualności' ) ); ?></h1>
		</div>
	</section>
	<section class="page-content">
		<div class="wrap">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article style="padding:24px 0;border-bottom:1px solid #DDE3F5">
						<h2 style="margin:0 0 6px"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p style="margin:0;color:var(--ink-soft)"><?php echo esc_html( get_the_date() ); ?></p>
						<div><?php the_excerpt(); ?></div>
					</article>
				<?php endwhile; ?>
				<?php the_posts_pagination(); ?>
			<?php else : ?>
				<p>Nic tu jeszcze nie ma.</p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
