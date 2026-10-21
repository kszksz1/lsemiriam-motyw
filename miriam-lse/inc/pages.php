<?php
/**
 * Podstrony: adresy, przekierowania ze starych adresów, treści edytowalne
 * w edytorze WordPressa (bloki) i krótkie kody z elementami automatycznymi.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Podstrony: docelowy adres (slug), układ, zdjęcie w nagłówku, czy na dole
 * jest pasek „Do zobaczenia we wtorek” i stare adresy (z kotwicą), które
 * mają prowadzić na tę podstronę.
 */
function miriam_sections() {
	return array(
		'first'        => array(
			'slug'    => 'pierwszy-raz',
			'title'   => 'Pierwszy raz',
			'photo'   => 'grupa',
			'visit'   => true,
			'aliases' => array( 'pierwsza-wizyta' => '', 'pierwszy-raz-2' => '' ),
		),
		'meetings'     => array(
			'slug'    => 'spotkania',
			'title'   => 'Spotkania',
			'photo'   => 'konferencja',
			'visit'   => true,
			'aliases' => array( 'spotkania-wspolnoty' => '' ),
		),
		'courses'      => array(
			'slug'    => 'kursy',
			'title'   => 'Kursy',
			'photo'   => 'jezus-zyje',
			'visit'   => true,
			'aliases' => array(
				'jakie-kursy-sa-dla-mnie' => '',
				'kursy-i-formacja'        => '',
				'czym-sa-kursy-sesa'      => '',
				'zapisz-sie'              => '#terminy',
				'zapisy'                  => '#terminy',
				'nowe-zycie'              => '#ktory-kurs',
				'emaus'                   => '#ktory-kurs',
				'jan'                     => '#ktory-kurs',
				'jezus-w-4-ewangeliach'   => '#ktory-kurs',
				'historia-zbawienia'      => '#ktory-kurs',
				'mojzesz'                 => '#ktory-kurs',
				'kamch'                   => '#ktory-kurs',
			),
		),
		'silent-disco' => array(
			'slug'    => 'silent-disco',
			'title'   => 'Silent Disco',
			'photo'   => 'sd-kosciol',
			'visit'   => false,
			'aliases' => array( 'uwielbienie-silent-disco' => '', 'uwielbienie' => '' ),
		),
		'about'        => array(
			'slug'    => 'o-nas',
			'title'   => 'O nas',
			'photo'   => 'wspolnota',
			'visit'   => true,
			'aliases' => array(
				'historia'                 => '#historia',
				'poznaj-miriam'            => '',
				'charyzmat'                => '#charyzmat',
				'pasterz-i-odpowiedzialni' => '#pasterz',
				'rada-wspolnoty'           => '#pasterz',
				'lider'                    => '#pasterz',
				'wspolnota'                => '',
			),
		),
		'contact'      => array(
			'slug'    => 'kontakt',
			'title'   => 'Kontakt',
			'photo'   => 'sd-kosciol',
			'visit'   => false,
			'aliases' => array( 'odwiedz-nas' => '', 'nasze-socialmedia' => '' ),
		),
		'stories'      => array(
			'slug'    => 'swiadectwa',
			'title'   => '',
			'photo'   => 'modlitwa',
			'visit'   => true,
			'aliases' => array( 'historie' => '' ),
		),
		'privacy'      => array(
			'slug'    => 'polityka-prywatnosci',
			'title'   => '',
			'photo'   => '',
			'visit'   => false,
			'aliases' => array( 'privacy-policy' => '' ),
		),
	);
}

/* Szablony starego typu (template-parts/page-*.php) dla każdej podstrony. */
function miriam_section_template( $key ) {
	$map = array( 'first' => 'first', 'meetings' => 'meetings', 'courses' => 'courses', 'silent-disco' => 'silent-disco', 'about' => 'about', 'contact' => 'contact', 'stories' => 'stories', 'privacy' => 'privacy' );
	return isset( $map[ $key ] ) ? $map[ $key ] : '';
}

/* Strony o danym slugu, niezależnie od strony nadrzędnej. */
function miriam_pages_by_slug( $slug, $status = 'publish' ) {
	return get_posts(
		array(
			'post_type'        => 'page',
			'name'             => $slug,
			'post_status'      => $status,
			'numberposts'      => 5,
			'orderby'          => 'parent',
			'order'            => 'ASC',
			'suppress_filters' => true,
		)
	);
}

/* Opublikowana strona podstrony: najpierw docelowy adres, potem stare. */
function miriam_section_page( $key ) {
	static $cache = array();
	if ( array_key_exists( $key, $cache ) ) {
		return $cache[ $key ];
	}
	$s = miriam_sections()[ $key ];
	foreach ( array_merge( array( $s['slug'] ), array_keys( $s['aliases'] ) ) as $slug ) {
		$found = miriam_pages_by_slug( $slug );
		if ( $found ) {
			return $cache[ $key ] = $found[0];
		}
	}
	return $cache[ $key ] = null;
}

