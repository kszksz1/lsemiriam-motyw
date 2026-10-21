<?php
/**
 * Wydarzenia: Silent Disco, kursy, Msze o uzdrowienie i inne.
 * Edytowane w panelu: Wydarzenia → Dodaj nowe.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function miriam_event_types() {
	return array(
		'silent-disco' => 'Silent Disco',
		'kurs'         => 'Kurs',
		'uzdrowienie'  => 'Msza z modlitwą o uzdrowienie',
		'inne'         => 'Inne wydarzenie',
	);
}

add_action( 'init', function () {
	register_post_type( 'miriam_event', array(
		'labels'       => array(
			'name'          => 'Wydarzenia',
			'singular_name' => 'Wydarzenie',
			'add_new'       => 'Dodaj wydarzenie',
			'add_new_item'  => 'Dodaj wydarzenie',
			'edit_item'     => 'Edytuj wydarzenie',
			'all_items'     => 'Wszystkie wydarzenia',
			'menu_name'     => 'Wydarzenia',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-calendar-alt',
		'menu_position'=> 5,
		'supports'     => array( 'title' ),
	) );
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'miriam_event_box', 'Szczegóły wydarzenia', 'miriam_event_box', 'miriam_event', 'normal', 'high' );
} );

function miriam_event_fields() {
	return array(
		'_me_type'  => 'Rodzaj',
		'_me_start' => 'Data rozpoczęcia',
		'_me_end'   => 'Data zakończenia (dla kursów)',
		'_me_time'  => 'Godzina',
		'_me_place' => 'Miejsce',
		'_me_link'  => 'Link do zapisów lub szczegółów',
		'_me_details' => 'Szczegóły (dla kursów)',
	);
}

function miriam_event_box( $post ) {
	wp_nonce_field( 'miriam_event_save', 'miriam_event_nonce' );
	$v = array();
	foreach ( array_keys( miriam_event_fields() ) as $k ) {
		$v[ $k ] = get_post_meta( $post->ID, $k, true );
	}
	?>
	<style>.me-grid{display:grid;grid-template-columns:220px 1fr;gap:12px 16px;align-items:center;max-width:720px}.me-grid input,.me-grid select,.me-grid textarea{width:100%}</style>
	<div class="me-grid">
		<label for="_me_type">Rodzaj</label>
		<select id="_me_type" name="_me_type">
			<?php foreach ( miriam_event_types() as $key => $label ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $v['_me_type'], $key ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<label for="_me_start">Data rozpoczęcia</label>
		<input type="date" id="_me_start" name="_me_start" value="<?php echo esc_attr( $v['_me_start'] ); ?>" required>
		<label for="_me_end">Data zakończenia (dla kursów)</label>
		<input type="date" id="_me_end" name="_me_end" value="<?php echo esc_attr( $v['_me_end'] ); ?>">
		<label for="_me_time">Godzina</label>
		<input type="text" id="_me_time" name="_me_time" value="<?php echo esc_attr( $v['_me_time'] ); ?>" placeholder="np. 20:00">
		<label for="_me_place">Miejsce</label>
		<input type="text" id="_me_place" name="_me_place" value="<?php echo esc_attr( $v['_me_place'] ); ?>" placeholder="np. Górny kościół św. Józefa">
		<label for="_me_link">Link do zapisów lub szczegółów</label>
		<input type="url" id="_me_link" name="_me_link" value="<?php echo esc_attr( $v['_me_link'] ); ?>" placeholder="https://forms…">
		<label for="_me_details">Szczegóły (dla kursów)<br><small>Każda linia to osobny punkt, np. „Koszt: 590 zł”.</small></label>
		<textarea id="_me_details" name="_me_details" rows="7" placeholder="Miejsce: …&#10;Koszt: …&#10;Czas: czwartek 17:30 – niedziela 15:00&#10;Dla kogo: …&#10;Zapisy do: …"><?php echo esc_textarea( $v['_me_details'] ); ?></textarea>
	</div>
	<p class="description">Wydarzenia, których data już minęła, same znikają ze strony.</p>
	<?php
}

add_action( 'save_post_miriam_event', function ( $post_id ) {
	if ( ! isset( $_POST['miriam_event_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['miriam_event_nonce'] ) ), 'miriam_event_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array_keys( miriam_event_fields() ) as $k ) {
		if ( ! isset( $_POST[ $k ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $k ] );
		if ( '_me_link' === $k ) {
			$val = esc_url_raw( $raw );
		} elseif ( '_me_type' === $k ) {
			$val = array_key_exists( $raw, miriam_event_types() ) ? $raw : 'inne';
		} elseif ( '_me_details' === $k ) {
			$val = sanitize_textarea_field( $raw );
		} elseif ( in_array( $k, array( '_me_start', '_me_end' ), true ) ) {
			$val = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw ) ? $raw : '';
		} else {
			$val = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $k, $val );
	}
} );

/* Admin list columns */
add_filter( 'manage_miriam_event_posts_columns', function ( $cols ) {
	return array(
		'cb'        => $cols['cb'],
		'title'     => 'Nazwa',
		'me_type'   => 'Rodzaj',
		'me_date'   => 'Termin',
		'me_place'  => 'Miejsce',
	);
} );
add_action( 'manage_miriam_event_posts_custom_column', function ( $col, $id ) {
	$types = miriam_event_types();
	if ( 'me_type' === $col ) {
		$t = get_post_meta( $id, '_me_type', true );
		echo esc_html( isset( $types[ $t ] ) ? $types[ $t ] : '' );
	} elseif ( 'me_date' === $col ) {
		echo esc_html( trim( get_post_meta( $id, '_me_start', true ) . ' ' . get_post_meta( $id, '_me_time', true ) ) );
	} elseif ( 'me_place' === $col ) {
		echo esc_html( get_post_meta( $id, '_me_place', true ) );
	}
}, 10, 2 );
add_filter( 'manage_edit-miriam_event_sortable_columns', function ( $cols ) {
	$cols['me_date'] = 'me_date';
	return $cols;
} );
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() && $q->is_main_query() && 'miriam_event' === $q->get( 'post_type' ) ) {
		$q->set( 'meta_key', '_me_start' );
		$q->set( 'orderby', 'meta_value' );
		if ( ! $q->get( 'order' ) ) {
			$q->set( 'order', 'ASC' );
		}
	}
} );

