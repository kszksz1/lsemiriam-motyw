<?php
/**
 * Prośby o modlitwę.
 * - zapisywane jako prywatne wpisy (widoczne tylko w panelu: Prośby o modlitwę),
 * - wysyłane e-mailem na adres z Wygląd → Dostosuj → Strona Miriam,
 * - usuwane automatycznie po 30 dniach.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MIRIAM_PRAYER_DAYS', 30 );

add_action( 'init', function () {
	register_post_type( 'miriam_prayer', array(
		'labels'          => array(
			'name'          => 'Prośby o modlitwę',
			'singular_name' => 'Prośba o modlitwę',
			'menu_name'     => 'Prośby o modlitwę',
			'all_items'     => 'Wszystkie prośby',
			'edit_item'     => 'Prośba o modlitwę',
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-heart',
		'menu_position'   => 6,
		'supports'        => array( 'title', 'editor' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
} );

add_filter( 'manage_miriam_prayer_posts_columns', function ( $cols ) {
	return array(
		'cb'       => $cols['cb'],
		'title'    => 'Od',
		'mp_text'  => 'Intencja',
		'mp_date'  => 'Data',
	);
} );
add_action( 'manage_miriam_prayer_posts_custom_column', function ( $col, $id ) {
	if ( 'mp_text' === $col ) {
		echo esc_html( wp_trim_words( get_post_field( 'post_content', $id ), 30 ) );
	} elseif ( 'mp_date' === $col ) {
		echo esc_html( get_the_date( 'j.m.Y H:i', $id ) );
	}
}, 10, 2 );

/**
 * Formularz.
 */
