<?php
/**
 * Nagłówek.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#050A26">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Przejdź do treści</a>

<header class="site-header">
	<div class="wrap">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			$logo_id = get_theme_mod( 'custom_logo' );
			$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : miriam_asset( 'logo.webp' );
			?>
			<img src="<?php echo esc_url( $logo ); ?>" alt="Miriam — Lubelska Szkoła Ewangelizacji" width="300" height="126">
		</a>

		<nav class="main-nav" id="main-nav" aria-label="Główne menu">
			<?php miriam_default_menu(); ?>
		</nav>

		<div class="header-cta">
			<a class="btn btn-orange" href="<?php echo esc_url( home_url( '/#dolacz' ) ); ?>">Wtorek · 19:00</a>
			<button class="menu-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="Otwórz menu">
				<span class="i-open"><?php echo miriam_icon( 'menu', 22 ); ?></span>
			</button>
		</div>
	</div>
</header>
