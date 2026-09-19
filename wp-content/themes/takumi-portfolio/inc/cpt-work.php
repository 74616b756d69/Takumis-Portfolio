<?php
/**
 * カスタム投稿タイプ「実績」の登録。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

/**
 * 実績（work）を登録する。
 */
function takumi_register_work_post_type() {
	register_post_type(
		'work',
		array(
			'labels'        => array(
				'name'               => '実績',
				'singular_name'      => '実績',
				'add_new'            => '新規追加',
				'add_new_item'       => '実績を追加',
				'edit_item'          => '実績を編集',
				'all_items'          => '実績一覧',
				'menu_name'          => '実績',
				'search_items'       => '実績を検索',
				'not_found'          => '実績が見つかりません',
			),
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'work' ),
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'takumi_register_work_post_type' );

/**
 * 実績一覧は「順序」の小さい順に並べる。
 *
 * page-attributes の「順序」で並び替えられるようにしておくと、
 * 公開日と関係なく見せたい順に組み替えられる。
 *
 * @param WP_Query $query メインクエリ。
 */
function takumi_work_archive_order( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'work' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
	}
}
add_action( 'pre_get_posts', 'takumi_work_archive_order' );
