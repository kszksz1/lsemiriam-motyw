<?php
/**
 * Bądź na bieżąco: kalendarz do subskrypcji, WhatsApp, Instagram.
 */
$l      = miriam_links();
$wa     = miriam_opt( 'whatsapp_url' );
$feed   = miriam_calendar_feed_url();
$webcal = miriam_calendar_feed_url( 'webcal' );
?>
<section class="sec connect" id="kalendarz">
	<div class="wrap">
		<div class="sec-head">
			<div>
				<p class="kicker" style="margin-bottom:14px">Bądź na bieżąco</p>
				<h2 class="display h-md">Nasze wydarzenia w Twoim kalendarzu.</h2>
			</div>
			<p>Zasubskrybuj kalendarz wspólnoty: wtorkowe spotkania, Silent Disco, Msze o uzdrowienie i kursy pojawią się w Twoim telefonie i same się zaktualizują, gdy coś się zmieni.</p>
		</div>
		<div class="connect-grid">
			<div class="connect-card">
				<?php echo miriam_icon( 'calendar', 28 ); ?>
				<h3>Kalendarz wspólnoty</h3>
				<div class="btn-row">
					<a class="btn btn-orange btn-sm" href="<?php echo esc_url( miriam_calendar_google_url() ); ?>" target="_blank" rel="noopener">Dodaj do Kalendarza Google</a>
					<a class="btn btn-ghost btn-sm" href="<?php echo esc_attr( $webcal ); ?>">iPhone / Outlook</a>
				</div>
				<details class="connect-more">
					<summary>Inny kalendarz? Skopiuj adres</summary>
					<div class="copy-row">
						<input type="text" readonly value="<?php echo esc_attr( $feed ); ?>" aria-label="Adres kalendarza do subskrypcji">
						<button type="button" class="btn btn-white btn-sm" data-copy="<?php echo esc_attr( $feed ); ?>">Kopiuj</button>
					</div>
				</details>
			</div>
			<?php if ( $wa ) : ?>
			<div class="connect-card">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.5 11.6a8.5 8.5 0 0 1-12.6 7.5L3.5 20.5l1.5-4.2A8.5 8.5 0 1 1 20.5 11.6z"/><path d="M9 8.8c.2 2.6 2.7 5.2 5.4 5.6l1-1.2-1.6-.9-.8.7c-1-.4-2-1.4-2.4-2.4l.7-.8-.9-1.6z"/></svg>
				<h3>LSE Miriam Info na WhatsAppie</h3>
				<p>Ogłoszenia, zmiany miejsca, przypomnienia — prosto na telefon.</p>
				<a class="btn btn-orange btn-sm" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">Dołącz na WhatsAppie</a>
			</div>
			<?php endif; ?>
			<div class="connect-card">
				<?php echo miriam_icon( 'instagram', 28 ); ?>
				<h3>Instagram</h3>
				<p>Rolki z uwielbień, zapowiedzi i kulisy wspólnoty.</p>
				<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( $l['instagram'] ); ?>" target="_blank" rel="noopener">@lse_miriam</a>
			</div>
		</div>
	</div>
</section>