function miriam_prayer_form( $variant = 'dark' ) {
	$status = isset( $_GET['modlitwa'] ) ? sanitize_key( wp_unslash( $_GET['modlitwa'] ) ) : '';
	ob_start();
	?>
	<div class="prayer prayer-<?php echo esc_attr( $variant ); ?>" id="modlitwa">
		<div class="prayer-copy">
			<p class="kicker">Prośba o modlitwę</p>
			<h2 class="display h-md">Pomodlimy się za Ciebie.</h2>
			<p>Napisz, w jakiej intencji mamy się modlić — za siebie albo za kogoś bliskiego. Podczas spotkań wspólnoty modlimy się w każdej intencji, o którą prosicie. Możesz pozostać anonimowy.</p>
		</div>
		<?php if ( 'ok' === $status ) : ?>
			<div class="prayer-done" role="status">
				<h3>Dziękujemy. Twoja intencja dotarła.</h3>
				<p>Będziemy się za nią modlić. Bóg słyszy każdą prośbę.</p>
			</div>
		<?php else : ?>
			<form class="prayer-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php if ( 'error' === $status ) : ?>
					<p class="prayer-error" role="alert">Nie udało się wysłać. Sprawdź, czy wpisałeś intencję i zaznaczyłeś zgodę, i spróbuj ponownie.</p>
				<?php endif; ?>
				<input type="hidden" name="action" value="miriam_prayer">
				<input type="hidden" name="mp_back" value="<?php echo esc_url( get_permalink() ? get_permalink() : home_url( '/' ) ); ?>">
				<input type="hidden" name="mp_t" value="<?php echo esc_attr( time() ); ?>">
				<div class="hp" aria-hidden="true"><label>Strona www <input type="text" name="mp_website" tabindex="-1" autocomplete="off"></label></div>
				<label for="mp_text">Intencja</label>
				<textarea id="mp_text" name="mp_text" rows="5" required maxlength="2000" placeholder="Proszę o modlitwę…"></textarea>
				<div class="prayer-row">
					<div>
						<label for="mp_name">Imię <span>(opcjonalnie)</span></label>
						<input id="mp_name" name="mp_name" type="text" maxlength="80" autocomplete="given-name">
					</div>
					<div>
						<label for="mp_email">E-mail <span>(opcjonalnie, jeśli chcesz odpowiedzi)</span></label>
						<input id="mp_email" name="mp_email" type="email" maxlength="120" autocomplete="email">
					</div>
				</div>
				<label class="prayer-consent">
					<input type="checkbox" name="mp_consent" value="1" required>
					<span>Zgadzam się, by <?php echo esc_html( miriam_opt( 'rodo_admin' ) ); ?>, w ramach którego działa LSE Miriam, przetwarzało treść mojej intencji (także informacje o zdrowiu, jeśli je podam) wyłącznie w celu modlitwy. Intencja zostanie usunięta po <?php echo (int) MIRIAM_PRAYER_DAYS; ?> dniach.<?php $pp = miriam_privacy_url(); if ( $pp ) { echo ' <a href="' . esc_url( $pp ) . '">Polityka prywatności</a>.'; } ?></span>
				</label>
				<button class="btn btn-orange" type="submit">Wyślij intencję</button>
			</form>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Obsługa wysłania.
 */
function miriam_prayer_handle() {
	$back = isset( $_POST['mp_back'] ) ? esc_url_raw( wp_unslash( $_POST['mp_back'] ) ) : home_url( '/' );
	$back = wp_validate_redirect( $back, home_url( '/' ) );
	$fail = add_query_arg( 'modlitwa', 'error', $back ) . '#modlitwa';

	// Pułapki na boty: ukryte pole i zbyt szybkie wysłanie.
	$t = isset( $_POST['mp_t'] ) ? (int) $_POST['mp_t'] : 0;
	if ( ! empty( $_POST['mp_website'] ) || ( $t && time() - $t < 3 ) ) {
		wp_safe_redirect( add_query_arg( 'modlitwa', 'ok', $back ) . '#modlitwa' );
		exit;
	}

	$text    = isset( $_POST['mp_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mp_text'] ) ) : '';
	$name    = isset( $_POST['mp_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mp_name'] ) ) : '';
	$email   = isset( $_POST['mp_email'] ) ? sanitize_email( wp_unslash( $_POST['mp_email'] ) ) : '';
	$consent = ! empty( $_POST['mp_consent'] );

	if ( '' === trim( $text ) || ! $consent ) {
		wp_safe_redirect( $fail );
		exit;
	}

	// Limit: 5 próśb na godzinę z jednego adresu.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'miriam_pr_' . md5( $ip . wp_salt() );
	$n   = (int) get_transient( $key );
	if ( $n >= 5 ) {
		wp_safe_redirect( $fail );
		exit;
	}
	set_transient( $key, $n + 1, HOUR_IN_SECONDS );

	$text = mb_substr( $text, 0, 2000 );
	$who  = $name ? $name : 'Anonimowo';
	$id   = wp_insert_post( array(
		'post_type'    => 'miriam_prayer',
		'post_status'  => 'private',
		'post_title'   => $who . ( $email ? ' <' . $email . '>' : '' ),
		'post_content' => $text,
	) );

	$to = miriam_opt( 'prayer_email' );
	if ( $to && is_email( $to ) ) {
		$body  = "Nowa prośba o modlitwę ze strony LSE Miriam\n\n";
		$body .= 'Od: ' . $who . ( $email ? ' (' . $email . ')' : '' ) . "\n\n";
		$body .= $text . "\n\n—\nZapisane także w panelu: Prośby o modlitwę. Usuwane automatycznie po " . MIRIAM_PRAYER_DAYS . ' dniach.';
		$headers = array();
		if ( $email && is_email( $email ) ) {
			$headers[] = 'Reply-To: ' . $email;
		}
		wp_mail( $to, 'Prośba o modlitwę — ' . $who, $body, $headers );
	}

	wp_safe_redirect( add_query_arg( 'modlitwa', $id ? 'ok' : 'error', $back ) . '#modlitwa' );
	exit;
}
add_action( 'admin_post_nopriv_miriam_prayer', 'miriam_prayer_handle' );
add_action( 'admin_post_miriam_prayer', 'miriam_prayer_handle' );

/**
 * Automatyczne usuwanie starszych niż 30 dni.
 */
add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'miriam_prayer_cleanup' ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'miriam_prayer_cleanup' );
	}
} );
add_action( 'miriam_prayer_cleanup', function () {
	$old = get_posts( array(
		'post_type'      => 'miriam_prayer',
		'post_status'    => 'any',
		'posts_per_page' => 200,
		'fields'         => 'ids',
		'date_query'     => array( array( 'before' => MIRIAM_PRAYER_DAYS . ' days ago' ) ),
	) );
	foreach ( $old as $id ) {
		wp_delete_post( $id, true );
	}
} );
add_action( 'switch_theme', function () {
	wp_clear_scheduled_hook( 'miriam_prayer_cleanup' );
} );