function miriam_section_url( $key, $fallback = '' ) {
	$p = miriam_section_page( $key );
	return $p ? get_permalink( $p ) : $fallback;
}

/* Która podstrona odpowiada temu slugowi (docelowemu albo staremu). */
function miriam_section_key( $slug ) {
	foreach ( miriam_sections() as $key => $s ) {
		if ( $slug === $s['slug'] || array_key_exists( $slug, $s['aliases'] ) ) {
			return $key;
		}
	}
	return '';
}

/* Czy treść strony jest już w blokach (edytowalna w edytorze). */
function miriam_is_block_page( $post ) {
	$post = get_post( $post );
	if ( ! $post || ! has_blocks( $post->post_content ) ) {
		return false;
	}
	return false !== strpos( $post->post_content, '"className":"sec' ) || false !== strpos( $post->post_content, '"className":"page-hero' );
}

/**
 * Stare adresy → aktualne podstrony (301). Działa zarówno dla starych stron,
 * które jeszcze istnieją, jak i dla adresów, których już nie ma (404).
 */
add_action(
	'template_redirect',
	function () {
		if ( is_front_page() || is_admin() ) {
			return;
		}
		if ( is_page() ) {
			$slug = get_post_field( 'post_name', get_queried_object_id() );
		} elseif ( is_404() ) {
			$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' ); // phpcs:ignore
			$parts = explode( '/', $path );
			$slug  = sanitize_title( end( $parts ) );
			if ( 'strona-glowna' === $slug ) {
				wp_safe_redirect( home_url( '/' ), 301 );
				exit;
			}
		} else {
			return;
		}
		$key = miriam_section_key( $slug );
		if ( ! $key ) {
			return;
		}
		$target = miriam_section_page( $key );
		if ( ! $target || ( is_page() && (int) $target->ID === (int) get_queried_object_id() ) ) {
			return;
		}
		$s      = miriam_sections()[ $key ];
		$anchor = isset( $s['aliases'][ $slug ] ) ? $s['aliases'][ $slug ] : '';
		wp_safe_redirect( get_permalink( $target ) . $anchor, 301 );
		exit;
	},
	4
);

/**
 * Przyciski w edytorze: style pasujące do strony (pomarańczowy, niebieski…).
 */
add_action(
	'init',
	function () {
		$styles = array(
			'orange'  => 'Pomarańczowy',
			'blue'    => 'Niebieski',
			'outline' => 'Kontur niebieski',
			'ghost'   => 'Kontur biały (na ciemnym tle)',
			'white'   => 'Biały',
		);
		foreach ( $styles as $name => $label ) {
			register_block_style( 'core/button', array( 'name' => $name, 'label' => $label ) );
		}
	}
);

add_filter(
	'render_block_core/button',
	function ( $html, $block ) {
		$cls = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
		if ( ! preg_match( '/is-style-(orange|blue|outline|ghost|white)/', $cls, $m ) ) {
			return $html;
		}
		$btn = 'btn btn-' . $m[1] . ( preg_match( '/\bsmall\b/', $cls ) ? ' btn-sm' : '' );
		return preg_replace( '/class="wp-block-button__link[^"]*"/', 'class="' . $btn . '"', $html, 1 );
	},
	10,
	2
);

/* Grupy bez dodatkowej warstwy <div class="wp-block-group__inner-container">. */
add_action(
	'init',
	function () {
		remove_filter( 'render_block_core/group', 'wp_restore_group_inner_container', 10 );
	}
);

/**
 * Krótkie kody: elementy, które aktualizują się same (terminy, formularz,
 * mapa…). W edytorze widać je jako bloki „Krótki kod” — można je przesuwać.
 */
