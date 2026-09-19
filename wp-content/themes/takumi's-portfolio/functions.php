<?php
/**
 * Takumi Portfolio — テーマ機能
 */

show_admin_bar(false);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TAKUMI_VERSION', '2.6.0' );

/* ============================================================
   テーマサポート
   ============================================================ */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus( array(
		'global' => 'グローバルナビゲーション',
	) );
} );

/* ============================================================
   CSS / JS の読み込み
   ============================================================ */
add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'destyle', 'https://cdn.jsdelivr.net/npm/destyle.css@3.0.2/destyle.css', array(), '3.0.2' );
	wp_enqueue_style(
		'takumi-fonts',
		'https://fonts.googleapis.com/css2?family=Unbounded:wght@500;700;800&family=Hanken+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&family=Zen+Kaku+Gothic+New:wght@400;500;700;900&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'takumi-style', $uri . '/assets/css/style.css', array( 'destyle' ), TAKUMI_VERSION );

	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array( 'gsap' ), '3.12.2', true );
	wp_enqueue_script( 'takumi-main', $uri . '/assets/js/main.js', array( 'gsap-scrolltrigger' ), TAKUMI_VERSION, true );

	// 作品データ・フィルタ・詳細パネル
	wp_enqueue_script( 'takumi-works', $uri . '/assets/js/works.js', array(), TAKUMI_VERSION, true );
	wp_add_inline_script(
		'takumi-works',
		'window.TAKUMI_BASE = ' . wp_json_encode( $uri . '/assets/' ) . ';'
		. 'window.TAKUMI_WORK_URL = ' . wp_json_encode( takumi_page_url( 'work' ) ) . ';',
		'before'
	);

	// Three.js 星空背景(全ページ)
	wp_enqueue_script( 'takumi-three-stars', $uri . '/assets/js/three-stars.js', array(), TAKUMI_VERSION, true );
} );

// Three.js 関連は ES Modules として読み込む
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	if ( in_array( $handle, array( 'takumi-three-stars' ), true ) ) {
		$tag = str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}, 10, 2 );

/* ============================================================
   ユーティリティ
   ============================================================ */

/**
 * スラッグからページURLを取得(なければトップへのアンカー)
 */
function takumi_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/#' . $slug );
}