/**
 * Upcoming events of a type (end date or start date not in the past).
 */
function miriam_get_events( $type = '', $limit = 20 ) {
	$today = wp_date( 'Y-m-d' );
	$meta  = array(
		'relation' => 'AND',
		array(
			'relation' => 'OR',
			array( 'key' => '_me_end', 'value' => $today, 'compare' => '>=', 'type' => 'DATE' ),
			array( 'key' => '_me_start', 'value' => $today, 'compare' => '>=', 'type' => 'DATE' ),
		),
	);
	if ( $type ) {
		$meta[] = array( 'key' => '_me_type', 'value' => $type );
	}
	$posts = get_posts( array(
		'post_type'      => 'miriam_event',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'meta_key'       => '_me_start',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => $meta,
		'no_found_rows'  => true,
	) );
	$out = array();
	foreach ( $posts as $p ) {
		$out[] = array(
			'id'    => $p->ID,
			'title' => get_the_title( $p ),
			'type'  => get_post_meta( $p->ID, '_me_type', true ),
			'start' => get_post_meta( $p->ID, '_me_start', true ),
			'end'   => get_post_meta( $p->ID, '_me_end', true ),
			'time'  => get_post_meta( $p->ID, '_me_time', true ),
			'place' => get_post_meta( $p->ID, '_me_place', true ),
			'link'  => get_post_meta( $p->ID, '_me_link', true ),
			'details' => get_post_meta( $p->ID, '_me_details', true ),
		);
	}
	return $out;
}

function miriam_months( $form = 'gen' ) {
	if ( 'short' === $form ) {
		return array( 1 => 'sty', 'lut', 'mar', 'kwi', 'maj', 'cze', 'lip', 'sie', 'wrz', 'paź', 'lis', 'gru' );
	}
	return array( 1 => 'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia' );
}

