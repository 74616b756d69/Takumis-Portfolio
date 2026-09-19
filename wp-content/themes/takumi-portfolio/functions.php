<?php
/**
 * テーマの基本設定。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

define( 'TAKUMI_THEME_VERSION', '0.1.0' );

require_once get_template_directory() . '/inc/cpt-work.php';
require_once get_template_directory() . '/inc/work-meta.php';

/**
 * テーマがサポートする機能を宣言する。
 */
function takumi_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => 'グローバルナビ',
		)
	);
}
add_action( 'after_setup_theme', 'takumi_theme_setup' );

/**
 * スタイルとスクリプトを読み込む。
 */
function takumi_enqueue_assets() {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	// Google Fonts。旧サイトで使っていた書体をそのまま引き継いでいる。
	wp_enqueue_style(
		'takumi-fonts',
		'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Oswald:wght@400;500;600&family=Zen+Old+Mincho:wght@600;700&display=swap',
		array(),
		null
	);

	// デザイントークンと全体の下地。
	wp_enqueue_style(
		'takumi-style',
		get_stylesheet_uri(),
		array( 'takumi-fonts' ),
		filemtime( $dir . '/style.css' )
	);

	wp_enqueue_style(
		'takumi-work-records',
		$uri . '/assets/css/work-records.css',
		array( 'takumi-style' ),
		filemtime( $dir . '/assets/css/work-records.css' )
	);

	wp_enqueue_style(
		'takumi-marquee',
		$uri . '/assets/css/marquee.css',
		array( 'takumi-style' ),
		filemtime( $dir . '/assets/css/marquee.css' )
	);

	// 図形レイヤー（template-parts/hero-shapes.php）。
	wp_enqueue_style(
		'takumi-hero-shapes',
		$uri . '/assets/css/hero-shapes.css',
		array( 'takumi-style' ),
		filemtime( $dir . '/assets/css/hero-shapes.css' )
	);

	wp_enqueue_script(
		'takumi-hero-shapes',
		$uri . '/assets/js/hero-shapes.js',
		array(),
		filemtime( $dir . '/assets/js/hero-shapes.js' ),
		true
	);

	wp_enqueue_script(
		'takumi-work-records',
		$uri . '/assets/js/work-records.js',
		array(),
		filemtime( $dir . '/assets/js/work-records.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'takumi_enqueue_assets' );

/**
 * Google Fonts の読み込みを早めるための preconnect。
 *
 * @param array  $urls          リソースのヒント。
 * @param string $relation_type ヒントの種類。
 * @return array
 */
function takumi_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'takumi_resource_hints', 10, 2 );