/* ============================================================
   カスタマイザー(プロフィール設定)
   ============================================================ */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'takumi_profile', array(
		'title'    => 'プロフィール設定',
		'priority' => 30,
	) );

	$fields = array(
		'takumi_name_ja' => array( '名前(日本語)', '赤堀 匠海' ),
		'takumi_name_en' => array( '名前(ローマ字)', 'Akahori Takumi' ),
		'takumi_motto'   => array( 'モットー', 'Behind every smile lies effort' ),
		'takumi_email'   => array( 'メールアドレス', 'akahori.t.24kdgn@gmail.com' ),
		'takumi_github'  => array( 'GitHub URL', 'https://github.com/74616b756d69' ),
	);
	foreach ( $fields as $key => $conf ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $conf[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $conf[0],
			'section' => 'takumi_profile',
			'type'    => 'text',
		) );
	}

	$wp_customize->add_setting( 'takumi_bio', array(
		'default'           => '2004年岐阜県生まれ。KADOKAWAドワンゴ情報工科学院に在籍し、Web開発を学習中。産学連携プロジェクトではチームリーダーを経験。',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_bio', array(
		'label'       => '自己紹介文(トップページ用・短め)',
		'description' => 'トップページ/登山モードのProfileセクションに表示されます。',
		'section'     => 'takumi_profile',
		'type'        => 'textarea',
	) );

	// 「プロフィールの要点」として箇条で出す項目
	$fact_fields = array(
		'takumi_fact_base'   => array( '拠点・出身', '2004年 / 岐阜県生まれ' ),
		'takumi_fact_school' => array( '所属', 'KADOKAWAドワンゴ情報工科学院' ),
		'takumi_fact_role'   => array( '担当領域', 'フロントエンド / バックエンド' ),
		'takumi_fact_now'    => array( 'いま力を入れていること', 'WordPressテーマ開発 / Laravel でのWebアプリ制作' ),
	);
	foreach ( $fact_fields as $key => $conf ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $conf[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'       => 'プロフィール要点: ' . $conf[0],
			'description' => '空欄にするとその行は表示されません。',
			'section'     => 'takumi_profile',
			'type'        => 'text',
		) );
	}

	$wp_customize->add_setting( 'takumi_face', array(
		// NOTE: テーマフォルダ名に含まれるアポストロフィがURLエンコードされると "%27s" となり、
		// get_theme_mod() の sprintf プレースホルダー検出に誤って一致し値が壊れるため、
		// ここでは空文字をデフォルトにし、実際のフォールバックは呼び出し側のPHPで行う。
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'takumi_face', array(
		'label'   => 'プロフィール写真',
		'section' => 'takumi_profile',
	) ) );

	/* ---------- SEO / OGP ---------- */
	$wp_customize->add_section( 'takumi_seo', array(
		'title'    => 'SEO / OGP設定',
		'priority' => 32,
	) );

	$wp_customize->add_setting( 'takumi_seo_description', array(
		'default'           => '赤堀匠海(Akahori Takumi)のポートフォリオサイト。Web制作のスキル・経歴・制作実績を紹介しています。',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'takumi_seo_description', array(
		'label'       => 'サイトの説明文(meta description / OGP用)',
		'description' => '検索結果やSNSでのシェア時に表示される説明文です。',
		'section'     => 'takumi_seo',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'takumi_og_image', array(
		// takumi_face と同じ理由でデフォルトは空文字にする(下の header.php 側でフォールバック)
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'takumi_og_image', array(
		'label'       => 'OGP画像(SNSシェア時のサムネイル)',
		'description' => '推奨サイズ: 1200×630px程度',
		'section'     => 'takumi_seo',
	) ) );

	/* ---------- トップページ文言 ---------- */
	$wp_customize->add_section( 'takumi_top_texts', array(
		'title'    => 'トップページ文言設定',
		'priority' => 31,
	) );

	$wp_customize->add_setting( 'takumi_top_tagline', array(
		'default'           => 'フロントエンドからバックエンドまで、想いをかたちにする。',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'takumi_top_tagline', array(
		'label'   => 'キャッチコピー(麓/Topセクション)',
		'section' => 'takumi_top_texts',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'takumi_hero_statement', array(
		'default'           => "BUILDING\nTHE FUTURE,\nONE LINE AT A TIME.",
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_hero_statement', array(
		'label'       => 'トップの大見出し(英字)',
		'description' => '改行ごとに1行として組まれます。2行目が差し色になります。',
		'section'     => 'takumi_top_texts',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'takumi_top_skill_desc', array(
		'default'           => 'フロントエンドからバックエンドまで。HTML/CSSでの制作経験を軸に、Laravel・Django などのフレームワークにも挑戦中です。',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_top_skill_desc', array(
		'label'   => 'Skillセクションの説明文',
		'section' => 'takumi_top_texts',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'takumi_top_work_desc', array(
		'default'           => '個人制作から産学連携・実案件まで。チームリーダーとして指揮したプロジェクトも紹介しています。',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_top_work_desc', array(
		'label'   => 'Workセクションの説明文',
		'section' => 'takumi_top_texts',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'takumi_top_contact_text', array(
		'default'           => '最後までご覧いただきありがとうございました。<br>制作のご依頼・ご相談など、お気軽にご連絡ください。',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'takumi_top_contact_text', array(
		'label'       => 'Contactセクションの案内文',
		'description' => '改行したい箇所には &lt;br&gt; を挿入できます。',
		'section'     => 'takumi_top_texts',
		'type'        => 'textarea',
	) );
} );

/* ============================================================
   カスタム投稿タイプ: 制作実績 (works)
   ============================================================ */
add_action( 'init', function () {
	register_post_type( 'works', array(
		'labels' => array(
			'name'          => '制作実績',
			'singular_name' => '制作実績',
			'add_new_item'  => '制作実績を追加',
			'edit_item'     => '制作実績を編集',
		),
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-portfolio',
		'menu_position'=> 5,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'skill', array(
		'labels' => array(
			'name'          => 'スキル',
			'singular_name' => 'スキル',
			'add_new_item'  => 'スキルを追加',
			'edit_item'     => 'スキルを編集',
		),
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'has_archive'        => false,
		'menu_icon'          => 'dashicons-awards',
		'menu_position'      => 6,
		'supports'           => array( 'title', 'page-attributes' ),
		'show_in_rest'       => true,
	) );

	register_post_type( 'career', array(
		'labels' => array(
			'name'          => '経歴',
			'singular_name' => '経歴',
			'add_new_item'  => '経歴を追加',
			'edit_item'     => '経歴を編集',
		),
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'has_archive'        => false,
		'menu_icon'          => 'dashicons-clock',
		'menu_position'      => 7,
		'supports'           => array( 'title', 'page-attributes' ),
		'show_in_rest'       => true,
	) );
} );

/* ---------- メタボックス共通処理 ---------- */

/**
 * フィールド定義から add_meta_box 用のレンダリング関数を生成する
 */
function takumi_meta_box_renderer( $fields, $prefix ) {
	return function ( $post ) use ( $fields, $prefix ) {
		wp_nonce_field( "takumi_{$prefix}_meta", "takumi_{$prefix}_meta_nonce" );
		foreach ( $fields as $key => $conf ) {
			$value = get_post_meta( $post->ID, '_takumi_' . $key, true );
			$type  = isset( $conf[2] ) ? $conf[2] : 'text';
			printf(
				'<p><label for="takumi_%1$s"><strong>%2$s</strong><br><small>%3$s</small></label><br>',
				esc_attr( $key ),
				esc_html( $conf[0] ),
				esc_html( $conf[1] )
			);
			if ( 'textarea' === $type ) {
				printf( '<textarea id="takumi_%1$s" name="takumi_%1$s" rows="4" style="width:100%%">%2$s</textarea>', esc_attr( $key ), esc_textarea( $value ) );
			} else {
				printf( '<input type="text" id="takumi_%1$s" name="takumi_%1$s" value="%2$s" style="width:100%%">', esc_attr( $key ), esc_attr( $value ) );
			}
			echo '</p>';
		}
	};
}

/**
 * メタボックスの入力値を保存する
 */
function takumi_save_meta_fields( $post_id, $fields, $prefix ) {
	if ( ! isset( $_POST[ "takumi_{$prefix}_meta_nonce" ] ) ||
		! wp_verify_nonce( $_POST[ "takumi_{$prefix}_meta_nonce" ], "takumi_{$prefix}_meta" ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( $fields as $key => $conf ) {
		if ( isset( $_POST[ 'takumi_' . $key ] ) ) {
			$type  = isset( $conf[2] ) ? $conf[2] : 'text';
			$value = wp_unslash( $_POST[ 'takumi_' . $key ] );
			update_post_meta( $post_id, '_takumi_' . $key, 'textarea' === $type ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) );
		}
	}
}

/* ---------- 制作実績メタボックス ---------- */
const TAKUMI_WORK_FIELDS = array(
	'meta'  => array( 'サブタイトル', '例: 個人制作・2025' ),
	'year'  => array( '制作年', '例: 2025' ),
	'type'  => array( '種別(カンマ区切り)', 'front / back / design' ),
	'tech'  => array( '技術(カンマ区切り)', '例: html/css,js,php' ),
	'role'  => array( '担当', '例: デザイン・コーディング全て' ),
	'url'   => array( '公開URL', '空欄可' ),
	'github'=> array( 'GitHub URL', '空欄可' ),
	'icons' => array( 'スキルアイコン(カンマ区切り)', 'skillicons.dev のID。例: html,css,js' ),
	'label' => array( 'レコード見出し(英字)', 'Work一覧の索引に表示。空欄なら技術から自動生成。例: TEAM DEVELOPMENT' ),
	'metric'=> array( 'レコード指標(英字)', 'Work一覧の索引に表示。空欄なら担当から自動生成。例: 8-PERSON TEAM' ),
);

/* ---------- スキルメタボックス ---------- */
const TAKUMI_SKILL_FIELDS = array(
	'icon'       => array( 'アイコン(skillicons.dev のID)', '例: html' ),
	'experience' => array( '経験', '例: 4 yrs / Learning' ),
	'percent'    => array( '習熟度(0-100)', '例: 90' ),
	'note'       => array( '補足', '例: Webサイト制作で使用' ),
);

/* ---------- 経歴メタボックス ---------- */
const TAKUMI_CAREER_FIELDS = array(
	'date'        => array( '日付', '例: 2021.04' ),
	'description' => array( '説明', '例: 高校在学中に自身のブログサイトの制作を経験。', 'textarea' ),
);

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'takumi_work_meta', '実績情報', takumi_meta_box_renderer( TAKUMI_WORK_FIELDS, 'work' ), 'works', 'normal', 'high' );
	add_meta_box( 'takumi_skill_meta', 'スキル情報', takumi_meta_box_renderer( TAKUMI_SKILL_FIELDS, 'skill' ), 'skill', 'normal', 'high' );
	add_meta_box( 'takumi_career_meta', '経歴情報', takumi_meta_box_renderer( TAKUMI_CAREER_FIELDS, 'career' ), 'career', 'normal', 'high' );
} );

add_action( 'save_post_works', function ( $post_id ) {
	takumi_save_meta_fields( $post_id, TAKUMI_WORK_FIELDS, 'work' );
} );
add_action( 'save_post_skill', function ( $post_id ) {
	takumi_save_meta_fields( $post_id, TAKUMI_SKILL_FIELDS, 'skill' );
} );
add_action( 'save_post_career', function ( $post_id ) {
	takumi_save_meta_fields( $post_id, TAKUMI_CAREER_FIELDS, 'career' );
} );

/* ---------- 制作実績の取得・カード出力 ---------- */

/**
 * 制作実績のカードを出力する
 */
function takumi_render_work_card( $post ) {
	$id     = $post->ID;
	$meta   = fn( $key ) => get_post_meta( $id, '_takumi_' . $key, true );
	$thumb  = get_the_post_thumbnail_url( $id, 'large' );
	$techs  = array_filter( array_map( 'trim', explode( ',', (string) $meta( 'tech' ) ) ) );
	$types  = array_filter( array_map( 'trim', explode( ',', (string) $meta( 'type' ) ) ) );
	$icons  = array_filter( array_map( 'trim', explode( ',', (string) $meta( 'icons' ) ) ) );

	// 本文内の画像 + アイキャッチをギャラリーに
	$images = $thumb ? array( $thumb ) : array();
	if ( preg_match_all( '/<img[^>]+src="([^"]+)"/', $post->post_content, $m ) ) {
		$images = array_values( array_unique( array_merge( $images, $m[1] ) ) );
	}
	?>
	<article class="work-row" id="<?php echo esc_attr( takumi_work_anchor( $post ) ); ?>"
		data-year="<?php echo esc_attr( $meta( 'year' ) ); ?>"
		data-tech="<?php echo esc_attr( implode( ',', $techs ) ); ?>"
		data-type="<?php echo esc_attr( implode( ',', $types ) ); ?>">
		<div class="work-row__gallery">
			<?php foreach ( $images as $src ) : ?>
				<img src="<?php echo esc_url( $src ); ?>" alt="<?php the_title_attribute( array( 'post' => $id ) ); ?>" loading="lazy">
			<?php endforeach; ?>
		</div>
		<div class="work-row__body">
			<h3><?php echo esc_html( get_the_title( $id ) ); ?></h3>
			<p class="work-row__meta"><?php echo esc_html( $meta( 'meta' ) ); ?></p>
			<?php if ( $meta( 'role' ) ) : ?>
				<p class="work-row__role"><strong>担当:</strong> <?php echo esc_html( $meta( 'role' ) ); ?></p>
			<?php endif; ?>
			<div class="work-row__desc"><?php echo wp_kses_post( wpautop( $post->post_content ) ); ?></div>
			<div class="work-row__tags">
				<?php foreach ( $techs as $tech ) : ?>
					<span><?php echo esc_html( $tech ); ?></span>
				<?php endforeach; ?>
			</div>
			<?php if ( $icons ) : ?>
				<div class="work-row__icons">
					<?php foreach ( $icons as $icon ) : ?>
						<img src="<?php echo esc_url( takumi_skill_icon_url( $icon ) ); ?>" alt="<?php echo esc_attr( $icon ); ?>" loading="lazy">
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="work-row__links">
				<?php if ( $meta( 'url' ) ) : ?>
					<a class="btn" href="<?php echo esc_url( $meta( 'url' ) ); ?>" target="_blank" rel="noopener">Visit Site</a>
				<?php endif; ?>
				<?php if ( $meta( 'github' ) ) : ?>
					<a class="btn btn--gold" href="<?php echo esc_url( $meta( 'github' ) ); ?>" target="_blank" rel="noopener">GitHub</a>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php
}

/* ---------- Field Records(制作実績インデックス) ---------- */

/**
 * 実績カードへのアンカーIDを返す
 */
function takumi_work_anchor( $post ) {
	// 日本語スラッグはURLエンコードされてCSSセレクタに使えないため、ASCIIのときだけ採用する
	$slug = (string) $post->post_name;
	if ( preg_match( '/^[a-z0-9\-_]+$/', $slug ) ) {
		return 'work-' . $slug;
	}
	return 'work-' . $post->ID;
}

/**
 * レコード行に出す英字ラベルと指標を組み立てる
 * (メタ未入力なら技術・担当から自動生成する)
 */
function takumi_work_record_parts( $post ) {
	$meta = fn( $key ) => (string) get_post_meta( $post->ID, '_takumi_' . $key, true );

	$label = $meta( 'label' );
	if ( ! $label ) {
		$techs = array_filter( array_map( 'trim', explode( ',', $meta( 'tech' ) ) ) );
		$label = $techs ? mb_strtoupper( implode( ' × ', array_slice( $techs, 0, 3 ) ) ) : 'WORK';
	}

	$metric = $meta( 'metric' );
	if ( ! $metric ) {
		$roles  = array_filter( array_map( 'trim', preg_split( '/[・、\/]/u', $meta( 'role' ) ) ) );
		$metric = $roles ? mb_strtoupper( reset( $roles ) ) : $meta( 'year' );
	}

	return array( $label, $metric, $meta( 'meta' ) );
}

/**
 * 制作実績インデックスの1行を出力する
 */
function takumi_render_work_record( $post, $index, $base = '' ) {
	list( $label, $metric, $sub ) = takumi_work_record_parts( $post );
	?>
	<a class="work-record" href="<?php echo esc_url( $base . '#' . takumi_work_anchor( $post ) ); ?>">
		<span class="record-index"><?php echo esc_html( sprintf( '%02d', $index ) ); ?></span>
		<span class="record-title">
			<small><?php echo esc_html( $label ); ?></small>
			<strong><?php echo esc_html( get_the_title( $post ) ); ?></strong>
			<?php if ( $sub ) : ?><em><?php echo esc_html( $sub ); ?></em><?php endif; ?>
		</span>
		<span class="record-metric"><?php echo esc_html( $metric ); ?></span>
		<span class="record-arrow" aria-hidden="true"><?php takumi_arrow_icon(); ?></span>
	</a>
	<?php
}

/**
 * プロフィールの要点(カスタマイザーで編集)を定義リストで出力する
 */
function takumi_render_profile_facts() {
	$facts = array(
		'BASE'  => get_theme_mod( 'takumi_fact_base', '2004年 / 岐阜県生まれ' ),
		'SCHOOL' => get_theme_mod( 'takumi_fact_school', 'KADOKAWAドワンゴ情報工科学院' ),
		'ROLE'  => get_theme_mod( 'takumi_fact_role', 'フロントエンド / バックエンド' ),
		'NOW'   => get_theme_mod( 'takumi_fact_now', 'WordPressテーマ開発 / Laravel でのWebアプリ制作' ),
	);
	$facts = array_filter( $facts );
	if ( ! $facts ) {
		return;
	}
	?>
	<dl class="profile-facts">
		<?php foreach ( $facts as $label => $value ) : ?>
			<div class="profile-facts__row">
				<dt><?php echo esc_html( $label ); ?></dt>
				<dd><?php echo esc_html( $value ); ?></dd>
			</div>
		<?php endforeach; ?>
	</dl>
	<?php
}

/**
 * Statement セクションに散らす装飾図形
 * demo/scroll-*.html のイラストを取り込んだもの。
 * 動きの中身は CSS（style.css の「Statement の装飾イラスト」）側で定義し、
 * ここでは形とグラデーションの定義だけを持たせる。
 * 装飾なので aria-hidden、キーボード対象外にするため focusable="false" を付ける。
 */
function takumi_shape_svg( $name ) {
	$shapes = array();

	/* ダイヤモンド（オレンジ → ピンク） */
	$shapes['diamond'] = <<<'SVG'
<svg class="shape-svg shape-svg--diamond" viewBox="0 0 260 340" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
	<defs>
		<linearGradient id="dg-a" gradientUnits="userSpaceOnUse" x1="44" y1="20" x2="216" y2="292"><stop offset="0" class="shape-s1"/><stop offset="1" class="shape-s2"/></linearGradient>
		<linearGradient id="dg-b" gradientUnits="userSpaceOnUse" x1="216" y1="20" x2="44" y2="292"><stop offset="0" class="shape-s3"/><stop offset="1" class="shape-s2"/></linearGradient>
		<linearGradient id="dg-c" gradientUnits="userSpaceOnUse" x1="130" y1="20" x2="130" y2="292"><stop offset="0" class="shape-s3"/><stop offset="0.55" class="shape-s1"/><stop offset="1" class="shape-s2"/></linearGradient>
		<linearGradient id="dg-d" gradientUnits="userSpaceOnUse" x1="44" y1="292" x2="216" y2="20"><stop offset="0" class="shape-s2"/><stop offset="1" class="shape-s1"/></linearGradient>
		<radialGradient id="dg-e" gradientUnits="userSpaceOnUse" cx="120" cy="86" r="180"><stop offset="0" class="shape-s3"/><stop offset="0.5" class="shape-s1"/><stop offset="1" class="shape-s2"/></radialGradient>
		<linearGradient id="dg-f" gradientUnits="userSpaceOnUse" x1="88" y1="80" x2="172" y2="212"><stop offset="0" class="shape-s4"/><stop offset="1" class="shape-s2"/></linearGradient>
		<linearGradient id="dg-shine" gradientUnits="userSpaceOnUse" x1="100" y1="0" x2="160" y2="0">
			<stop offset="0" stop-color="#fff" stop-opacity="0"/>
			<stop offset="0.38" stop-color="#fff" stop-opacity="0.55"/>
			<stop offset="0.5" stop-color="#fff" stop-opacity="0.95"/>
			<stop offset="0.62" stop-color="#fff" stop-opacity="0.55"/>
			<stop offset="1" stop-color="#fff" stop-opacity="0"/>
		</linearGradient>
		<filter id="dg-grain" x="0" y="0" width="100%" height="100%" color-interpolation-filters="sRGB">
			<feTurbulence type="fractalNoise" baseFrequency="0.7" numOctaves="3" stitchTiles="stitch"/>
			<feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.32 0.32 0.32 0 -0.38"/>
		</filter>
		<clipPath id="dg-clip"><path d="M130,20 L216,146 L130,292 L44,146 Z"/></clipPath>
	</defs>
	<g class="shape-float"><g class="shape-swing"><g class="shape-turn">
		<g style="isolation: isolate">
			<path class="shape-facet" fill="url(#dg-c)" d="M130,20 L44,146 L88,146 Z"/>
			<path class="shape-facet" fill="url(#dg-e)" d="M130,20 L88,146 L130,80 Z"/>
			<path class="shape-facet" fill="url(#dg-a)" d="M130,20 L130,80 L172,146 Z"/>
			<path class="shape-facet" fill="url(#dg-b)" d="M130,20 L172,146 L216,146 Z"/>
			<path class="shape-facet" fill="url(#dg-d)" d="M44,146 L130,292 L130,212 Z"/>
			<path class="shape-facet" fill="url(#dg-c)" d="M44,146 L130,212 L88,146 Z"/>
			<path class="shape-facet" fill="url(#dg-a)" d="M172,146 L130,212 L130,292 Z"/>
			<path class="shape-facet" fill="url(#dg-e)" d="M172,146 L130,292 L216,146 Z"/>
			<path class="shape-facet" fill="url(#dg-f)" d="M88,146 L130,80 L130,146 Z"/>
			<path class="shape-facet" fill="url(#dg-b)" d="M130,80 L172,146 L130,146 Z"/>
			<path class="shape-facet" fill="url(#dg-a)" d="M88,146 L130,146 L130,212 Z"/>
			<path class="shape-facet" fill="url(#dg-f)" d="M130,146 L172,146 L130,212 Z"/>
			<g clip-path="url(#dg-clip)" style="mix-blend-mode: screen">
				<g transform="rotate(-18 130 156)"><g class="shape-shine"><rect x="100" y="-150" width="60" height="620" fill="url(#dg-shine)"/></g></g>
			</g>
			<g class="shape-grain" clip-path="url(#dg-clip)"><rect x="0" y="0" width="260" height="340" filter="url(#dg-grain)"/></g>
		</g>
		<g transform="translate(130 20)"><g class="shape-spark shape-spark--sync"><path d="M0,-16 Q3,-3 16,0 Q3,3 0,16 Q-3,3 -16,0 Q-3,-3 0,-16 Z" fill="#fff"/></g></g>
	</g></g></g>
	<g transform="translate(34 62)"><g class="shape-spark shape-spark--idle"><path d="M0,-11 Q2,-2 11,0 Q2,2 0,11 Q-2,2 -11,0 Q-2,-2 0,-11 Z" fill="var(--c1)"/></g></g>
	<g transform="translate(228 108)"><g class="shape-spark shape-spark--idle shape-spark--idle-2"><path d="M0,-8 Q2,-2 8,0 Q2,2 0,8 Q-2,2 -8,0 Q-2,-2 0,-8 Z" fill="var(--c2)"/></g></g>
	<g transform="translate(58 292)"><g class="shape-spark shape-spark--idle shape-spark--idle-3"><path d="M0,-9 Q2,-2 9,0 Q2,2 0,9 Q-2,2 -9,0 Q-2,-2 0,-9 Z" fill="var(--c4)"/></g></g>
</svg>
SVG;

	/* リング（グリーン → ブルー）— 3本の円弧が別々の速度で回り続ける */
	$shapes['ring'] = <<<'SVG'
<svg class="shape-svg shape-svg--ring" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
	<defs>
		<linearGradient id="ar-a" gradientUnits="userSpaceOnUse" x1="38" y1="40" x2="262" y2="250"><stop offset="0" class="shape-s1"/><stop offset="0.5" class="shape-s2"/><stop offset="1" class="shape-s3"/></linearGradient>
		<linearGradient id="ar-b" gradientUnits="userSpaceOnUse" x1="240" y1="70" x2="60" y2="230"><stop offset="0" class="shape-s3"/><stop offset="0.5" class="shape-s2"/><stop offset="1" class="shape-s4"/></linearGradient>
		<linearGradient id="ar-c" gradientUnits="userSpaceOnUse" x1="90" y1="220" x2="215" y2="85"><stop offset="0" class="shape-s2"/><stop offset="0.5" class="shape-s1"/><stop offset="1" class="shape-s2"/></linearGradient>
		<filter id="ar-grain" x="-20%" y="-20%" width="140%" height="140%" color-interpolation-filters="sRGB">
			<feTurbulence type="fractalNoise" baseFrequency="0.75" numOctaves="3" stitchTiles="stitch" result="noise"/>
			<feColorMatrix in="noise" type="matrix" result="grain" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.3 0.3 0.3 0 -0.35"/>
			<feComposite in="grain" in2="SourceGraphic" operator="in" result="grainClipped"/>
			<feBlend in="grainClipped" in2="SourceGraphic" mode="multiply"/>
		</filter>
	</defs>
	<g class="shape-rot shape-rot--1"><path class="shape-arc shape-arc--1" filter="url(#ar-grain)" d="M130.55,39.70 A112,112 0 1 1 38.43,140.24"/></g>
	<g class="shape-rot shape-rot--2"><path class="shape-arc shape-arc--2" filter="url(#ar-grain)" d="M192.00,222.75 A84,84 0 1 1 203.99,85.65"/></g>
	<g class="shape-rot shape-rot--3"><path class="shape-arc shape-arc--3" filter="url(#ar-grain)" d="M92.88,160.07 A58,58 0 1 1 200.23,179.00"/></g>
</svg>
SVG;

	/* 星（パープル → ピンク）— 大小3つが時間差で瞬く */
	$shapes['star'] = <<<'SVG'
<svg class="shape-svg shape-svg--star" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
	<defs>
		<linearGradient id="st-a" gradientUnits="userSpaceOnUse" x1="-72" y1="-88" x2="66" y2="82"><stop offset="0" class="shape-s1"/><stop offset="0.42" class="shape-s2"/><stop offset="1" class="shape-s3"/></linearGradient>
		<radialGradient id="st-b" gradientUnits="userSpaceOnUse" cx="0" cy="0" r="40"><stop offset="0" class="shape-s4"/><stop offset="1" class="shape-s3"/></radialGradient>
		<linearGradient id="st-c" gradientUnits="userSpaceOnUse" x1="-26" y1="-26" x2="26" y2="26"><stop offset="0" class="shape-s2"/><stop offset="1" class="shape-s1"/></linearGradient>
		<filter id="st-grain" x="-20%" y="-20%" width="140%" height="140%" color-interpolation-filters="sRGB">
			<feTurbulence type="fractalNoise" baseFrequency="0.75" numOctaves="3" stitchTiles="stitch" result="noise"/>
			<feColorMatrix in="noise" type="matrix" result="grain" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.32 0.32 0.32 0 -0.38"/>
			<feComposite in="grain" in2="SourceGraphic" operator="in" result="grainClipped"/>
			<feBlend in="grainClipped" in2="SourceGraphic" mode="multiply"/>
		</filter>
	</defs>
	<g transform="translate(150 150)"><g class="shape-blink"><g class="shape-swing">
		<path filter="url(#st-grain)" fill="url(#st-a)" d="M0,-118 C7,-40 40,-7 118,0 C40,7 7,40 0,118 C-7,40 -40,7 -118,0 C-40,-7 -7,-40 0,-118 Z"/>
	</g></g></g>
	<g transform="translate(252 68)"><g class="shape-blink shape-blink--2"><g class="shape-swing shape-swing--2">
		<path filter="url(#st-grain)" fill="url(#st-b)" d="M0,-38 C2,-13 13,-2 38,0 C13,2 2,13 0,38 C-2,13 -13,2 -38,0 C-13,-2 -2,-13 0,-38 Z"/>
	</g></g></g>
	<g transform="translate(56 240)"><g class="shape-blink shape-blink--3"><g class="shape-swing shape-swing--3">
		<path filter="url(#st-grain)" fill="url(#st-c)" d="M0,-26 C1.5,-9 9,-1.5 26,0 C9,1.5 1.5,9 0,26 C-1.5,9 -9,1.5 -26,0 C-9,-1.5 -1.5,-9 0,-26 Z"/>
	</g></g></g>
</svg>
SVG;

	if ( isset( $shapes[ $name ] ) ) {
		echo $shapes[ $name ]; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/**
 * うねる区切り線（demo/flow-lines.html の SECTION DIVIDER 由来）
 * 線そのものをマスクにして、内側で繰り返しグラデーションの面を横に流している。
 * ページ内に複数出すので、id はインスタンスごとに連番を振って衝突を避ける。
 *
 * @param string $modifier 追加クラス（例: 'flow-line--flip' で上下反転）
 */
function takumi_flow_line( $modifier = '' ) {
	static $seq = 0;
	++$seq;
	$p = 'fl' . $seq; // この SVG 専用の id 接頭辞

	$class = 'flow-line' . ( $modifier ? ' ' . $modifier : '' );
	?>
	<svg class="<?php echo esc_attr( $class ); ?>" viewBox="0 36 1200 52" xmlns="http://www.w3.org/2000/svg" role="presentation" aria-hidden="true" focusable="false">
		<defs>
			<!-- 端の色を始点と同じにすると、繰り返しても色の段差が出ない -->
			<linearGradient id="<?php echo esc_attr( $p ); ?>-grad" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="600" y2="0" spreadMethod="repeat">
				<stop offset="0" class="s-g1"/>
				<stop offset="0.33" class="s-g2"/>
				<stop offset="0.66" class="s-g3"/>
				<stop offset="1" class="s-g1"/>
			</linearGradient>
			<linearGradient id="<?php echo esc_attr( $p ); ?>-grad2" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="600" y2="0" spreadMethod="repeat">
				<stop offset="0" class="s-g4"/>
				<stop offset="0.5" class="s-g1"/>
				<stop offset="1" class="s-g4"/>
			</linearGradient>
			<!-- 線そのものをマスクに（白＝見える） -->
			<mask id="<?php echo esc_attr( $p ); ?>-m1">
				<path class="wave w1" stroke="#fff" d="M0,60 q75,-34 150,0 t150,0 t150,0 t150,0 t150,0 t150,0 t150,0 t150,0"/>
			</mask>
			<mask id="<?php echo esc_attr( $p ); ?>-m2">
				<path class="wave w2" stroke="#fff" d="M0,66 q100,30 200,0 t200,0 t200,0 t200,0 t200,0 t200,0"/>
			</mask>
			<mask id="<?php echo esc_attr( $p ); ?>-m3">
				<path class="wave w3" stroke="#fff" d="M0,52 q60,-16 120,0 t120,0 t120,0 t120,0 t120,0 t120,0 t120,0 t120,0 t120,0 t120,0"/>
			</mask>
		</defs>
		<g mask="url(#<?php echo esc_attr( $p ); ?>-m1)">
			<g class="flow"><rect x="-600" y="0" width="2400" height="120" fill="url(#<?php echo esc_attr( $p ); ?>-grad)"/></g>
		</g>
		<g mask="url(#<?php echo esc_attr( $p ); ?>-m2)" opacity="0.75">
			<g class="flow flow--rev"><rect x="-600" y="0" width="2400" height="120" fill="url(#<?php echo esc_attr( $p ); ?>-grad2)"/></g>
		</g>
		<g mask="url(#<?php echo esc_attr( $p ); ?>-m3)" opacity="0.55">
			<g class="flow flow--slow"><rect x="-600" y="0" width="2400" height="120" fill="url(#<?php echo esc_attr( $p ); ?>-grad)"/></g>
		</g>
	</svg>
	<?php
}

/**
 * 散らす装飾図形1つぶん（demo/hero-shapes.html 由来）
 *
 * デモは 1 枚の大きな SVG に 7 個を配置していたが、セクションごとに
 * 好きな数・好きな位置へ置けるよう「図形1つ＝1ステッカー」に分解してある。
 * 配置と視差は外側の span（CSS）、浮遊と回転は SVG の中（CSS アニメーション）が担当。
 *
 * @param string $shape circle|triangle|wave|ring|cross|square|zigzag
 * @param array  $args  x, y   … セクション内の位置（% 文字列）
 *                      size   … 幅（CSS の長さ。clamp() 可）
 *                      depth  … 視差の強さ。大きいほどマウスに追従する
 *                      delay  … アニメーションの開始ずらし（負値で途中から）
 *                      class  … 追加クラス（例: 'is-sm-hidden'）
 */
function takumi_shape_deco( $shape, $args = array() ) {
	// viewBox は図形ごとに実寸へ合わせる（原点中心に描いてあるので左上は負の値）
	$shapes = array(
		'circle'   => array( '-56 -56 112 112', '<circle class="fill-1" r="46"/>', 'spin' ),
		'triangle' => array( '-62 -62 124 124', '<path class="fill-2" d="M0,-52 L47,30 Q52,40 40,40 L-40,40 Q-52,40 -47,30 Z"/>', 'spin' ),
		'wave'     => array( '-88 -34 176 68', '<path class="line-1" d="M-78,0 q19.5,-26 39,0 t39,0 t39,0"/>', 'wobble' ),
		'ring'     => array( '-44 -44 88 88', '<circle class="line-ink" r="35" stroke-dasharray="9 13"/>', 'spin' ),
		'cross'    => array( '-40 -40 80 80', '<path class="line-ink" d="M0,-30 V30 M-30,0 H30"/>', 'spin' ),
		'square'   => array( '-40 -40 80 80', '<rect class="fill-3" x="-30" y="-30" width="60" height="60" rx="16"/>', 'spin' ),
		'zigzag'   => array( '-55 -28 110 56', '<path class="line-3" d="M-45,14 L-22,-14 L0,14 L22,-14 L45,14"/>', 'wobble' ),
	);

	if ( ! isset( $shapes[ $shape ] ) ) {
		return;
	}

	list( $viewbox, $body, $turn ) = $shapes[ $shape ];

	$args = wp_parse_args( $args, array(
		'x'     => '50%',
		'y'     => '50%',
		'size'  => 'clamp(34px, 5vw, 68px)',
		'depth' => 24,
		'delay' => '0s',
		'class' => '',
	) );

	$style = sprintf(
		'--x:%s;--y:%s;--size:%s;--depth:%d;--delay:%s',
		$args['x'],
		$args['y'],
		$args['size'],
		(int) $args['depth'],
		$args['delay']
	);

	$class = 'shape-deco shape-deco--' . $shape;
	if ( $args['class'] ) {
		$class .= ' ' . $args['class'];
	}
	?>
	<span class="<?php echo esc_attr( $class ); ?>" style="<?php echo esc_attr( $style ); ?>" aria-hidden="true">
		<svg viewBox="<?php echo esc_attr( $viewbox ); ?>" xmlns="http://www.w3.org/2000/svg" role="presentation" aria-hidden="true" focusable="false">
			<g class="shape__float"><g class="shape__<?php echo esc_attr( $turn ); ?>"><?php echo $body; // phpcs:ignore WordPress.Security.EscapeOutput ?></g></g>
		</svg>
	</span>
	<?php
}

/**
 * セクションの隅に置く大きめのイラスト（demo/scroll-*.html 由来）
 *
 * トップでは Statement の帯の中を流れている diamond / ring / star を、
 * 下層ページでは「1セクションに1つだけ」背面へ沈めて使う。
 * 形と動きは takumi_shape_svg() と style.css のものをそのまま使い、
 * ここは置き場所（位置・大きさ）だけを持つ。
 *
 * @param string $name diamond|ring|star
 * @param array  $args x, y  … セクション内の位置（% 文字列）
 *                     size  … 幅（CSS の長さ。clamp() 可）
 */
function takumi_section_art( $name, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'x'    => '92%',
		'y'    => '50%',
		'size' => 'clamp(110px, 14vw, 220px)',
	) );

	$style = sprintf( '--x:%s;--y:%s;--size:%s', $args['x'], $args['y'], $args['size'] );
	?>
	<span class="section-art section-art--<?php echo esc_attr( $name ); ?>"
		style="<?php echo esc_attr( $style ); ?>" aria-hidden="true">
		<?php takumi_shape_svg( $name ); ?>
	</span>
	<?php
}

/**
 * セクション背面に図形を散らすレイヤー
 * バリエーションごとに図形・位置・大きさを変え、同じ絵面の繰り返しにならないようにする。
 * 画面が狭いと本文に重なるので、内側寄りのものは is-sm-hidden で落とす。
 *
 * @param string $variant profile|skill|work|contact|about-*|work-*|footer
 */
function takumi_shape_field( $variant ) {
	$fields = array(
		'profile' => array(
			array( 'circle', array( 'x' => '4%',  'y' => '18%', 'size' => 'clamp(40px, 6vw, 86px)', 'depth' => 26, 'delay' => '-0.4s' ) ),
			array( 'cross',  array( 'x' => '95%', 'y' => '12%', 'size' => 'clamp(26px, 3.4vw, 48px)', 'depth' => 34, 'delay' => '-3.9s' ) ),
			array( 'zigzag', array( 'x' => '90%', 'y' => '78%', 'size' => 'clamp(44px, 6vw, 82px)', 'depth' => 48, 'delay' => '-4.3s', 'class' => 'is-sm-hidden' ) ),
		),
		'skill' => array(
			array( 'square',   array( 'x' => '6%',  'y' => '74%', 'size' => 'clamp(32px, 4.4vw, 62px)', 'depth' => 20, 'delay' => '-6.5s' ) ),
			array( 'triangle', array( 'x' => '94%', 'y' => '22%', 'size' => 'clamp(36px, 5vw, 72px)', 'depth' => 34, 'delay' => '-2.7s' ) ),
			array( 'wave',     array( 'x' => '12%', 'y' => '8%',  'size' => 'clamp(56px, 8vw, 116px)', 'depth' => 16, 'delay' => '-5.1s', 'class' => 'is-sm-hidden' ) ),
		),
		'work' => array(
			array( 'ring',   array( 'x' => '3%',  'y' => '34%', 'size' => 'clamp(34px, 4.6vw, 66px)', 'depth' => 42, 'delay' => '-1.2s' ) ),
			array( 'circle', array( 'x' => '96%', 'y' => '66%', 'size' => 'clamp(30px, 4vw, 58px)', 'depth' => 26, 'delay' => '-2.8s' ) ),
			array( 'cross',  array( 'x' => '88%', 'y' => '6%',  'size' => 'clamp(24px, 3vw, 42px)', 'depth' => 30, 'delay' => '-6s', 'class' => 'is-sm-hidden' ) ),
		),
		'contact' => array(
			array( 'triangle', array( 'x' => '5%',  'y' => '62%', 'size' => 'clamp(34px, 4.6vw, 66px)', 'depth' => 34, 'delay' => '-7.4s' ) ),
			array( 'zigzag',   array( 'x' => '93%', 'y' => '28%', 'size' => 'clamp(42px, 5.6vw, 78px)', 'depth' => 48, 'delay' => '-2.2s' ) ),
			array( 'square',   array( 'x' => '80%', 'y' => '86%', 'size' => 'clamp(26px, 3.4vw, 48px)', 'depth' => 20, 'delay' => '-4.8s', 'class' => 'is-sm-hidden' ) ),
		),
		// --- 下層ページ（About / Work）---
		// トップと同じ絵面にならないよう、形と位置を変えてある。
		// 数はトップより少なめにして、ページを送るたびにぽつぽつ出てくる程度に。
		'about-profile' => array(
			array( 'ring',     array( 'x' => '4%',  'y' => '24%', 'size' => 'clamp(30px, 4.2vw, 58px)', 'depth' => 42, 'delay' => '-1.2s' ) ),
			array( 'triangle', array( 'x' => '93%', 'y' => '14%', 'size' => 'clamp(28px, 3.8vw, 54px)', 'depth' => 34, 'delay' => '-2.7s', 'class' => 'is-sm-hidden' ) ),
			array( 'square',   array( 'x' => '96%', 'y' => '80%', 'size' => 'clamp(24px, 3.2vw, 46px)', 'depth' => 20, 'delay' => '-6.5s' ) ),
		),
		'about-career' => array(
			array( 'wave',   array( 'x' => '8%',  'y' => '10%', 'size' => 'clamp(46px, 6vw, 92px)', 'depth' => 16, 'delay' => '-5.1s', 'class' => 'is-sm-hidden' ) ),
			array( 'circle', array( 'x' => '95%', 'y' => '44%', 'size' => 'clamp(28px, 3.8vw, 54px)', 'depth' => 26, 'delay' => '-0.4s' ) ),
			array( 'cross',  array( 'x' => '4%',  'y' => '84%', 'size' => 'clamp(22px, 2.8vw, 38px)', 'depth' => 30, 'delay' => '-3.9s' ) ),
		),
		'about-cta' => array(
			array( 'zigzag', array( 'x' => '90%', 'y' => '22%', 'size' => 'clamp(40px, 5.2vw, 74px)', 'depth' => 48, 'delay' => '-4.3s' ) ),
			array( 'circle', array( 'x' => '6%',  'y' => '72%', 'size' => 'clamp(26px, 3.4vw, 48px)', 'depth' => 26, 'delay' => '-2.8s', 'class' => 'is-sm-hidden' ) ),
		),
		'work-index' => array(
			array( 'cross',  array( 'x' => '5%',  'y' => '18%', 'size' => 'clamp(22px, 2.8vw, 40px)', 'depth' => 30, 'delay' => '-6s' ) ),
			array( 'ring',   array( 'x' => '94%', 'y' => '32%', 'size' => 'clamp(32px, 4.4vw, 62px)', 'depth' => 42, 'delay' => '-1.2s' ) ),
			array( 'square', array( 'x' => '9%',  'y' => '86%', 'size' => 'clamp(26px, 3.4vw, 48px)', 'depth' => 20, 'delay' => '-6.5s', 'class' => 'is-sm-hidden' ) ),
		),
		'work-detail' => array(
			array( 'triangle', array( 'x' => '95%', 'y' => '10%', 'size' => 'clamp(30px, 4vw, 58px)', 'depth' => 34, 'delay' => '-7.4s' ) ),
			array( 'wave',     array( 'x' => '5%',  'y' => '52%', 'size' => 'clamp(44px, 5.6vw, 86px)', 'depth' => 16, 'delay' => '-5.1s', 'class' => 'is-sm-hidden' ) ),
			array( 'circle',   array( 'x' => '91%', 'y' => '88%', 'size' => 'clamp(24px, 3.2vw, 44px)', 'depth' => 26, 'delay' => '-0.4s' ) ),
		),
		// フッターだけは「散らす」よりも「紛れさせる」のが目的。
		// マスコット2体の周りに寄せて、クリーム色の丸い地を図形の一部に見せる。
		'footer' => array(
			array( 'circle', array( 'x' => '3%',  'y' => '36%', 'size' => 'clamp(30px, 4vw, 54px)', 'depth' => 26, 'delay' => '-0.4s' ) ),
			array( 'zigzag', array( 'x' => '19%', 'y' => '26%', 'size' => 'clamp(38px, 5vw, 68px)', 'depth' => 48, 'delay' => '-4.3s' ) ),
			array( 'cross',  array( 'x' => '13%', 'y' => '72%', 'size' => 'clamp(20px, 2.6vw, 36px)', 'depth' => 30, 'delay' => '-6s', 'class' => 'is-sm-hidden' ) ),
			array( 'ring',   array( 'x' => '82%', 'y' => '30%', 'size' => 'clamp(28px, 3.8vw, 52px)', 'depth' => 42, 'delay' => '-1.2s' ) ),
			array( 'square', array( 'x' => '97%', 'y' => '62%', 'size' => 'clamp(24px, 3.2vw, 44px)', 'depth' => 20, 'delay' => '-6.5s' ) ),
			array( 'wave',   array( 'x' => '88%', 'y' => '84%', 'size' => 'clamp(44px, 6vw, 88px)', 'depth' => 16, 'delay' => '-5.1s', 'class' => 'is-sm-hidden' ) ),
		),
	);

	if ( ! isset( $fields[ $variant ] ) ) {
		return;
	}
	?>
	<div class="shape-field shape-field--<?php echo esc_attr( $variant ); ?>" aria-hidden="true">
		<?php foreach ( $fields[ $variant ] as $deco ) : ?>
			<?php takumi_shape_deco( $deco[0], $deco[1] ); ?>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * 猫のマスコット（demo/mascot-cat.html 由来）
 *
 * フッター上辺のラインに前足を揃えて座らせる。viewBox は 0 0 240 250 から
 * 0 12 240 216 に詰めてあり、下端が前足の底（y=225）とほぼ一致する。
 * こうしないと SVG 下部の余白ぶん、猫がラインから浮いて見える。
 *
 * 毛色は明るいので輪郭はクリーム（--ink）で引いて背景から切り離す。
 * 目と口は黄色い顔の上に乗るため --eye（暗色）に分けてある。
 * 装飾なので aria-hidden。
 */
function takumi_mascot_cat() {
	?>
	<svg class="mascot mascot--cat" viewBox="0 12 240 216" xmlns="http://www.w3.org/2000/svg" role="presentation" aria-hidden="true" focusable="false">
		<g class="sway"><g class="hop"><g class="breathe">

			<!-- しっぽ（太い線の上に細い線を重ねて「輪郭線つき」に見せる） -->
			<g class="tail">
				<path d="M162,206 q46,2 40,-46" fill="none" stroke="var(--ink)" stroke-width="22"/>
				<path d="M162,206 q46,2 40,-46" fill="none" stroke="var(--c1)" stroke-width="8"/>
			</g>

			<!-- 体 -->
			<path class="solid" d="M62,214 q0,-52 58,-52 q58,0 58,52 Z" fill="var(--c1)"/>
			<!-- 前足 -->
			<ellipse class="solid" cx="92" cy="210" rx="17" ry="11" fill="var(--c1)"/>
			<ellipse class="solid" cx="148" cy="210" rx="17" ry="11" fill="var(--c1)"/>

			<g class="head">
				<!-- 耳 -->
				<g class="ear ear--l">
					<path class="solid" d="M70,72 L62,26 L106,48 Z" fill="var(--c1)"/>
					<path d="M78,66 L74,42 L94,53 Z" fill="var(--c2)"/>
				</g>
				<g class="ear ear--r">
					<path class="solid" d="M170,72 L178,26 L134,48 Z" fill="var(--c1)"/>
					<path d="M162,66 L166,42 L146,53 Z" fill="var(--c2)"/>
				</g>

				<!-- 顔 -->
				<circle class="solid" cx="120" cy="112" r="64" fill="var(--c1)"/>

				<!-- ひげ -->
				<path class="line" d="M52,104 H28 M52,120 H30" stroke-width="5"/>
				<path class="line" d="M188,104 H212 M188,120 H210" stroke-width="5"/>

				<!-- ほっぺ（ホバーで出る） -->
				<ellipse class="blush" cx="80" cy="128" rx="11" ry="7" fill="var(--c2)"/>
				<ellipse class="blush" cx="160" cy="128" rx="11" ry="7" fill="var(--c2)"/>

				<!-- 目：待機時 -->
				<g class="eye eye--l"><ellipse cx="97" cy="106" rx="9" ry="12" fill="var(--eye)"/></g>
				<g class="eye eye--r"><ellipse cx="143" cy="106" rx="9" ry="12" fill="var(--eye)"/></g>
				<!-- 目：ホバー時（にっこり） -->
				<path class="eye-happy line line--eye" d="M87,110 q10,-14 20,0"/>
				<path class="eye-happy line line--eye" d="M133,110 q10,-14 20,0"/>

				<!-- 鼻と口 -->
				<path d="M113,126 h14 l-7,9 Z" fill="var(--c2)"/>
				<path class="line line--eye" d="M120,135 q-9,11 -18,2 M120,135 q9,11 18,2" stroke-width="6"/>
			</g>

			<!-- 首輪 -->
			<path class="solid" d="M84,166 q36,20 72,0" fill="none" stroke-width="12"/>
			<path d="M84,166 q36,20 72,0" fill="none" stroke="var(--c3)" stroke-width="6"/>

		</g></g></g>
	</svg>
	<?php
}

/**
 * ロボットのマスコット（demo/mascot-robot.html 由来）
 * 猫と対になる相方。ヘッダーのラインに足を着けて立たせる。
 * viewBox 下端(250)が脚の底(y=248)とほぼ一致するので、そのまま使える。
 * 装飾なので aria-hidden。
 */
function takumi_mascot_robot() {
	?>
	<svg class="mascot mascot--robot" viewBox="0 0 240 250" xmlns="http://www.w3.org/2000/svg" role="presentation" aria-hidden="true" focusable="false">
		<g class="sway"><g class="hop"><g class="breathe">

			<!-- 脚 -->
			<path class="line" d="M98,232 V244 M142,232 V244"/>

			<!-- 胴体 -->
			<rect class="solid" x="78" y="180" width="84" height="54" rx="22" fill="var(--c2)"/>

			<!-- 腕 -->
			<g class="arm arm--l"><path class="line" d="M78,204 H50"/></g>
			<g class="arm arm--r"><path class="line" d="M162,204 H190"/></g>

			<!-- アンテナ -->
			<g class="antenna">
				<path class="line" d="M120,62 V30"/>
				<circle class="solid" cx="120" cy="21" r="11" fill="var(--c3)"/>
			</g>

			<!-- 耳 -->
			<rect class="solid" x="26" y="104" width="20" height="44" rx="10" fill="var(--c2)"/>
			<rect class="solid" x="194" y="104" width="20" height="44" rx="10" fill="var(--c2)"/>

			<!-- 頭 -->
			<rect class="solid" x="42" y="60" width="156" height="122" rx="34" fill="var(--c1)"/>
			<!-- 顔の下地 -->
			<rect class="solid" x="62" y="80" width="116" height="84" rx="24" fill="var(--face)"/>

			<!-- ほっぺ（ホバーで出る） -->
			<ellipse class="blush" cx="78" cy="134" rx="9" ry="6" fill="var(--c1)"/>
			<ellipse class="blush" cx="162" cy="134" rx="9" ry="6" fill="var(--c1)"/>

			<!-- 目 -->
			<g class="eye eye--l"><circle cx="96" cy="112" r="11" fill="var(--eye)"/></g>
			<g class="eye eye--r"><circle cx="144" cy="112" r="11" fill="var(--eye)"/></g>

			<!-- 口：待機時 -->
			<path class="line line--eye mouth--idle" d="M104,140 q16,13 32,0"/>
			<!-- 口：ホバー時（にっと開く） -->
			<path class="mouth--hover" d="M100,136 h40 a20,20 0 0 1 -40,0 Z" fill="var(--eye)" stroke="var(--eye)" stroke-width="8"/>

		</g></g></g>
	</svg>
	<?php
}

/**
 * 右上向き矢印アイコン
 */
function takumi_arrow_icon() {
	echo '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>';
}

/**
 * プラスアイコン（開閉トグル用）
 *
 * 矢印と同じ 24 グリッド・同じ線幅で描く。開いたときは CSS で 45 度回して
 * そのまま「×」になる。
 */
function takumi_plus_icon() {
	echo '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>';
}

/**
 * 制作実績の投稿一覧を取得
 */
function takumi_get_works() {
	return get_posts( array(
		'post_type'      => 'works',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'DESC',
	) );
}

/* ---------- スキル・経歴の取得 ---------- */

/**
 * スキル一覧を取得(管理画面「スキル」に投稿がなければ既定値を返す)
 * 各要素: array( アイコンID, 名前, 経験, 習熟度%, 補足 )
 */
function takumi_get_skills_data() {
	$posts = get_posts( array(
		'post_type'      => 'skill',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );

	if ( ! $posts ) {
		return array(
			array( 'html', 'HTML', '4 yrs', 90, 'Webサイト制作で使用' ),
			array( 'css', 'CSS', '4 yrs', 80, 'Webサイト制作で使用' ),
			array( 'js', 'JavaScript', 'Learning', 40, '経験半年・Webサイト制作で使用' ),
			array( 'php', 'PHP', 'Learning', 20, '経験半年・基礎から学習中' ),
			array( 'python', 'Python', '1 yr', 80, '基本構文を習得済み' ),
			array( 'java', 'Java', 'Learning', 10, '経験半年・基礎から学習中' ),
			array( 'django', 'Django', 'Learning', 20, '産学連携プロジェクトで制作経験あり' ),
			array( 'laravel', 'Laravel', 'Learning', 35, 'Webアプリの制作経験あり' ),
			array( 'mysql', 'MySQL', '<1 yr', 30, 'データベース構築で使用' ),
			array( 'git', 'Git', '<1 yr', 30, 'リポジトリの管理で使用' ),
			array( 'github', 'GitHub', '1 yr', 50, 'チーム開発でのリポジトリ共有で使用' ),
			array( 'docker', 'Docker', '<1 yr', 25, '開発環境の構築経験あり' ),
			array( 'wordpress', 'WordPress', '4 yrs', 90, 'Webサイト制作・テーマ開発で使用' ),
			array( 'threejs', 'Three.js', 'Learning', 20, '本サイトの3D演出で使用' ),
		);
	}

	return array_map( function ( $post ) {
		return array(
			get_post_meta( $post->ID, '_takumi_icon', true ),
			get_the_title( $post ),
			get_post_meta( $post->ID, '_takumi_experience', true ),
			(int) get_post_meta( $post->ID, '_takumi_percent', true ),
			get_post_meta( $post->ID, '_takumi_note', true ),
		);
	}, $posts );
}

/**
 * 経歴一覧を取得(管理画面「経歴」に投稿がなければ既定値を返す)
 * 各要素: array( 日付, タイトル, 説明 )
 */
function takumi_get_career_data() {
	$posts = get_posts( array(
		'post_type'      => 'career',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );

	if ( ! $posts ) {
		return array(
			array( '2021.04', '通信制高校に編入', '高校在学中に自身のブログサイトの制作を経験。' ),
			array( '2024.04', 'KADOKAWAドワンゴ情報工科学院 入学', '入学後、プログラミングの学習を本格的に開始。' ),
			array( '2024.09', '初めてのチーム制作を経験', '文化祭でのチーム制作を通じて協調性や責任感を養い、その経験が今の自信やキャリア形成につながっている。' ),
			array( '2024.11', '初の産学連携にチームリーダーとして参加', '産学連携プロジェクトを通じて、企業との協働方法を学ぶ。' ),
			array( '2025.04', '企業様の実案件コンペで受賞', 'コーダーとしてWebサイト制作に参加。' ),
			array( '2025.05', 'Web制作案件の営業を開始', 'クライアントとのコミュニケーションを学びながら案件の営業を開始。' ),
			
		);
	}

	return array_map( function ( $post ) {
		return array(
			get_post_meta( $post->ID, '_takumi_date', true ),
			get_the_title( $post ),
			get_post_meta( $post->ID, '_takumi_description', true ),
		);
	}, $posts );
}

/**
 * スキルアイコンのURLを取得。ローカルに /assets/img/skills/{icon}.svg があればそれを使い、
 * 無ければ skillicons.dev にフォールバックする(外部リクエスト削減のため)
 */
function takumi_skill_icon_url( $icon ) {
	$local_path = get_template_directory() . '/assets/img/skills/' . $icon . '.svg';
	if ( file_exists( $local_path ) ) {
		return get_template_directory_uri() . '/assets/img/skills/' . $icon . '.svg';
	}
	return 'https://skillicons.dev/icons?i=' . rawurlencode( $icon );
}

/**
 * トップページ Skill セクション用の上位アイコンを取得
 */
function takumi_get_top_skill_icons( $limit = 6 ) {
	$icons = array_filter( array_column( takumi_get_skills_data(), 0 ) );
	return array_slice( $icons, 0, $limit );
}