function miriam_weekday( $date ) {
	$days = array( 'niedziela', 'poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota' );
	$ts   = strtotime( $date . ' 12:00:00' );
	return $days[ (int) gmdate( 'w', $ts ) ];
}

/**
 * Parts of a date range: [days, "miesiąca rok"].
 */
function miriam_range_parts( $start, $end ) {
	$m  = miriam_months();
	$s  = strtotime( $start . ' 12:00:00' );
	$e  = $end ? strtotime( $end . ' 12:00:00' ) : $s;
	$sd = (int) gmdate( 'j', $s );
	$ed = (int) gmdate( 'j', $e );
	$sm = (int) gmdate( 'n', $s );
	$em = (int) gmdate( 'n', $e );
	if ( $e === $s ) {
		return array( (string) $sd, $m[ $sm ] . ' ' . gmdate( 'Y', $s ) );
	}
	if ( $sm === $em ) {
		return array( $sd . '–' . $ed, $m[ $sm ] . ' ' . gmdate( 'Y', $e ) );
	}
	return array( $sd . '.' . sprintf( '%02d', $sm ) . '–' . $ed . '.' . sprintf( '%02d', $em ), gmdate( 'Y', $e ) );
}

function miriam_short_date( $start ) {
	$s = strtotime( $start . ' 12:00:00' );
	return gmdate( 'j', $s ) . '.' . gmdate( 'm', $s );
}

/**
 * Kalendarze .ics:
 *   /kalendarz-silent-disco.ics | -kurs | -uzdrowienie  — jeden rodzaj
 *   /kalendarz.ics                                      — kalendarz wspólnoty do subskrypcji
 *                                                     (wszystkie wydarzenia + wtorkowe spotkania)
 */
function miriam_ics_url( $type = 'all', $scheme = 'https' ) {
	$type = sanitize_key( $type );
	$url  = home_url( '/' . ( 'all' === $type ? 'kalendarz.ics' : 'kalendarz-' . $type . '.ics' ) );
	return 'webcal' === $scheme ? preg_replace( '#^https?://#', 'webcal://', $url ) : $url;
}

function miriam_calendar_feed_url( $scheme = 'https' ) {
	return miriam_ics_url( 'all', $scheme );
}

function miriam_calendar_google_url() {
	$own = miriam_opt( 'gcal_url' );
	if ( $own ) {
		return $own;
	}
	return 'https://calendar.google.com/calendar/r?cid=' . rawurlencode( miriam_calendar_feed_url( 'webcal' ) );
}

/*
 * Adresy z końcówką .ics (/kalendarz.ics, /kalendarz-silent-disco.ics) — kalendarze Apple
 * i Outlook radzą sobie z nimi pewniej niż z adresem z parametrem. Stary adres
 * /?miriam_ics=… nadal działa.
 */
add_action( 'wp_loaded', function () {
	$path = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : ''; // phpcs:ignore
	$base = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( $path && preg_match( '#^' . preg_quote( rtrim( $base, '/' ), '#' ) . '/kalendarz(?:-([a-z-]+))?\.ics$#', $path, $m ) ) {
		miriam_ics_output( empty( $m[1] ) ? 'all' : $m[1] );
	}
} );

add_action( 'template_redirect', function () {
	if ( empty( $_GET['miriam_ics'] ) ) {
		return;
	}
	miriam_ics_output( sanitize_key( wp_unslash( $_GET['miriam_ics'] ) ) );
} );

