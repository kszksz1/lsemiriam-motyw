<?php
/**
 * Świadectwa: rozbija treść strony „Historie” (swiadectwa) na osobne historie.
 * Każdy akapit kończący się imieniem (np. „… Kasia”) to jedna historia.
 * Treść nadal edytuje się zwyczajnie w edytorze strony.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function miriam_parse_stories( $html ) {
	$text  = wp_strip_all_tags( preg_replace( '#</p>|<br\s*/?>#i', "\n", (string) $html ) );
	$paras = preg_split( "/\n\s*\n|\n/", $text );
	$out   = array();
	foreach ( $paras as $p ) {
		$p = trim( html_entity_decode( $p, ENT_QUOTES, 'UTF-8' ) );
		if ( mb_strlen( $p ) < 80 ) {
			continue;
		}
		if ( preg_match( '/^(.*[.!?…)])\s+([A-ZĄĆĘŁŃÓŚŹŻ][a-ząćęłńóśźż]{1,20})\.?$/us', $p, $m ) ) {
			$out[] = array( 'text' => trim( $m[1] ), 'name' => $m[2] );
		}
	}
	return $out;
}

function miriam_story_quote( $text, $max = 170 ) {
	$sentences = preg_split( '/(?<=[.!?…])\s+/u', $text );
	$best      = '';
	foreach ( $sentences as $s ) {
		$len = mb_strlen( $s );
		if ( $len < 40 || $len > $max ) {
			continue;
		}
		if ( preg_match( '/\b(Bóg|Boga|Bogiem|Jezus|Mu|wspólnot|uwielbien|wdzięczn)/u', $s ) ) {
			return miriam_ucfirst( $s );
		}
		if ( ! $best ) {
			$best = $s;
		}
	}
	if ( $best ) {
		return miriam_ucfirst( $best );
	}
	// Brak zdania o dobrej długości: bierzemy początek najdłuższego zdania, ucięty na przecinku.
	usort( $sentences, function ( $a, $b ) {
		return mb_strlen( $b ) - mb_strlen( $a );
	} );
	$s   = $sentences[0];
	$cut = mb_substr( $s, 0, $max );
	$pos = mb_strrpos( $cut, ',' );
	$cut = ( false !== $pos && $pos > 40 ) ? mb_substr( $cut, 0, $pos ) : $cut;
	return miriam_ucfirst( trim( $cut ) ) . '…';
}

function miriam_get_stories() {
	$page = get_page_by_path( 'swiadectwa' );
	if ( ! $page || 'publish' !== $page->post_status ) {
		return array();
	}
	return miriam_parse_stories( $page->post_content );
}
