<?php
/**
 * Ustawienia w Wygląd → Dostosuj → „Strona Miriam”.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function miriam_defaults() {
	return array(
		'hero_kicker'    => 'Gwiazdo Ewangelizacji, prowadź nas!',
		'hero_video_1'   => '',
		'hero_video_2'   => '',
		'hero_video_3'   => '',
		'hero_poster_1'  => '',
		'hero_poster_2'  => '',
		'hero_poster_3'  => '',
		'featured_video' => 'prZg7yDerOE',
		'featured_title' => 'Niepojęty, Niezmierzony — Uwielbienie Silent Disco',
		'spotify_id'     => '7rzeMzZRiC8A39RMqgyK9t',
		'sd_poster'      => '',
		'sd_photo'       => '',
		'email'          => 'lublin.se@gmail.com',
		'phone'          => '+48 510 475 734',
		'prayer_email'   => 'lublin.se@gmail.com',
		'whatsapp_url'   => 'https://chat.whatsapp.com/EMyYE2JpY7qB6LzL7IeyKg',
		'gcal_url'       => '',
		'rodo_admin'     => 'Katolickie Stowarzyszenie Młodzieży w Polsce (KSM)',
		'rodo_details'   => "ul. Miodowa 17/19, 00-246 Warszawa\ntel. 570 753 770, e-mail: biuro@ksm.org.pl\nreprezentowane przez Prezydium Krajowej Rady KSM",
		'story_quote'    => '',
		'story_author'   => '',
	);
}

function miriam_opt( $key ) {
	$d = miriam_defaults();
	$v = get_theme_mod( 'miriam_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
	return is_string( $v ) ? trim( $v ) : $v;
}

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'miriam', array(
		'title'       => 'Strona Miriam',
		'priority'    => 30,
		'description' => 'Treści strony głównej. Wydarzenia (Silent Disco, kursy, Msze o uzdrowienie) edytujesz w menu „Wydarzenia”.',
	) );

	$fields = array(
		'hero_kicker'    => array( 'Napis nad hasłem', 'text' ),
		'hero_video_1'   => array( 'Nagłówek: wideo 1 (adres pliku .mp4 z Mediów)', 'url' ),
		'hero_poster_1'  => array( 'Nagłówek: zdjęcie 1 (gdy brak wideo)', 'image' ),
		'hero_video_2'   => array( 'Nagłówek: wideo 2 — środkowe, największe (.mp4)', 'url' ),
		'hero_poster_2'  => array( 'Nagłówek: zdjęcie 2', 'image' ),
		'hero_video_3'   => array( 'Nagłówek: wideo 3 (.mp4)', 'url' ),
		'hero_poster_3'  => array( 'Nagłówek: zdjęcie 3', 'image' ),
		'sd_poster'      => array( 'Silent Disco: grafika (domyślnie „Randka w ciemno”)', 'image' ),
		'featured_video' => array( 'Wyróżniony film YouTube (ID lub link)', 'text' ),
		'featured_title' => array( 'Tytuł wyróżnionego filmu', 'text' ),
		'spotify_id'     => array( 'Spotify: ID artysty', 'text' ),
		'story_quote'    => array( 'Świadectwo: cytat (puste = sekcja ukryta)', 'textarea' ),
		'story_author'   => array( 'Świadectwo: podpis, np. „Ania, we wspólnocie od 2021”', 'text' ),
		'email'          => array( 'E-mail kontaktowy', 'email' ),
		'phone'          => array( 'Telefon kontaktowy (puste = ukryty)', 'text' ),
		'prayer_email'   => array( 'E-mail, na który trafiają prośby o modlitwę', 'email' ),
		'whatsapp_url'   => array( 'Link do grupy LSE Miriam Info na WhatsAppie (puste = ukryty)', 'url' ),
		'rodo_admin'     => array( 'RODO: administrator danych (pełna nazwa)', 'text' ),
		'rodo_details'   => array( 'RODO: adres, KRS, kontakt do administratora / inspektora ochrony danych', 'textarea' ),
		'gcal_url'       => array( 'Opcjonalnie: publiczny link do Waszego Kalendarza Google (zastępuje kalendarz z wydarzeń strony)', 'url' ),
	);
	$defaults = miriam_defaults();

	foreach ( $fields as $key => $f ) {
		$sanitize = 'sanitize_text_field';
		if ( 'url' === $f[1] ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'email' === $f[1] ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'textarea' === $f[1] ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( 'image' === $f[1] ) {
			$sanitize = 'absint';
		}
		$wp_customize->add_setting( 'miriam_' . $key, array(
			'default'           => $defaults[ $key ],
			'sanitize_callback' => $sanitize,
		) );
		if ( 'image' === $f[1] ) {
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'miriam_' . $key, array(
				'label'     => $f[0],
				'section'   => 'miriam',
				'mime_type' => 'image',
			) ) );
		} else {
			$wp_customize->add_control( 'miriam_' . $key, array(
				'label'   => $f[0],
				'section' => 'miriam',
				'type'    => $f[1],
			) );
		}
	}
} );

/**
 * YouTube ID from an ID or any YouTube link.
 */
function miriam_yt_id( $v ) {
	if ( preg_match( '~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', $v, $m ) ) {
		return $m[1];
	}
	return preg_match( '/^[A-Za-z0-9_-]{11}$/', $v ) ? $v : '';
}

function miriam_img_url( $key, $size = 'large' ) {
	$id = (int) miriam_opt( $key );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, $size );
		if ( $src ) {
			return $src;
		}
	}
	return '';
}
