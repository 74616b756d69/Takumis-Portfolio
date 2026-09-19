<?php
/**
 * 実績のカスタムフィールド。
 *
 * ACF などのプラグインに依存せず、テーマ単体で完結させている。
 * サーバー移行やプラグイン停止で入力欄が消えることがない。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

/**
 * 入力欄の定義を返す。
 *
 * ここに 1 行足せば入力欄・保存・出力がすべて追従する。
 *
 * @return array<string, array{label:string, hint:string, type:string}>
 */
function takumi_work_fields() {
	return array(
		'cat'    => array(
			'label' => 'カテゴリ（英字）',
			'hint'  => '行の上に小さく出る英字ラベル。例: CLIENT WORK / CORPORATE',
			'type'  => 'text',
		),
		'sub'    => array(
			'label' => 'サブタイトル（和文）',
			'hint'  => '作品名の下に出る一行説明。例: 若鯱家 コーポレートサイト',
			'type'  => 'text',
		),
		'metric' => array(
			'label' => 'ハイライト',
			'hint'  => '行の右端に出る一言。数字が入ると強い。例: 8-PERSON TEAM',
			'type'  => 'text',
		),
		'year'   => array(
			'label' => '制作年',
			'hint'  => '例: 2024',
			'type'  => 'text',
		),
		'role'   => array(
			'label' => '担当',
			'hint'  => '例: デザイン / フロントエンド実装',
			'type'  => 'text',
		),
		'stack'  => array(
			'label' => '使用技術',
			'hint'  => '例: HTML, CSS, JavaScript',
			'type'  => 'text',
		),
		'url'    => array(
			'label' => 'サイト URL',
			'hint'  => '公開されている場合のみ。',
			'type'  => 'url',
		),
		'github' => array(
			'label' => 'GitHub URL',
			'hint'  => 'リポジトリが公開されている場合のみ。',
			'type'  => 'url',
		),
	);
}

/**
 * メタ値を取得する。
 *
 * @param int    $post_id 投稿 ID。
 * @param string $key     フィールドキー（接頭辞なし）。
 * @return string
 */
function takumi_work_meta( $post_id, $key ) {
	return (string) get_post_meta( $post_id, '_work_' . $key, true );
}

/**
 * 入力欄のメタボックスを登録する。
 */
function takumi_work_add_meta_box() {
	add_meta_box(
		'takumi-work-detail',
		'実績の詳細',
		'takumi_work_render_meta_box',
		'work',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'takumi_work_add_meta_box' );

/**
 * メタボックスを描画する。
 *
 * @param WP_Post $post 編集中の投稿。
 */
function takumi_work_render_meta_box( $post ) {
	wp_nonce_field( 'takumi_work_save', 'takumi_work_nonce' );

	echo '<style>
		.takumi-work-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-top:8px}
		.takumi-work-grid label{display:block;font-weight:600;margin-bottom:4px}
		.takumi-work-grid input{width:100%}
		.takumi-work-grid .hint{display:block;margin-top:4px;color:#666;font-size:12px}
	</style>';

	echo '<p style="color:#666;margin:0">説明文は本文エディターに書いてください。アイキャッチ画像を設定すると詳細に表示されます。</p>';
	echo '<div class="takumi-work-grid">';

	foreach ( takumi_work_fields() as $key => $field ) {
		$id    = 'work_' . $key;
		$value = takumi_work_meta( $post->ID, $key );

		printf(
			'<div><label for="%1$s">%2$s</label><input type="%3$s" id="%1$s" name="%1$s" value="%4$s"><span class="hint">%5$s</span></div>',
			esc_attr( $id ),
			esc_html( $field['label'] ),
			esc_attr( $field['type'] ),
			esc_attr( $value ),
			esc_html( $field['hint'] )
		);
	}

	echo '</div>';
}

/**
 * 入力値を保存する。
 *
 * @param int $post_id 投稿 ID。
 */
function takumi_work_save_meta( $post_id ) {
	// 自動保存・リビジョン・権限のない操作では何もしない。
	if ( ! isset( $_POST['takumi_work_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['takumi_work_nonce'] ) ), 'takumi_work_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( takumi_work_fields() as $key => $field ) {
		$name = 'work_' . $key;

		if ( ! isset( $_POST[ $name ] ) ) {
			continue;
		}

		$raw = wp_unslash( $_POST[ $name ] );
		$value = ( 'url' === $field['type'] ) ? esc_url_raw( $raw ) : sanitize_text_field( $raw );

		if ( '' === $value ) {
			delete_post_meta( $post_id, '_work_' . $key );
		} else {
			update_post_meta( $post_id, '_work_' . $key, $value );
		}
	}
}
add_action( 'save_post_work', 'takumi_work_save_meta' );