function miriam_ics_output( $type ) {
	$all  = 'all' === $type;
	if ( ! $all && ! array_key_exists( $type, miriam_event_types() ) ) {
		return;
	}
	$events = miriam_get_events( $all ? '' : $type, 100 );
	$tz     = 'Europe/Warsaw';
	$host   = wp_parse_url( home_url(), PHP_URL_HOST );
	$name   = $all ? 'LSE Miriam — kalendarz wspólnoty' : 'LSE Miriam — ' . miriam_event_types()[ $type ];
	$lines  = array(
		'BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//LSE Miriam//PL', 'CALSCALE:GREGORIAN', 'METHOD:PUBLISH',
		'X-WR-CALNAME:' . miriam_ics_escape( $name ), 'X-WR-CALDESC:' . miriam_ics_escape( 'Wydarzenia Lubelskiej Szkoły Ewangelizacji Miriam' ),
		'X-WR-TIMEZONE:' . $tz, 'X-APPLE-CALENDAR-COLOR:#F8981F',
		'REFRESH-INTERVAL;VALUE=DURATION:PT6H', 'X-PUBLISHED-TTL:PT6H',
		'BEGIN:VTIMEZONE', 'TZID:Europe/Warsaw',
		'BEGIN:DAYLIGHT', 'TZOFFSETFROM:+0100', 'TZOFFSETTO:+0200', 'TZNAME:CEST', 'DTSTART:19700329T020000', 'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU', 'END:DAYLIGHT',
		'BEGIN:STANDARD', 'TZOFFSETFROM:+0200', 'TZOFFSETTO:+0100', 'TZNAME:CET', 'DTSTART:19701025T030000', 'RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU', 'END:STANDARD',
		'END:VTIMEZONE',
	);
	if ( $all ) {
		$lines[] = 'BEGIN:VEVENT';
		$lines[] = 'UID:miriam-wtorki@' . $host;
		$lines[] = 'DTSTAMP:' . gmdate( 'Ymd\THis\Z' );
		$lines[] = 'DTSTART;TZID=' . $tz . ':20261006T190000';
		$lines[] = 'DTEND;TZID=' . $tz . ':20261006T210000';
		$lines[] = 'RRULE:FREQ=WEEKLY;BYDAY=TU';
		foreach ( $events as $hx ) {
			if ( 'uzdrowienie' === $hx['type'] && '2' === gmdate( 'w', strtotime( $hx['start'] . ' 12:00:00' ) ) ) {
				$lines[] = 'EXDATE;TZID=' . $tz . ':' . str_replace( '-', '', $hx['start'] ) . 'T190000';
			}
		}
		$lines[] = 'SUMMARY:Spotkanie wspólnoty LSE Miriam';
		$lines[] = 'LOCATION:' . miriam_ics_escape( 'Dolny kościół parafii św. Józefa, ul. Filaretów 7, Lublin' );
		$lines[] = 'DESCRIPTION:' . miriam_ics_escape( 'Uwielbienie, Słowo, wspólnota. Otwarte dla każdego.' );
		$lines[] = 'URL:' . home_url( '/' );
		$lines[] = 'END:VEVENT';
	}
	foreach ( $events as $ev ) {
		$time = preg_match( '/(\d{1,2}):(\d{2})/', $ev['time'], $mm ) ? sprintf( '%02d%02d00', $mm[1], $mm[2] ) : '';
		$lines[] = 'BEGIN:VEVENT';
		$lines[] = 'UID:miriam-' . $ev['id'] . '@' . $host;
		$lines[] = 'DTSTAMP:' . gmdate( 'Ymd\THis\Z' );
		if ( $time && ! $ev['end'] ) {
			$start   = new DateTime( $ev['start'] . ' ' . $mm[1] . ':' . $mm[2] . ':00', new DateTimeZone( $tz ) );
			$end     = clone $start;
			$end->modify( '+2 hours' );
			$lines[] = 'DTSTART;TZID=' . $tz . ':' . $start->format( 'Ymd\THis' );
			$lines[] = 'DTEND;TZID=' . $tz . ':' . $end->format( 'Ymd\THis' );
		} else {
			$lines[] = 'DTSTART;VALUE=DATE:' . str_replace( '-', '', $ev['start'] );
			$end     = $ev['end'] ? $ev['end'] : $ev['start'];
			$lines[] = 'DTEND;VALUE=DATE:' . gmdate( 'Ymd', strtotime( $end . ' 12:00:00 +1 day' ) );
		}
		$title = 'kurs' === $ev['type'] ? 'Kurs ' . $ev['title'] : $ev['title'];
		$lines[] = 'SUMMARY:' . miriam_ics_escape( $title );
		$lines[] = 'LOCATION:' . miriam_ics_escape( trim( $ev['place'] . ', ul. Filaretów 7, Lublin', ', ' ) );
		if ( $ev['link'] ) {
			$lines[] = 'DESCRIPTION:' . miriam_ics_escape( 'Zapisy i szczegóły: ' . $ev['link'] );
		}
		$lines[] = 'URL:' . ( $ev['link'] ? $ev['link'] : home_url( '/' ) );
		$lines[] = 'END:VEVENT';
	}
	$lines[] = 'END:VCALENDAR';

	$folded = array();
	foreach ( $lines as $line ) {
		$folded[] = miriam_ics_fold( $line );
	}
	nocache_headers();
	header( 'Content-Type: text/calendar; charset=utf-8' );
	// inline: iPhone pokazuje wtedy listę wydarzeń z przyciskiem „Dodaj wszystkie” zamiast zapisywać plik.
	header( 'Content-Disposition: inline; filename="miriam-' . $type . '.ics"' );
	header( 'X-Robots-Tag: noindex' );
	if ( 'HEAD' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) { // phpcs:ignore
		echo implode( "\r\n", $folded ) . "\r\n"; // phpcs:ignore
	}
	exit;
}