add_shortcode(
	'miriam_terminy',
	function ( $atts ) {
		$atts = shortcode_atts( array( 'rodzaj' => 'kurs' ), $atts );
		$type = sanitize_key( $atts['rodzaj'] );
		$evs  = miriam_get_events( $type, 'kurs' === $type ? 20 : 6 );
		if ( ! $evs ) {
			return '<p class="note">' . ( 'kurs' === $type ? 'Nowe terminy wkrótce.' : 'Najbliższe terminy podamy wkrótce.' ) . '</p>';
		}
		$out = '<div class="courses">';
		if ( 'kurs' === $type ) {
			foreach ( $evs as $c ) {
				$out .= miriam_course_row( $c );
			}
		} else {
			$mg = miriam_months();
			foreach ( $evs as $ev ) {
				$ts   = strtotime( $ev['start'] . ' 12:00:00' );
				$out .= '<div class="course" style="background:var(--light-bg)"><span class="course-date"><strong>' . esc_html( gmdate( 'j', $ts ) ) . '</strong><span>' . esc_html( $mg[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts ) ) . '</span></span><span class="course-title">' . esc_html( miriam_ucfirst( miriam_weekday( $ev['start'] ) ) . ', ' . ( $ev['time'] ? $ev['time'] : '19:00' ) ) . '</span></div>';
			}
		}
		$out .= '</div>';
		if ( in_array( $type, array( 'kurs', 'uzdrowienie', 'silent-disco' ), true ) ) {
			$out .= '<p class="note" style="margin-top:16px"><a href="' . esc_url( miriam_ics_url( $type ) ) . '">Dodaj terminy do kalendarza</a></p>';
		}
		return $out;
	}
);

add_shortcode(
	'miriam_kontakt',
	function () {
		$email = miriam_opt( 'email' );
		$phone = miriam_opt( 'phone' );
		$out   = '';
		if ( $email ) {
			$out .= '<h3 style="font-size:22px;word-break:break-all"><a href="mailto:' . esc_attr( antispambot( $email ) ) . '">' . esc_html( antispambot( $email ) ) . '</a></h3>';
		}
		if ( $phone ) {
			$out .= '<h3 style="font-size:22px"><a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a></h3><p class="small" style="margin-top:-6px">Biuro wspólnoty</p>';
		}
		return $out;
	}
);

add_shortcode(
	'miriam_mapa',
	function () {
		return '<div class="join-map" style="min-height:420px">' . miriam_map_gate( 420 ) . '</div>';
	}
);

add_shortcode(
	'miriam_modlitwa',
	function () {
		return '<section class="sec" style="background:var(--navy)"><div class="wrap">' . miriam_prayer_form() . '</div></section>';
	}
);

add_shortcode(
	'miriam_kalendarz',
	function () {
		ob_start();
		get_template_part( 'template-parts/connect' );
		return ob_get_clean();
	}
);

