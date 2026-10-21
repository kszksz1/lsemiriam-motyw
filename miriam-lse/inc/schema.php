<?php
/**
 * Dane strukturalne (schema.org), dzięki którym Google może pokazywać
 * wspólnotę i wydarzenia w wynikach wyszukiwania.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function miriam_schema_place( $name ) {
	if ( $name && false === mb_stripos( $name, 'Józef' ) ) {
		return array(
			'@type'   => 'Place',
			'name'    => $name,
			'address' => array( '@type' => 'PostalAddress', 'name' => $name, 'addressCountry' => 'PL' ),
		);
	}
	return array(
		'@type'   => 'Place',
		'name'    => $name ? $name : 'Parafia św. Józefa w Lublinie',
		'address' => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'ul. Filaretów 7',
			'postalCode'      => '20-609',
			'addressLocality' => 'Lublin',
			'addressCountry'  => 'PL',
		),
	);
}

function miriam_schema_iso( $date, $time ) {
	if ( preg_match( '/(\d{1,2}):(\d{2})/', (string) $time, $m ) ) {
		$dt = new DateTime( $date . ' ' . $m[1] . ':' . $m[2] . ':00', new DateTimeZone( 'Europe/Warsaw' ) );
		return $dt->format( 'c' );
	}
	return $date;
}

add_action( 'wp_head', function () {
	if ( ! is_front_page() && ! is_page() ) {
		return;
	}
	$l    = miriam_links();
	$org  = array(
		'@type'       => 'Organization',
		'@id'         => home_url( '/#org' ),
		'name'        => 'Lubelska Szkoła Ewangelizacji Miriam',
		'alternateName' => 'LSE Miriam',
		'url'         => home_url( '/' ),
		'logo'        => miriam_asset( 'logo.webp' ),
		'email'       => miriam_opt( 'email' ),
		'sameAs'      => array_values( array_filter( array( $l['instagram'], $l['facebook'], $l['youtube'], $l['spotify'] ) ) ),
	);
	$phone = miriam_opt( 'phone' );
	if ( $phone ) {
		$org['telephone'] = $phone;
	}
	$graph = array( $org );

	if ( is_front_page() ) {
		$names = array(
			'silent-disco' => 'Uwielbienie Silent Disco — LSE Miriam',
			'uzdrowienie'  => 'Msza Święta z modlitwą o uzdrowienie — LSE Miriam',
		);
		$desc = array(
			'silent-disco' => 'Uwielbienie w słuchawkach: muzyka, światło, krótka konferencja i adoracja. Wstęp wolny.',
			'uzdrowienie'  => 'Otwarta Msza Święta z modlitwą charyzmatyczną o uzdrowienie i uwielbieniem.',
			'kurs'         => 'Kurs Szkoły Ewangelizacji św. Andrzeja (SESA) prowadzony przez LSE Miriam.',
			'inne'         => 'Wydarzenie wspólnoty LSE Miriam.',
		);
		foreach ( miriam_get_events( '', 25 ) as $ev ) {
			$type  = $ev['type'] ? $ev['type'] : 'inne';
			if ( 'kurs' === $type && ! $ev['place'] ) {
				continue; // bez miejsca nie podajemy Google błędnego adresu
			}
			$start = miriam_schema_iso( $ev['start'], $ev['time'] );
			$item  = array(
				'@type'               => 'Event',
				'name'                => isset( $names[ $type ] ) ? $names[ $type ] : ( 'kurs' === $type ? 'Kurs ' . $ev['title'] . ' — LSE Miriam' : $ev['title'] ),
				'description'         => isset( $desc[ $type ] ) ? $desc[ $type ] : $desc['inne'],
				'startDate'           => $start,
				'eventStatus'         => 'https://schema.org/EventScheduled',
				'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
				'location'            => miriam_schema_place( $ev['place'] ),
				'organizer'           => array( '@id' => home_url( '/#org' ) ),
				'image'               => array( 'silent-disco' === $type ? miriam_asset( 'silent-disco.webp' ) : ( 'uzdrowienie' === $type ? miriam_photo( 'uzdrowienie' ) : miriam_photo( 'wspolnota' ) ) ),
				'url'                 => $ev['link'] ? $ev['link'] : home_url( '/' ),
			);
			if ( $ev['end'] ) {
				$item['endDate'] = $ev['end'];
			} elseif ( preg_match( '/(\d{1,2}):(\d{2})/', (string) $ev['time'], $m ) ) {
				$dt = new DateTime( $ev['start'] . ' ' . $m[1] . ':' . $m[2] . ':00', new DateTimeZone( 'Europe/Warsaw' ) );
				$dt->modify( '+2 hours' );
				$item['endDate'] = $dt->format( 'c' );
			}
			// Wykonawca: zespół uwielbienia przy Silent Disco, w pozostałych — wspólnota.
			$item['performer'] = 'silent-disco' === $type
				? array( '@type' => 'PerformingGroup', 'name' => 'Zespół uwielbienia LSE Miriam', 'url' => home_url( '/' ) )
				: array( '@type' => 'Organization', 'name' => 'Lubelska Szkoła Ewangelizacji Miriam', 'url' => home_url( '/' ) );
			if ( 'kurs' !== $type ) {
				$item['isAccessibleForFree'] = true;
				$item['offers'] = array(
					'@type'         => 'Offer',
					'price'         => '0',
					'priceCurrency' => 'PLN',
					'availability'  => 'https://schema.org/InStock',
					'url'           => home_url( '/' ),
					'validFrom'     => gmdate( 'Y-m-d', strtotime( $ev['start'] . ' -60 days' ) ),
				);
			} elseif ( $ev['link'] ) {
				$item['offers'] = array(
					'@type'         => 'Offer',
					'availability'  => 'https://schema.org/InStock',
					'url'           => $ev['link'],
					'priceCurrency' => 'PLN',
				);
			}
			$graph[] = $item;
		}
	}

	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
}, 20 );
