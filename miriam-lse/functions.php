<?php
/**
 * Miriam LSE — funkcje motywu.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MIRIAM_VERSION', '1.2.0' );

require_once get_template_directory() . '/inc/events.php';
require_once get_template_directory() . '/inc/youtube.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/prayer.php';
require_once get_template_directory() . '/inc/schema.php';
require_once get_template_directory() . '/inc/stories.php';

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'miriam-lse' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 360, 'flex-width' => true, 'flex-height' => true ) );
	register_nav_menus( array(
		'miriam-primary' => 'Menu główne (Miriam)',
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	$css = get_stylesheet_directory() . '/style.css';
	wp_enqueue_style( 'miriam', get_stylesheet_uri(), array(), file_exists( $css ) ? (string) filemtime( $css ) : MIRIAM_VERSION );
	$fonts = get_template_directory_uri() . '/assets/fonts/';
	wp_add_inline_style( 'miriam', "@font-face{font-family:'Bricolage Grotesque';src:url('{$fonts}bricolage-grotesque.woff') format('woff');font-weight:400 800;font-display:swap}@font-face{font-family:'Instrument Sans';src:url('{$fonts}instrument-sans.woff') format('woff');font-weight:400 600;font-display:swap}" );
	$js = get_template_directory() . '/assets/main.js';
	wp_enqueue_script( 'miriam', get_template_directory_uri() . '/assets/main.js', array(), file_exists( $js ) ? (string) filemtime( $js ) : MIRIAM_VERSION, true );
} );

add_action( 'wp_head', function () {
	echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/fonts/bricolage-grotesque.woff' ) . '" as="font" type="font/woff" crossorigin>' . "\n";
}, 2 );

/* Old-theme leftovers that would clash with this design. */
add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 100 );

/**
 * URL of a theme asset.
 */
function miriam_asset( $file ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $file, '/' );
}

/**
 * URL of a community photo (assets/photos/*.webp).
 */
function miriam_photo( $name ) {
	return miriam_asset( 'photos/' . $name . '.webp' );
}

/**
 * Inline style with a background photo for photo sections.
 */
function miriam_photo_style( $name ) {
	return '--img:url(' . esc_url( miriam_photo( $name ) ) . ')';
}

/**
 * URL of a page by slug, or a fallback (anchor on the front page).
 */
function miriam_page_url( $slugs, $fallback ) {
	foreach ( (array) $slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			return get_permalink( $page );
		}
	}
	return $fallback;
}

/**
 * Inline stroke icons.
 */
function miriam_icon( $name, $size = 18 ) {
	$paths = array(
		'play'      => '<path d="M7 4.5v15l12-7.5z" fill="currentColor" stroke="none"/>',
		'pin'       => '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="M6 6l12 12M18 6L6 18"/>',
		'calendar'  => '<rect x="3.5" y="5" width="17" height="15" rx="2.5"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
		'headphones'=> '<path d="M4 15v-3a8 8 0 0 1 16 0v3"/><rect x="3" y="14" width="4.5" height="7" rx="2"/><rect x="16.5" y="14" width="4.5" height="7" rx="2"/>',
	);
	$p = isset( $paths[ $name ] ) ? $paths[ $name ] : '';
	return '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p . '</svg>';
}

/**
 * Lite YouTube embed: thumbnail button that loads the player on click.
 */
function miriam_youtube( $id, $title = '' ) {
	$id = preg_replace( '/[^A-Za-z0-9_-]/', '', $id );
	if ( ! $id ) {
		return '';
	}
	$label = $title ? $title : 'Film na YouTube';
	ob_start();
	?>
	<button type="button" class="yt" data-yt="<?php echo esc_attr( $id ); ?>" aria-label="<?php echo esc_attr( 'Odtwórz: ' . $label ); ?>">
		<?php $th = miriam_yt_thumb( $id ); if ( $th ) : ?><img src="<?php echo esc_url( $th ); ?>" alt="" loading="lazy" width="480" height="360"><?php endif; ?>
		<span class="yt-play"><span><?php echo miriam_icon( 'play', 30 ); ?></span></span>
		<?php if ( $title ) : ?><span class="yt-title"><?php echo esc_html( $title ); ?></span><?php endif; ?>
	</button>
	<?php
	return ob_get_clean();
}