add_shortcode(
	'miriam_silent_disco',
	function () {
		$l        = miriam_links();
		$sd       = miriam_get_events( 'silent-disco', 12 );
		$months_s = miriam_months( 'short' );
		$feat_id  = miriam_yt_id( miriam_opt( 'featured_video' ) );
		$poster   = miriam_img_url( 'sd_poster' );
		$poster   = $poster ? $poster : miriam_asset( 'silent-disco.webp' );
		ob_start();
		?>
		<div class="sd-grid">
			<figure class="sd-poster"><img src="<?php echo esc_url( $poster ); ?>" alt="Plakat Silent Disco"></figure>
			<div class="sd-dates">
				<span class="fact-label">Terminy</span>
				<?php if ( $sd ) : ?>
					<ul class="date-grid">
						<?php foreach ( array_slice( $sd, 0, 9 ) as $i => $ev ) : $ts = strtotime( $ev['start'] . ' 12:00:00' ); ?>
							<li class="<?php echo 0 === $i ? 'is-next' : ''; ?>"><strong><?php echo esc_html( gmdate( 'j', $ts ) ); ?></strong><span><?php echo esc_html( $months_s[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts ) ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<p class="sd-note">Niedziela: 19:00 Msza Święta, 20:00 Silent Disco. Górny kościół św. Józefa, ul. Filaretów 7. Wstęp wolny.</p>
				<a class="btn btn-white btn-sm" href="<?php echo esc_url( miriam_ics_url( 'silent-disco' ) ); ?>"><?php echo miriam_icon( 'calendar', 18 ); // phpcs:ignore ?>Dodaj do kalendarza</a>
			</div>
			<?php if ( $feat_id ) : ?><div class="sd-video"><?php echo miriam_youtube( $feat_id, miriam_opt( 'featured_title' ) ); // phpcs:ignore ?></div><?php endif; ?>
			<a class="sd-stat" href="<?php echo esc_url( $l['youtube'] ); ?>" target="_blank" rel="noopener"><strong>2 mln+</strong><span>wyświetleń jednej pieśni z naszych uwielbień na YouTube</span><span class="sd-stat-link">Zobacz kanał →</span></a>
		</div>
		<?php
		return ob_get_clean();
	}
);

add_shortcode(
	'miriam_filmy',
	function ( $atts ) {
		$atts    = shortcode_atts( array( 'ile' => 6 ), $atts );
		$feat_id = miriam_yt_id( miriam_opt( 'featured_video' ) );
		$grid    = array();
		foreach ( miriam_latest_videos( 12 ) as $v ) {
			if ( $v['id'] !== $feat_id ) {
				$grid[] = $v;
			}
		}
		if ( ! $grid ) {
			return '';
		}
		$out = '<div class="videos">';
		foreach ( array_slice( $grid, 0, max( 1, (int) $atts['ile'] ) ) as $v ) {
			$out .= miriam_youtube( $v['id'], $v['title'] );
		}
		return $out . '</div>';
	}
);

/**
 * Jednorazowe porządki (wersja 1.9.0):
 *  1. podstrony dostają aktualne adresy (np. /historia/ → /o-nas/, /jakie-kursy-sa-dla-mnie/ → /kursy/),
 *  2. ich treści trafiają do edytora WordPressa (bloki) — poprzednia wersja zostaje w rewizjach,
 *  3. stare, nieużywane strony przechodzą do szkiców z dopiskiem [archiwum] (nic nie jest kasowane).
 */
add_action( 'init', 'miriam_migrate_pages_v1', 20 );
function miriam_migrate_pages_v1() {
	if ( get_option( 'miriam_pages_v1' ) || wp_installing() || defined( 'MIRIAM_NO_MIGRATION' ) ) {
		return;
	}
	update_option( 'miriam_pages_v1', gmdate( 'c' ) );

	$protected = array_filter( array( (int) get_option( 'page_on_front' ), (int) get_option( 'page_for_posts' ) ) );
	$kses      = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses ) {
		kses_remove_filters();
	}
	$log  = array();
	$keep = array();

	foreach ( miriam_sections() as $key => $s ) {
		$page = miriam_section_page( $key );
		$file = get_template_directory() . '/tresci/' . $s['slug'] . '.html';
		if ( ! $page && ! file_exists( $file ) ) {
			continue;
		}
		// Docelowy adres zajęty przez inną (starą) stronę → do archiwum.
		foreach ( miriam_pages_by_slug( $s['slug'], 'any' ) as $other ) {
			if ( ! $page || (int) $other->ID !== (int) $page->ID ) {
				if ( in_array( (int) $other->ID, $protected, true ) ) {
					continue;
				}
				wp_update_post( array( 'ID' => $other->ID, 'post_name' => $s['slug'] . '-archiwum', 'post_status' => 'draft', 'post_title' => '[archiwum] ' . $other->post_title ) );
				$log[] = 'archiwum: ' . $s['slug'] . ' (#' . $other->ID . ')';
			}
		}
		$data = array();
		if ( ! $page ) {
			$data = array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $s['title'] ? $s['title'] : $s['slug'], 'post_name' => $s['slug'] );
		} else {
			$data['ID'] = $page->ID;
			if ( $page->post_name !== $s['slug'] ) {
				$data['post_name'] = $s['slug'];
				$log[]             = 'adres: ' . $page->post_name . ' → ' . $s['slug'];
			}
			if ( $page->post_parent ) {
				$data['post_parent'] = 0;
			}
			if ( $s['title'] && $page->post_title !== $s['title'] ) {
				$data['post_title'] = $s['title'];
			}
		}
		if ( file_exists( $file ) && ( ! $page || ! miriam_is_block_page( $page ) ) ) {
			$data['post_content'] = file_get_contents( $file ); // phpcs:ignore
			$log[]                = 'treść w edytorze: ' . $s['slug'];
		}
		if ( count( $data ) > 1 || ! $page ) {
			$id = $page ? wp_update_post( wp_slash( $data ) ) : wp_insert_post( wp_slash( $data ) );
		} else {
			$id = $page->ID;
		}
		if ( $id && ! is_wp_error( $id ) ) {
			$keep[] = (int) $id;
			update_post_meta( $id, '_wp_page_template', 'default' ); // np. stary szablon Elementora
		}
	}

	// Stare strony, których treść jest już na nowych podstronach → szkice [archiwum].
	$old = array( 'test_kurs', 'test-kurs', 'elementor-1533', 'strona-glowna', 'sample-page' );
	foreach ( miriam_sections() as $s ) {
		$old = array_merge( $old, array_keys( $s['aliases'] ) );
	}
	foreach ( array_unique( $old ) as $slug ) {
		foreach ( miriam_pages_by_slug( $slug ) as $p ) {
			if ( in_array( (int) $p->ID, $keep, true ) || in_array( (int) $p->ID, $protected, true ) ) {
				continue;
			}
			wp_update_post( array( 'ID' => $p->ID, 'post_status' => 'draft', 'post_title' => '[archiwum] ' . $p->post_title ) );
			$log[] = 'do szkiców: ' . $slug . ' (#' . $p->ID . ')';
		}
	}

	if ( $kses ) {
		kses_init_filters();
	}
	update_option( 'miriam_pages_v1_log', $log, false );
}