/* RFC 5545: lines longer than 75 bytes are folded. */
function miriam_ics_fold( $line ) {
	if ( strlen( $line ) <= 75 ) {
		return $line;
	}
	$out = '';
	$cur = '';
	foreach ( preg_split( '//u', $line, -1, PREG_SPLIT_NO_EMPTY ) as $ch ) {
		if ( strlen( $cur . $ch ) > 74 ) {
			$out .= $cur . "\r\n ";
			$cur  = '';
		}
		$cur .= $ch;
	}
	return $out . $cur;
}

function miriam_ics_escape( $s ) {
	return str_replace( array( '\\', ';', ',', "\n" ), array( '\\\\', '\;', '\,', '\n' ), $s );
}

/**
 * Seed initial events once, on theme activation.
 */
add_action( 'after_switch_theme', 'miriam_seed_events' );
add_action( 'init', 'miriam_seed_events', 15 );
function miriam_seed_events() {
	if ( get_option( 'miriam_events_seeded_v2' ) ) {
		return;
	}
	update_option( 'miriam_events_seeded_v2', 1 );
	$has = function ( $type ) {
		return (bool) get_posts( array( 'post_type' => 'miriam_event', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_me_type', 'meta_value' => $type ) );
	};
	$skip_sd     = $has( 'silent-disco' );
	$skip_course = $has( 'kurs' );
	$sd_place = 'Górny kościół św. Józefa';
	$seed     = array();
	foreach ( array( '2026-10-25', '2026-11-29', '2026-12-20', '2027-01-31', '2027-02-28', '2027-03-21', '2027-04-25', '2027-05-30', '2027-06-27' ) as $d ) {
		$seed[] = array( 'Uwielbienie Silent Disco', 'silent-disco', $d, '', '20:00', '2027-06-27' === $d ? $sd_place . ' + patio' : $sd_place, '' );
	}
	$lt     = '';
	$seed[] = array( 'Historia Zbawienia', 'kurs', '2026-11-12', '2026-11-15', '', '', 'https://forms.cloud.microsoft/e/TTwyjiRFZG' );
	$seed[] = array( 'Nowe Życie', 'kurs', '2026-12-04', '2026-12-06', '', '', $lt );
	$seed[] = array( 'Jan', 'kurs', '2026-12-10', '2026-12-13', '', '', 'https://forms.cloud.microsoft/e/c2Fmzy5wZK' );
	$seed[] = array( 'Emaus', 'kurs', '2027-02-05', '2027-02-07', '', '', $lt );
	$seed[] = array( 'Jan', 'kurs', '2027-04-01', '2027-04-04', '', '', $lt );
	$seed[] = array( 'Nowe Życie', 'kurs', '2027-04-02', '2027-04-04', '', '', $lt );
	$seed[] = array( 'Jezus w 4 Ewangeliach', 'kurs', '2027-06-11', '2027-06-13', '', '', $lt );

	foreach ( $seed as $s ) {
		if ( ( 'silent-disco' === $s[1] && $skip_sd ) || ( 'kurs' === $s[1] && $skip_course ) ) {
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'   => 'miriam_event',
			'post_status' => 'publish',
			'post_title'  => $s[0],
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_me_type', $s[1] );
			update_post_meta( $id, '_me_start', $s[2] );
			update_post_meta( $id, '_me_end', $s[3] );
			update_post_meta( $id, '_me_time', $s[4] );
			update_post_meta( $id, '_me_place', $s[5] );
			update_post_meta( $id, '_me_link', $s[6] );
		}
	}
	update_option( 'miriam_events_seeded', 1 );
}

/**
 * Jednorazowo: szczegóły kursu Historia Zbawienia (z formularza zapisów).
 */
add_action( 'init', function () {
	if ( get_option( 'miriam_details_v1' ) ) {
		return;
	}
	update_option( 'miriam_details_v1', 1 );
	$ids = get_posts( array(
		'post_type'   => 'miriam_event',
		'post_status' => 'any',
		'numberposts' => 5,
		'fields'      => 'ids',
		'title'       => 'Historia Zbawienia',
		'meta_key'    => '_me_start',
		'meta_value'  => '2026-11-12',
	) );
	foreach ( $ids as $id ) {
		if ( ! get_post_meta( $id, '_me_place', true ) ) {
			update_post_meta( $id, '_me_place', 'Dom Pielgrzymkowo-Rekolekcyjny w Siedlanowie' );
		}
		if ( ! get_post_meta( $id, '_me_details', true ) ) {
			update_post_meta( $id, '_me_details', implode( "\n", array(
				'Miejsce: Diecezjalny Dom Pielgrzymkowo-Rekolekcyjny w Siedlanowie',
				'Czas: czwartek 17:30 – niedziela 15:00 (obecność na całym kursie)',
				'Koszt: 590 zł (3 noclegi, wyżywienie, przerwy kawowe, materiały); pokój jednoosobowy +150 zł',
				'Zapis: formularz + bezzwrotna darowizna 50 zł',
				'Dla kogo: osoby pełnoletnie po kursach Nowe Życie, Emaus i Jan',
				'Zapisy do: 5 listopada 2026 lub do wyczerpania miejsc',
			) ) );
		}
	}
}, 20 );

/**
 * Jednorazowo: kursy bez własnego formularza miały link do Linktree —
 * czyścimy go, żeby na stronie było „Zapisy wkrótce” zamiast mylącego przycisku.
 */
add_action( 'init', function () {
	if ( get_option( 'miriam_links_v2' ) ) {
		return;
	}
	update_option( 'miriam_links_v2', 1 );
	$ids = get_posts( array( 'post_type' => 'miriam_event', 'post_status' => 'any', 'numberposts' => 200, 'fields' => 'ids' ) );
	foreach ( $ids as $id ) {
		if ( false !== strpos( (string) get_post_meta( $id, '_me_link', true ), 'linktr.ee' ) ) {
			update_post_meta( $id, '_me_link', '' );
		}
	}
}, 21 );

/**
 * Terminy Mszy z modlitwą o uzdrowienie (sezon 2026/27) — dodawane jednorazowo.
 */
function miriam_heal_dates() {
	return array( '2026-11-10', '2026-12-22', '2027-01-26', '2027-02-09', '2027-03-09', '2027-04-13', '2027-05-11', '2027-06-08' );
}
add_action( 'init', function () {
	if ( get_option( 'miriam_heal_v1' ) ) {
		return;
	}
	update_option( 'miriam_heal_v1', 1 );
	foreach ( miriam_heal_dates() as $d ) {
		$exists = get_posts( array(
			'post_type'   => 'miriam_event',
			'post_status' => 'any',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array(
				array( 'key' => '_me_type', 'value' => 'uzdrowienie' ),
				array( 'key' => '_me_start', 'value' => $d ),
			),
		) );
		if ( $exists ) {
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'   => 'miriam_event',
			'post_status' => 'publish',
			'post_title'  => 'Msza z modlitwą o uzdrowienie',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_me_type', 'uzdrowienie' );
			update_post_meta( $id, '_me_start', $d );
			update_post_meta( $id, '_me_end', '' );
			update_post_meta( $id, '_me_time', '19:00' );
			update_post_meta( $id, '_me_place', 'Dolny kościół św. Józefa' );
			update_post_meta( $id, '_me_link', '' );
		}
	}
}, 22 );