/**
 * Fallback menu when no menu is assigned in Appearance → Menus.
 */
function miriam_default_menu() {
	$items = array(
		array( 'Pierwszy raz', miriam_page_url( array( 'pierwsza-wizyta', 'pierwszy-raz-2', 'pierwszy-raz' ), home_url( '/#pierwszy-raz' ) ) ),
		array( 'Spotkania', miriam_page_url( array( 'spotkania-wspolnoty', 'spotkania' ), home_url( '/#uzdrowienie' ) ) ),
		array( 'Silent Disco', miriam_page_url( array( 'uwielbienie', 'uwielbienie-silent-disco' ), home_url( '/#silent-disco' ) ) ),
		array( 'Kursy', miriam_page_url( array( 'kursy-i-formacja', 'jakie-kursy-sa-dla-mnie' ), home_url( '/#kursy' ) ) ),
		array( 'Historie', miriam_page_url( array( 'swiadectwa' ), home_url( '/#historie' ) ) ),
		array( 'O nas', miriam_page_url( array( 'poznaj-miriam', 'historia' ), home_url( '/#o-nas' ) ) ),
		array( 'Kontakt', miriam_page_url( array( 'kontakt', 'odwiedz-nas' ), home_url( '/#dolacz' ) ) ),
	);
	$current = untrailingslashit( home_url( add_query_arg( array() ) ) );
	echo '<ul>';
	foreach ( $items as $it ) {
		$cls = untrailingslashit( $it[1] ) === $current ? ' class="current-menu-item"' : '';
		echo '<li' . $cls . '><a href="' . esc_url( $it[1] ) . '">' . esc_html( $it[0] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Tytuł i opis strony głównej (także gdy działa wtyczka SEO).
 */
function miriam_front_title() {
	return 'LSE Miriam — Lubelska Szkoła Ewangelizacji · Lublin';
}
function miriam_front_desc() {
	return 'Wspólnota ewangelizacyjna w Lublinie: spotkania we wtorki o 19:00, uwielbienia Silent Disco i kursy Szkoły Ewangelizacji św. Andrzeja. Przyjdź — jest tu miejsce dla Ciebie.';
}
add_filter( 'pre_get_document_title', function ( $t ) {
	return is_front_page() ? miriam_front_title() : $t;
}, 999 );
foreach ( array( 'wpseo_title', 'rank_math/frontend/title', 'aioseo_title', 'wpseo_opengraph_title', 'rank_math/opengraph/facebook/og_title' ) as $hook ) {
	add_filter( $hook, function ( $t ) {
		return is_front_page() ? miriam_front_title() : $t;
	}, 999 );
}
foreach ( array( 'wpseo_metadesc', 'rank_math/frontend/description', 'aioseo_description', 'wpseo_opengraph_desc' ) as $hook ) {
	add_filter( $hook, function ( $d ) {
		return is_front_page() ? miriam_front_desc() : $d;
	}, 999 );
}
add_action( 'wp_head', function () {
	if ( is_front_page() && ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) && ! defined( 'AIOSEO_VERSION' ) ) {
		echo '<meta name="description" content="' . esc_attr( miriam_front_desc() ) . '">' . "\n";
	}
}, 1 );

/**
 * Polish date helpers.
 */
function miriam_ucfirst( $s ) {
	if ( function_exists( 'mb_substr' ) ) {
		return mb_strtoupper( mb_substr( $s, 0, 1 ) ) . mb_substr( $s, 1 );
	}
	return ucfirst( $s );
}

/* Social / contact constants (editable in Customizer where it matters). */
function miriam_links() {
	return array(
		'instagram' => 'https://www.instagram.com/lse_miriam/',
		'facebook'  => 'https://www.facebook.com/LubSEMiriam',
		'youtube'   => 'https://www.youtube.com/@LSEMiriam',
		'spotify'   => 'https://open.spotify.com/artist/7rzeMzZRiC8A39RMqgyK9t',
		'patronite' => 'https://patronite.pl/PlonacyKrzak',
		'linktree'  => 'https://linktr.ee/lsemiriam',
		'maps'      => 'https://maps.google.com/?q=Filaret%C3%B3w+7,+Lublin',
	);
}

/**
 * Zewnętrzne osadzenia (mapa Google, Spotify) ładowane dopiero po kliknięciu —
 * do tego czasu strona nie łączy się z tymi serwisami.
 */
function miriam_embed_gate( $src, $title, $label, $note, $height = 380, $class = '' ) {
	ob_start();
	?>
	<div class="embed-gate <?php echo esc_attr( $class ); ?>" style="min-height:<?php echo (int) $height; ?>px" data-src="<?php echo esc_url( $src ); ?>" data-title="<?php echo esc_attr( $title ); ?>" data-height="<?php echo (int) $height; ?>">
		<button type="button" class="btn btn-white btn-sm embed-gate-btn"><?php echo esc_html( $label ); ?></button>
		<p><?php echo esc_html( $note ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}

function miriam_map_gate( $height = 340 ) {
	return miriam_embed_gate(
		'https://www.google.com/maps?q=Filaret%C3%B3w+7,+20-609+Lublin&z=16&output=embed',
		'Mapa: ul. Filaretów 7, Lublin',
		'Pokaż mapę',
		'Mapa ładuje się z Google Maps dopiero po kliknięciu.',
		$height,
		'is-map'
	);
}

function miriam_privacy_url() {
	$u = get_privacy_policy_url();
	return $u ? $u : miriam_page_url( array( 'polityka-prywatnosci', 'privacy-policy' ), home_url( '/polityka-prywatnosci/' ) );
}

/**
 * Podgląd linku przy udostępnianiu (WhatsApp, Messenger, Facebook).
 */
function miriam_share_image() {
	return miriam_asset( 'share.jpg' );
}
add_action( 'wp_head', function () {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) ) {
		return; // wtyczka SEO wypisuje własne znaczniki; obrazek podajemy niżej przez filtry
	}
	$title = is_front_page() ? miriam_front_title() : wp_get_document_title();
	$desc  = is_front_page() ? miriam_front_desc() : wp_strip_all_tags( get_the_excerpt() ? get_the_excerpt() : miriam_front_desc() );
	$url   = is_singular() ? get_permalink() : home_url( '/' );
	$tags  = array(
		'og:type'        => 'website',
		'og:locale'      => 'pl_PL',
		'og:site_name'   => 'LSE Miriam',
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $url,
		'og:image'       => miriam_share_image(),
		'og:image:width' => '1200',
		'og:image:height'=> '630',
	);
	foreach ( $tags as $k => $v ) {
		echo '<meta property="' . esc_attr( $k ) . '" content="' . esc_attr( $v ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 5 );
add_filter( 'wpseo_opengraph_image', function ( $img ) {
	return ( is_front_page() || ! $img ) ? miriam_share_image() : $img;
}, 999 );
add_filter( 'rank_math/opengraph/facebook/image', function ( $img ) {
	return ( is_front_page() || ! $img ) ? miriam_share_image() : $img;
}, 999 );
add_filter( 'rank_math/opengraph/twitter/image', function ( $img ) {
	return ( is_front_page() || ! $img ) ? miriam_share_image() : $img;
}, 999 );
add_action( 'wpseo_add_opengraph_images', function ( $images ) {
	if ( is_front_page() && is_object( $images ) && method_exists( $images, 'add_image' ) ) {
		$images->add_image( array( 'url' => miriam_share_image(), 'width' => 1200, 'height' => 630 ) );
	}
} );

/**
 * Wiersz kursu (strona główna i podstrona Kursy).
 */
function miriam_course_row( $c ) {
	$l     = miriam_links();
	$cp    = miriam_range_parts( $c['start'], $c['end'] );
	$lines = array_filter( array_map( 'trim', explode( "\n", (string) $c['details'] ) ) );
	ob_start();
	?>
	<div class="course<?php echo $lines ? ' has-details' : ''; ?>">
		<span class="course-date"><strong><?php echo esc_html( $cp[0] ); ?></strong><span><?php echo esc_html( $cp[1] ); ?></span></span>
		<span class="course-title"><?php echo esc_html( $c['title'] ); ?></span>
		<?php if ( $c['link'] ) : ?>
			<a class="btn btn-blue" href="<?php echo esc_url( $c['link'] ); ?>" aria-label="<?php echo esc_attr( 'Zapisz się na kurs ' . $c['title'] ); ?>">Zapisz się</a>
		<?php else : ?>
			<span class="btn is-disabled">Zapisy wkrótce</span>
		<?php endif; ?>
		<?php if ( $lines ) : ?>
			<details class="course-more">
				<summary>Szczegóły: koszt, miejsce, dla kogo</summary>
				<ul>
					<?php
					foreach ( $lines as $line ) {
						$parts = explode( ':', $line, 2 );
						if ( 2 === count( $parts ) && mb_strlen( $parts[0] ) <= 20 ) {
							echo '<li><strong>' . esc_html( trim( $parts[0] ) ) . ':</strong> ' . esc_html( trim( $parts[1] ) ) . '</li>';
						} else {
							echo '<li>' . esc_html( $line ) . '</li>';
						}
					}
					?>
				</ul>
			</details>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/* Nazwa witryny w tytułach podstron (zamiast nazwy wersji testowej). */
add_filter( 'document_title_parts', function ( $parts ) {
	$parts['site'] = 'LSE Miriam';
	unset( $parts['tagline'] );
	return $parts;
}, 999 );
add_filter( 'document_title_separator', function () {
	return '·';
} );

/**
 * Stare i zdublowane adresy podstron → jeden adres docelowy (301).
 * Docelowy jest ten, do którego prowadzi menu.
 */
add_action( 'template_redirect', function () {
	if ( ! is_page() || is_front_page() ) {
		return;
	}
	$slug   = get_post_field( 'post_name', get_queried_object_id() );
	$groups = array(
		array( 'pierwsza-wizyta', 'pierwszy-raz-2', 'pierwszy-raz' ),
		array( 'spotkania-wspolnoty', 'spotkania' ),
		array( 'uwielbienie', 'uwielbienie-silent-disco' ),
		array( 'kursy-i-formacja', 'jakie-kursy-sa-dla-mnie', 'czym-sa-kursy-sesa', 'zapisz-sie' ),
		array( 'poznaj-miriam', 'historia', 'charyzmat', 'pasterz-i-odpowiedzialni' ),
		array( 'kontakt', 'odwiedz-nas' ),
	);
	$anchors = array( 'historia' => '#historia', 'charyzmat' => '#charyzmat', 'pasterz-i-odpowiedzialni' => '#pasterz', 'jakie-kursy-sa-dla-mnie' => '#ktory-kurs', 'zapisz-sie' => '#terminy' );
	foreach ( $groups as $g ) {
		$pos = array_search( $slug, $g, true );
		if ( false === $pos ) {
			continue;
		}
		foreach ( $g as $i => $candidate ) {
			if ( $i >= $pos ) {
				break; // jesteśmy na najważniejszym istniejącym adresie
			}
			$target = get_page_by_path( $candidate );
			if ( $target && 'publish' === $target->post_status ) {
				wp_safe_redirect( get_permalink( $target ) . ( isset( $anchors[ $slug ] ) ? $anchors[ $slug ] : '' ), 301 );
				exit;
			}
		}
	}
}, 5 );

/* Polityka prywatności działa nawet wtedy, gdy w WordPressie nie ma takiej strony. */
add_action( 'template_redirect', function () {
	if ( ! is_404() ) {
		return;
	}
	$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
	$base = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
	if ( $base && 0 === strpos( $path, $base ) ) {
		$path = trim( substr( $path, strlen( $base ) ), '/' );
	}
	if ( 'polityka-prywatnosci' !== $path ) {
		return;
	}
	global $wp_query;
	$wp_query->is_404 = false;
	status_header( 200 );
	add_filter( 'pre_get_document_title', function () {
		return 'Polityka prywatności · LSE Miriam';
	}, 1000 );
	get_header();
	get_template_part( 'template-parts/page', 'privacy' );
	get_footer();
	exit;
}, 1 );

/* Ikona strony (karta przeglądarki, ekran telefonu) — gdy w Ustawieniach nie wybrano własnej. */
add_action( 'wp_head', function () {
	if ( has_site_icon() ) {
		return;
	}
	echo '<link rel="icon" href="' . esc_url( miriam_asset( 'icon-32.png' ) ) . '" sizes="32x32">' . "\n";
	echo '<link rel="icon" href="' . esc_url( miriam_asset( 'icon-512.png' ) ) . '" sizes="512x512">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( miriam_asset( 'icon-180.png' ) ) . '">' . "\n";
}, 3 );
