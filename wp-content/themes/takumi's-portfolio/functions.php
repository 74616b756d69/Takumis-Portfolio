<?php
/**
 * Takumi's Portfolio — テーマ機能
 */

show_admin_bar(false);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TAKUMI_VERSION', '3.3.1' );

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
		'window.TAKUMI_BASE = ' . wp_json_encode( $uri . '/assets/' ) . ';',
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

/**
 * 「キー|値」を1行ずつ並べた設定値を連想配列にする。
 * 空行と、| を含まない行は読み飛ばす。前後の空白は落とす。
 */
function takumi_parse_pair_lines( $raw ) {
	$pairs = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line || false === strpos( $line, '|' ) ) {
			continue;
		}
		list( $key, $value ) = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( '' !== $key ) {
			$pairs[ $key ] = $value;
		}
	}
	return $pairs;
}

/* ============================================================
   カスタマイザー(プロフィール設定)
   ============================================================ */
/**
 * Statement セクション（横に流れる大きな文字）の既定値。
 * 1行 = 「種類|文字」。種類の一覧は takumi_get_statement_items() を参照。
 */
const TAKUMI_STATEMENT_DEFAULT = "xl-grad|つくる。
text|手を動かして、形にする。
shape|diamond
xl-outline|うごかす。
text|要件定義から運用まで。
shape|ring
xl-grad2|とどける。
text|使う人の、毎日へ。
shape|star";

/**
 * Work インデックスの絞り込み軸の既定値。
 * 1行 = 「キー|ラベル」。3つめに「値1,値2」を足すと選択肢を固定できる。
 */
const TAKUMI_WORK_FACETS_DEFAULT = "category|Category
type|Type
tech|Tech
year|Year";

/**
 * 区分(category)の英字キーと、画面に出す日本語ラベルの対応の既定値。
 */
const TAKUMI_WORK_CATEGORY_LABELS_DEFAULT = "personal|個人制作
company|企業・実案件
team|チーム制作
school|学校制作";

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
		'default'           => 'フロントエンドからバックエンドまで。HTML/CSSでの制作経験を軸に、React・Spring Boot・ASP.NET Core まで、実際に動くものを作りながら幅を広げています。',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_top_skill_desc', array(
		'label'   => 'Skillセクションの説明文',
		'section' => 'takumi_top_texts',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'takumi_home_work_limit', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'takumi_home_work_limit', array(
		'label'       => 'トップに出す実績の件数(上限)',
		'description' => 'トップページの Work セクションに並べる件数の上限です。0 にすると上限なし。'
			. '出す実績そのものは、管理画面「制作実績」の各記事にある「トップページに掲載する」で選べます。'
			. '並び順は制作実績一覧の並び順(順序)に従います。',
		'section'     => 'takumi_top_texts',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 0, 'step' => 1 ),
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

	/* ---------- セクション見出し・共通文言 ---------- */
	$wp_customize->add_section( 'takumi_headings', array(
		'title'       => 'セクション見出し設定',
		'description' => 'トップページ各セクションの見出しやラベルを編集できます。',
		'priority'    => 33,
	) );

	$wp_customize->add_section( 'takumi_page_texts', array(
		'title'       => 'About / Work ページ文言設定',
		'description' => '下層ページの見出しや説明文を編集できます。',
		'priority'    => 34,
	) );

	$wp_customize->add_section( 'takumi_section_labels', array(
		'title'       => 'セクションラベル設定',
		'description' => '各セクションに添える英字ラベルと日本語ラベルを編集できます。',
		'priority'    => 35,
	) );

	$wp_customize->add_section( 'takumi_work_filter', array(
		'title'       => 'Work: 絞り込み設定',
		'description' => '実績インデックスの絞り込みの軸・ラベル・選択肢を編集できます。',
		'priority'    => 36,
	) );

	$wp_customize->add_section( 'takumi_code', array(
		'title'       => '追加コード(head / footer)',
		'description' => '全ページの &lt;head&gt; 内と &lt;/body&gt; 直前に、そのまま差し込むコードです。'
			. '解析タグ・フォントの読み込み・ちょっとしたスクリプトなどに使います。'
			. 'CSS は WordPress 本体の「追加CSS」に書くほうが、プレビューしながら調整できます。'
			. 'PHP は書けません(そのまま文字として出ます)。',
		'priority'    => 37,
	) );

	// key => array( セクション, ラベル, 既定値, 入力欄の種類, 補足説明 )
	// 種類が textarea のものは改行が意味を持つ（見出しは改行位置で行が分かれる）。
	$takumi_text_controls = array(
		'takumi_logo_text' => array(
			'takumi_headings', 'サイトロゴの文字', "Takumi's Portfolio", 'text',
			'ヘッダー左上とローディング画面に表示されます。',
		),
		'takumi_hero_label' => array(
			'takumi_headings', 'トップ: ヒーロー上部のラベル', 'Portfolio / Web Developer', 'text', '',
		),
		'takumi_skill_heading' => array(
			'takumi_headings', 'トップ: Skill の見出し', "触れる技術を、\n増やしている途中です。", 'textarea',
			'改行した位置で行が分かれます。',
		),
		'takumi_builds_label' => array(
			'takumi_headings', 'トップ: 個人開発スライダーのラベル', 'Personal builds — scroll sideways', 'text', '',
		),
		'takumi_builds_note' => array(
			'takumi_headings', 'トップ: 個人開発スライダーの補足', '学校やチームでの制作とは別に、個人で作っているものです。', 'text', '',
		),
		'takumi_work_heading' => array(
			'takumi_headings', 'Work の見出し', "実装の幅を、\n結果で見せる。", 'textarea',
			'トップページの Work セクションと Work ページの両方で使われます。改行した位置で行が分かれます。',
		),
		'takumi_contact_heading' => array(
			'takumi_headings', 'トップ: Contact の見出し', "話を聞くところから、\nはじめさせてください。", 'textarea',
			'改行した位置で行が分かれます。',
		),
		'takumi_contact_form_id' => array(
			'takumi_headings', 'Contact Form 7 のフォームID', '3b0857e', 'text',
			'Contact Form 7 の一覧に出るショートコード [contact-form-7 id="…"] の id の値です。空欄にするとフォーム行を表示しません。',
		),
		'takumi_about_hero_label' => array(
			'takumi_page_texts', 'About: ヒーローのラベル', 'Profile / Skill / Career', 'text', '',
		),
		'takumi_about_cta_text' => array(
			'takumi_page_texts', 'About: ページ下部の案内文', '制作実績もぜひご覧ください。', 'text', '',
		),
		'takumi_work_hero_label' => array(
			'takumi_page_texts', 'Work: ヒーローのラベル', 'Selected Work / 2024—2025', 'text', '',
		),
		'takumi_work_hero_lead' => array(
			'takumi_page_texts', 'Work: ヒーローの説明文', 'これまでに手掛けた制作物をまとめています。気になる番号を選ぶと、その場で詳細が開きます。', 'textarea', '',
		),
		'takumi_work_kicker' => array(
			'takumi_page_texts', 'Work: 索引の小見出し(英字)', 'WORK INDEX / FIELD RECORDS', 'text', '',
		),
		'takumi_work_index_desc' => array(
			'takumi_page_texts', 'Work: 索引の説明文', '企業・個人・チーム制作を横断し、要件定義から運用まで必要な場所を担当してきました。気になる番号を開くと、その場で詳細が読めます。', 'textarea', '',
		),

		/* --- トップページのリンク・小さな文言 --- */
		'takumi_hero_scroll' => array(
			'takumi_headings', 'トップ: ヒーロー下のスクロール案内', 'Scroll', 'text', '',
		),
		'takumi_profile_photo_tag' => array(
			'takumi_headings', 'トップ: プロフィール写真のタグ', 'Web Developer', 'text', '',
		),
		'takumi_statement_label' => array(
			'takumi_headings', 'トップ: 横に流れる文字のラベル', 'Scroll — 横に流れます', 'text', '',
		),
		'takumi_top_profile_btn' => array(
			'takumi_top_texts', 'トップ: Profile のボタン文言', 'More About Me', 'text', '',
		),
		'takumi_top_skill_more' => array(
			'takumi_top_texts', 'トップ: Skill 下のリンク文言', 'スキルの一覧を見る', 'text', '',
		),
		'takumi_builds_link_text' => array(
			'takumi_top_texts', 'トップ: 個人開発カードのリンク文言', 'GitHub で見る', 'text', '',
		),
		'takumi_top_work_more' => array(
			'takumi_top_texts', 'トップ: Work 下のリンク文言', '実績の一覧を見る', 'text', '',
		),
		'takumi_contact_form_label' => array(
			'takumi_top_texts', 'トップ: Contact のフォーム行の文言', 'フォームから送る', 'text', '',
		),
		'takumi_contact_form_fallback' => array(
			'takumi_top_texts', 'トップ: フォームが使えないときの一文', 'フォームは準備中です。上のアドレスへ直接お送りください。', 'textarea',
			'Contact Form 7 が無効、またはフォームIDが空欄のときに代わりに表示されます。',
		),

		/* --- 下層ページのボタン・文言 --- */
		'takumi_about_btn_work' => array(
			'takumi_page_texts', 'About: 下部ボタン(実績へ)', 'View Works', 'text', '',
		),
		'takumi_about_btn_contact' => array(
			'takumi_page_texts', 'About: 下部ボタン(問い合わせへ)', 'Contact', 'text', '',
		),
		'takumi_work_empty' => array(
			'takumi_page_texts', 'Work: 該当が無いときの文言', '条件に一致する作品が見つかりませんでした。', 'text',
			'絞り込みの結果が0件になったときに表示されます。',
		),

		/* --- セクションのラベル(英字 / 日本語) --- */
		'takumi_top_profile_en' => array(
			'takumi_section_labels', 'トップ: 01 の英字ラベル', 'Profile', 'text', '',
		),
		'takumi_top_profile_ja' => array(
			'takumi_section_labels', 'トップ: 01 の日本語ラベル', '私について', 'text', '',
		),
		'takumi_top_skill_en' => array(
			'takumi_section_labels', 'トップ: 02 の英字ラベル', 'Skill', 'text', '',
		),
		'takumi_top_skill_ja' => array(
			'takumi_section_labels', 'トップ: 02 の日本語ラベル', 'できること', 'text', '',
		),
		'takumi_top_work_en' => array(
			'takumi_section_labels', 'トップ: 03 の英字ラベル', 'Work', 'text', '',
		),
		'takumi_top_work_ja' => array(
			'takumi_section_labels', 'トップ: 03 の日本語ラベル', '制作実績', 'text', '',
		),
		'takumi_top_contact_en' => array(
			'takumi_section_labels', 'トップ: 04 の英字ラベル', 'Contact', 'text', '',
		),
		'takumi_top_contact_ja' => array(
			'takumi_section_labels', 'トップ: 04 の日本語ラベル', 'お問い合わせ', 'text', '',
		),
		'takumi_about_hero_sub' => array(
			'takumi_section_labels', 'About: ヒーローの日本語ラベル', '私について', 'text', '',
		),
		'takumi_about_profile_en' => array(
			'takumi_section_labels', 'About: 01 の英字見出し', 'Profile', 'text', '',
		),
		'takumi_about_profile_ja' => array(
			'takumi_section_labels', 'About: 01 の日本語見出し', 'プロフィール', 'text', '',
		),
		'takumi_about_skill_en' => array(
			'takumi_section_labels', 'About: 02 の英字見出し', 'Skill', 'text', '',
		),
		'takumi_about_skill_ja' => array(
			'takumi_section_labels', 'About: 02 の日本語見出し', 'スキル', 'text', '',
		),
		'takumi_about_career_en' => array(
			'takumi_section_labels', 'About: 03 の英字見出し', 'Career', 'text', '',
		),
		'takumi_about_career_ja' => array(
			'takumi_section_labels', 'About: 03 の日本語見出し', '経歴', 'text', '',
		),
		'takumi_work_hero_sub' => array(
			'takumi_section_labels', 'Work: ヒーローの日本語ラベル', '制作実績', 'text', '',
		),

		/* --- 絞り込みのボタン文言 --- */
		'takumi_work_filter_all' => array(
			'takumi_work_filter', '「すべて」ボタンの文言', 'All', 'text', '各軸の先頭に出る、絞り込みを解除するボタンです。',
		),
		'takumi_work_filter_reset' => array(
			'takumi_work_filter', 'リセットボタンの文言', 'Reset', 'text', '',
		),
	);

	foreach ( $takumi_text_controls as $key => $conf ) {
		list( $section, $label, $default, $type, $description ) = $conf;
		$wp_customize->add_setting( $key, array(
			'default'           => $default,
			'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'       => $label,
			'description' => $description,
			'section'     => $section,
			'type'        => $type,
		) );
	}

	/* ---------- Statement(横に流れる大きな文字) ---------- */
	$wp_customize->add_setting( 'takumi_statement', array(
		'default'           => TAKUMI_STATEMENT_DEFAULT,
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_statement', array(
		'label'       => 'トップ: 横に流れる大きな文字',
		'description' => '1行に1つ、「種類|文字」の形式で書きます。'
			. '種類は xl-grad(グラデーションの大文字) / xl-outline(白抜きの大文字) / xl-grad2(グラデーション2) / text(小さめの文章) / shape(図形) です。'
			. 'shape のときは文字の代わりに diamond・ring・star のいずれかを書きます。',
		'section'     => 'takumi_headings',
		'type'        => 'textarea',
	) );

	/* ---------- Work: 絞り込みの軸と区分ラベル ---------- */
	$wp_customize->add_setting( 'takumi_work_facets', array(
		'default'           => TAKUMI_WORK_FACETS_DEFAULT,
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_work_facets', array(
		'label'       => '絞り込みの軸',
		'description' => '1行に1つ、「キー|ラベル」の形式で書きます。書いた順に上から並び、行を消すとその軸は表示されません。'
			. '使えるキーは category(区分) / type(種別) / tech(技術) / year(制作年) です。'
			. '選択肢は各実績の入力から自動で集まります（制作年は新しい順）。'
			. '「キー|ラベル|値1,値2」と3つめを書くと、その並びで選択肢を固定できます。',
		'section'     => 'takumi_work_filter',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'takumi_work_category_labels', array(
		'default'           => TAKUMI_WORK_CATEGORY_LABELS_DEFAULT,
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'takumi_work_category_labels', array(
		'label'       => '区分(category)の表示名',
		'description' => '1行に1つ、「キー|表示名」の形式で書きます。実績の「区分」に書いた英字キーを、'
			. '絞り込みボタンと詳細のバッジで日本語に言い換えます。ここに無いキーはそのまま表示されます。'
			. '区分を増やすときは、ここに1行足してから実績の「区分」にそのキーを入力してください。',
		'section'     => 'takumi_work_filter',
		'type'        => 'textarea',
	) );

	/* ---------- 追加コード ---------- */
	$code_fields = array(
		'takumi_code_head' => array(
			'&lt;head&gt; 内に追加するコード',
			'全ページの &lt;/head&gt; の直前に出力されます。meta タグや外部スクリプトの読み込みなど。',
		),
		'takumi_code_footer' => array(
			'&lt;/body&gt; の直前に追加するコード',
			'全ページの最後に出力されます。読み込みを遅らせたいスクリプトはこちらへ。',
		),
	);
	foreach ( $code_fields as $key => $conf ) {
		$wp_customize->add_setting( $key, array(
			'default'           => '',
			'sanitize_callback' => 'takumi_sanitize_code',
		) );
		$wp_customize->add_control( $key, array(
			'label'       => $conf[0],
			'description' => $conf[1],
			'section'     => 'takumi_code',
			'type'        => 'textarea',
			'input_attrs' => array( 'rows' => 8, 'style' => 'font-family:monospace;' ),
		) );
	}
} );

/**
 * 追加コードの保存前チェック
 * 未加工の HTML を保存できるのは、その権限を持つ利用者(既定では管理者)だけ。
 * 権限が無ければ投稿本文と同じ範囲まで削る。
 */
function takumi_sanitize_code( $value ) {
	$value = (string) $value;
	return current_user_can( 'unfiltered_html' ) ? $value : wp_kses_post( $value );
}

/**
 * 追加コードを head / footer に出力する
 * テーマ自身の出力を邪魔しないよう、どちらも最後(優先度99)に回す。
 */
add_action( 'wp_head', function () {
	$code = trim( (string) get_theme_mod( 'takumi_code_head', '' ) );
	if ( $code ) {
		echo "\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 管理者が入力したコードをそのまま出す
	}
}, 99 );

add_action( 'wp_footer', function () {
	$code = trim( (string) get_theme_mod( 'takumi_code_footer', '' ) );
	if ( $code ) {
		echo "\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 管理者が入力したコードをそのまま出す
	}
}, 99 );

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

	register_post_type( 'build', array(
		'labels' => array(
			'name'          => '個人開発',
			'singular_name' => '個人開発',
			'add_new_item'  => '個人開発を追加',
			'edit_item'     => '個人開発を編集',
		),
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'has_archive'        => false,
		'menu_icon'          => 'dashicons-hammer',
		'menu_position'      => 8,
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

			// チェックボックスはラベルを右に置きたいので、他の型とは組み方を分ける。
			if ( 'checkbox' === $type ) {
				printf(
					'<p><label for="takumi_%1$s"><input type="checkbox" id="takumi_%1$s" name="takumi_%1$s" value="1"%2$s> <strong>%3$s</strong></label><br><small>%4$s</small></p>',
					esc_attr( $key ),
					checked( $value, '1', false ),
					esc_html( $conf[0] ),
					esc_html( $conf[1] )
				);
				continue;
			}

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
		$type = isset( $conf[2] ) ? $conf[2] : 'text';

		// 未チェックのチェックボックスは POST に含まれないため、
		// 「来ていない = 外された」とみなして保存する。
		if ( 'checkbox' === $type ) {
			update_post_meta( $post_id, '_takumi_' . $key, empty( $_POST[ 'takumi_' . $key ] ) ? '' : '1' );
			continue;
		}

		if ( isset( $_POST[ 'takumi_' . $key ] ) ) {
			$value = wp_unslash( $_POST[ 'takumi_' . $key ] );
			update_post_meta( $post_id, '_takumi_' . $key, 'textarea' === $type ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) );
		}
	}
}

/* ---------- 制作実績メタボックス ---------- */
const TAKUMI_WORK_FIELDS = array(
	'home'  => array( 'トップページに掲載する', 'トップページの Work セクションに出す実績を選びます。'
		. '1件もチェックが無いときは、Workページと同じ並びで自動的に表示されます。'
		. '並び順と表示件数の上限は「外観 > カスタマイズ > トップページ文言設定」から。', 'checkbox' ),
	'meta'  => array( 'サブタイトル', '例: 個人制作・2025' ),
	'year'  => array( '制作年', 'Work一覧の Year 絞り込みに使う。新しい年を入れると選択肢が自動で増える。例: 2025' ),
	'category' => array( '区分(カンマ区切り)', 'Work一覧の絞り込みに使う。既定は personal / company / team / school。'
		. '区分の追加や表示名の変更は「外観 > カスタマイズ > Work: 絞り込み設定」から。' ),
	'type'  => array( '種別(カンマ区切り)', 'front / back / design' ),
	'tech'  => array( '技術(カンマ区切り)', '例: html/css,js,php' ),
	'period'=> array( '制作期間', '詳細の「期間」欄。空欄なら行ごと出ない。例: 2024.09 – 2024.11' ),
	'team'  => array( '体制', '詳細の「体制」欄。空欄なら行ごと出ない。例: 4人チーム(リーダー)' ),
	'role'  => array( '担当', '例: デザイン・コーディング全て' ),
	'status'=> array( '公開状態', '詳細の先頭にバッジで出る。空欄でリンクも無ければ「非公開」。例: 制作中 / 公開中 / 非公開' ),
	'challenge' => array( '課題', '詳細の 01。何が問題だったか。空欄ならブロックごと出ない。', 'textarea' ),
	'approach'  => array( 'やったこと', '詳細の 02。どう解いたか。', 'textarea' ),
	'result'    => array( '結果・学び', '詳細の 03。どうなったか・何を得たか。', 'textarea' ),
	'url'   => array( '公開URL', '空欄可' ),
	'github'=> array( 'GitHub URL', '空欄可' ),
	'note'  => array( 'リンクが無いときの補足', '公開URLもGitHubも無いときに詳細の末尾へ出す。例: 掲載のみ承認いただいた案件のため、詳細は面談時にご紹介します。', 'textarea' ),
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

/* ---------- 個人開発メタボックス ---------- */
const TAKUMI_BUILD_FIELDS = array(
	'headline' => array( '見出し', '例: 宇宙の打ち上げを、日々の予定に並べる / 改行した位置で行が分かれます', 'textarea' ),
	'text'     => array( '説明文', '2〜3行程度の紹介文', 'textarea' ),
	'meta'     => array( '技術・体制のメモ', '例: 設計・API・UI すべて一人で / Spring Boot 3 + React 18 + MySQL' ),
	'icons'    => array( 'スキルアイコン(カンマ区切り)', 'skillicons.dev のID。例: java,spring,react,mysql,docker' ),
	'url'      => array( 'リンクURL', '例: https://github.com/74616b756d69/Apogee' ),
);

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'takumi_work_meta', '実績情報', takumi_meta_box_renderer( TAKUMI_WORK_FIELDS, 'work' ), 'works', 'normal', 'high' );
	add_meta_box( 'takumi_skill_meta', 'スキル情報', takumi_meta_box_renderer( TAKUMI_SKILL_FIELDS, 'skill' ), 'skill', 'normal', 'high' );
	add_meta_box( 'takumi_career_meta', '経歴情報', takumi_meta_box_renderer( TAKUMI_CAREER_FIELDS, 'career' ), 'career', 'normal', 'high' );
	add_meta_box( 'takumi_build_meta', '個人開発の情報', takumi_meta_box_renderer( TAKUMI_BUILD_FIELDS, 'build' ), 'build', 'normal', 'high' );
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
add_action( 'save_post_build', function ( $post_id ) {
	takumi_save_meta_fields( $post_id, TAKUMI_BUILD_FIELDS, 'build' );
} );

/* ---------- 制作実績の一覧画面(管理側) ---------- */

/**
 * 実績一覧に「トップ掲載」列を足す。どれをトップに出しているか一覧で分かるように。
 */
add_filter( 'manage_works_posts_columns', function ( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['takumi_home'] = 'トップ掲載';
		}
	}
	return $new;
} );

add_action( 'manage_works_posts_custom_column', function ( $column, $post_id ) {
	if ( 'takumi_home' !== $column ) {
		return;
	}
	$on = '1' === (string) get_post_meta( $post_id, '_takumi_home', true );
	printf(
		'<span style="color:%s" title="%s">%s</span>',
		$on ? '#2271b1' : '#a7aaad',
		esc_attr( $on ? 'トップページに掲載中' : 'トップページには出していません' ),
		$on ? '●' : '—'
	);
}, 10, 2 );

/* ---------- 制作実績の取得・レコード出力 ---------- */

/**
 * 区分(category)の英字キー => 日本語ラベル の対応を返す
 * (カスタマイザー「Work: 絞り込み設定」で編集できる)
 */
function takumi_work_category_map() {
	return takumi_parse_pair_lines( get_theme_mod( 'takumi_work_category_labels', TAKUMI_WORK_CATEGORY_LABELS_DEFAULT ) );
}

/**
 * 区分キーの表示名を返す(未知のキーはそのまま出す)
 */
function takumi_work_category_label( $key ) {
	$key = trim( (string) $key );
	$map = takumi_work_category_map();
	return $map[ strtolower( $key ) ] ?? ( $map[ $key ] ?? $key );
}

/**
 * 絞り込みボタンに出す選択肢の表示名
 * 区分だけは英字キーを日本語へ言い換え、他の軸は入力値をそのまま見せる。
 */
function takumi_work_facet_value_label( $group, $value ) {
	return 'category' === $group ? takumi_work_category_label( $value ) : $value;
}

/**
 * Work インデックスの絞り込み軸を組み立てる
 *
 * 軸の並びとラベルはカスタマイザー、選択肢は実際のレコードの入力値から拾う。
 * 選択肢を固定文字列で持つと、管理画面の入力とズレた瞬間に
 * 「押しても 0 件」のボタンが出てしまうため、値そのものを集めている。
 *
 * @param WP_Post[] $works 表示する実績。
 * @return array キー => array( 'label' => 表示名, 'values' => 値の配列 )
 */
function takumi_get_work_facets( $works ) {
	// data-属性として出している軸だけを受け付ける(takumi_render_work_record を参照)
	$allowed = array( 'category', 'type', 'tech', 'year' );
	$facets  = array();

	foreach ( preg_split( '/\r\n|\r|\n/', (string) get_theme_mod( 'takumi_work_facets', TAKUMI_WORK_FACETS_DEFAULT ) ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts = array_map( 'trim', explode( '|', $line, 3 ) );
		$key   = strtolower( $parts[0] );
		if ( ! in_array( $key, $allowed, true ) || isset( $facets[ $key ] ) ) {
			continue;
		}
		// 3つめに値を書いた軸は、その並びで固定する(自動収集しない)
		$fixed = isset( $parts[2] ) ? array_filter( array_map( 'trim', explode( ',', $parts[2] ) ) ) : array();

		$facets[ $key ] = array(
			'label'  => '' !== ( $parts[1] ?? '' ) ? $parts[1] : ucfirst( $key ),
			'values' => array_fill_keys( $fixed, true ),
			'fixed'  => (bool) $fixed,
		);
	}
	if ( ! $facets ) {
		return array();
	}

	if ( $works ) {
		foreach ( $works as $work ) {
			foreach ( $facets as $key => $facet ) {
				if ( $facet['fixed'] ) {
					continue;
				}
				$raw = (string) get_post_meta( $work->ID, '_takumi_' . $key, true );
				foreach ( array_filter( array_map( 'trim', explode( ',', $raw ) ) ) as $value ) {
					$facets[ $key ]['values'][ $value ] = true;
				}
			}
		}
	} else {
		// 実績が未登録のときは works.js 同梱のサンプルデータの語彙に合わせる。
		$samples = array(
			'category' => array_keys( takumi_work_category_map() ),
			'type'     => array( 'front', 'back', 'design' ),
			'tech'     => array( 'html/css', 'js', 'php', 'python' ),
			'year'     => array( '2025', '2024' ),
		);
		foreach ( $facets as $key => $facet ) {
			if ( ! $facet['fixed'] ) {
				$facets[ $key ]['values'] = array_fill_keys( $samples[ $key ], true );
			}
		}
	}

	// 制作年は入力順に並ぶと読みにくいので、新しい順に整える。
	if ( isset( $facets['year'] ) && ! $facets['year']['fixed'] ) {
		$years = array_keys( $facets['year']['values'] );
		rsort( $years, SORT_NATURAL );
		$facets['year']['values'] = array_fill_keys( $years, true );
	}

	// 選択肢が1つも無い軸は行ごと出さない。
	return array_filter( $facets, fn( $facet ) => (bool) $facet['values'] );
}

/**
 * 詳細の先頭に出すバッジを組み立てる
 * 公開状態は「status に書かれていればそれ」「空ならリンクの有無から推定」。
 * 本文の括弧書き(サイト公開なし 等)に頼らず、ひと目で分かる位置に出すのが狙い。
 */
function takumi_work_badges( $meta ) {
	// 制作年はモーダル見出しのサブタイトルに出ているので、ここでは繰り返さない。
	$badges = array();

	foreach ( array_filter( array_map( 'trim', explode( ',', (string) $meta( 'category' ) ) ) ) as $cat ) {
		$badges[] = array( 'category', takumi_work_category_label( $cat ) );
	}

	$status = trim( (string) $meta( 'status' ) );
	if ( ! $status && ! $meta( 'url' ) && ! $meta( 'github' ) ) {
		$status = '非公開';
	}
	if ( $status ) {
		$badges[] = array( 'status', $status );
	}

	return $badges;
}

/**
 * 制作実績の詳細(レコードを開いたときに出る中身)を出力する
 * 左に画像、右に「バッジ → 事実(期間・体制・担当) → 物語(課題・やったこと・結果) →
 * 技術 → リンク」の順。開いた直後に案件の性格が読めるように、文章より先に
 * 短い事実を置いている。
 */
function takumi_render_work_detail( $post ) {
	$id    = $post->ID;
	$meta  = fn( $key ) => get_post_meta( $id, '_takumi_' . $key, true );
	$thumb = get_the_post_thumbnail_url( $id, 'large' );
	$techs = array_filter( array_map( 'trim', explode( ',', (string) $meta( 'tech' ) ) ) );
	$icons = array_filter( array_map( 'trim', explode( ',', (string) $meta( 'icons' ) ) ) );

	// 本文内の画像 + アイキャッチをギャラリーに。alt があれば拾って添える。
	$images = $thumb ? array( array( 'src' => $thumb, 'alt' => '' ) ) : array();
	if ( preg_match_all( '/<img[^>]+>/', $post->post_content, $tags ) ) {
		foreach ( $tags[0] as $tag ) {
			if ( ! preg_match( '/src="([^"]+)"/', $tag, $src ) ) {
				continue;
			}
			$alt = preg_match( '/alt="([^"]*)"/', $tag, $m ) ? $m[1] : '';
			$images[] = array( 'src' => $src[1], 'alt' => $alt );
		}
	}
	// 同じ画像が二重に並ばないように src で畳む。
	$unique = array();
	foreach ( $images as $image ) {
		$unique[ $image['src'] ] = $image;
	}
	$images = array_values( $unique );

	// 画像はギャラリーで出すので、本文からは取り除く。
	// 画像だけを並べた行が残ると空段落になるため、余った <br> と空白行も畳む。
	$body = preg_replace( '/<img[^>]*>/', '', $post->post_content );
	$body = preg_replace( '/^(\s*<br\s*\/?>)+/', '', $body );
	$body = preg_replace( '/(<br\s*\/?>\s*){2,}/', '<br />', $body );
	$body = preg_replace( '/(\s*<br\s*\/?>)+$/', '', $body );
	$body = trim( preg_replace( '/\n{3,}/', "\n\n", $body ) );

	$badges = takumi_work_badges( $meta );

	// 事実の行。未入力の項目は行ごと出さない。
	$facts = array_filter( array(
		'期間' => (string) $meta( 'period' ),
		'体制' => (string) $meta( 'team' ),
		'担当' => (string) $meta( 'role' ),
	) );

	// 物語の3ブロック。ひとつも無ければ本文を Overview として出す。
	$story = array_filter( array(
		'課題'      => (string) $meta( 'challenge' ),
		'やったこと' => (string) $meta( 'approach' ),
		'結果・学び' => (string) $meta( 'result' ),
	) );

	$title = get_the_title( $id );
	?>
	<div class="work-detail">
		<?php if ( $images ) : ?>
			<figure class="work-detail__visual">
				<div class="work-detail__stage">
					<img class="work-detail__main"
						src="<?php echo esc_url( $images[0]['src'] ); ?>"
						alt="<?php echo esc_attr( $images[0]['alt'] ? $images[0]['alt'] : $title . ' のメインビジュアル' ); ?>">
				</div>
				<?php if ( count( $images ) > 1 ) : ?>
					<div class="work-detail__thumbs" role="tablist" aria-label="<?php echo esc_attr( $title ); ?> の画像">
						<?php foreach ( $images as $i => $image ) : ?>
							<button type="button" class="work-detail__thumb<?php echo 0 === $i ? ' is-active' : ''; ?>"
								role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
								data-src="<?php echo esc_url( $image['src'] ); ?>"
								data-alt="<?php echo esc_attr( $image['alt'] ? $image['alt'] : sprintf( '%s の画像 %d', $title, $i + 1 ) ); ?>">
								<img src="<?php echo esc_url( $image['src'] ); ?>"
									alt="<?php echo esc_attr( $image['alt'] ? $image['alt'] : sprintf( '%s の画像 %d', $title, $i + 1 ) ); ?>"
									loading="lazy">
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="work-detail__body">
			<?php if ( $badges ) : ?>
				<div class="work-detail__badges">
					<?php foreach ( $badges as $badge ) : ?>
						<span class="work-badge work-badge--<?php echo esc_attr( $badge[0] ); ?>"><?php echo esc_html( $badge[1] ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $icons || $techs ) : ?>
				<div class="work-detail__stack">
					<p class="work-detail__stack-label">Tech Stack</p>
					<?php if ( $icons ) : ?>
						<?php // アイコンがあるときはアイコンだけ。同じ内容をタグでも出すと二重になる。 ?>
						<div class="work-detail__icons">
							<?php foreach ( $icons as $icon ) : ?>
								<img src="<?php echo esc_url( takumi_skill_icon_url( $icon ) ); ?>" alt="<?php echo esc_attr( $icon ); ?>" title="<?php echo esc_attr( $icon ); ?>" loading="lazy">
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="work-detail__tags">
							<?php foreach ( $techs as $tech ) : ?>
								<span><?php echo esc_html( $tech ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $facts ) : ?>
				<dl class="work-detail__facts">
					<?php foreach ( $facts as $fact_label => $fact_value ) : ?>
						<div class="work-detail__fact">
							<dt><?php echo esc_html( $fact_label ); ?></dt>
							<dd><?php echo esc_html( $fact_value ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>

			<?php if ( $story ) : ?>
				<div class="work-detail__story">
					<?php foreach ( $story as $story_label => $story_text ) : ?>
						<section class="work-story">
							<h4 class="work-story__head"><?php echo esc_html( $story_label ); ?></h4>
							<p class="work-story__text"><?php echo nl2br( esc_html( $story_text ) ); ?></p>
						</section>
					<?php endforeach; ?>
				</div>
				<?php if ( $body ) : ?>
					<div class="work-detail__desc"><?php echo wp_kses_post( wpautop( $body ) ); ?></div>
				<?php endif; ?>
			<?php elseif ( $body ) : ?>
				<?php // 章立てが無いときも、余白を持つ .work-detail__story で包む(上の事実リストと詰まるため) ?>
				<div class="work-detail__story">
					<section class="work-story">
						<h4 class="work-story__head">概要</h4>
						<div class="work-detail__desc"><?php echo wp_kses_post( wpautop( $body ) ); ?></div>
					</section>
				</div>
			<?php endif; ?>

			<?php if ( $meta( 'url' ) || $meta( 'github' ) ) : ?>
				<div class="work-detail__links">
					<?php if ( $meta( 'url' ) ) : ?>
						<a class="btn" href="<?php echo esc_url( $meta( 'url' ) ); ?>" target="_blank" rel="noopener">Visit Site</a>
					<?php endif; ?>
					<?php if ( $meta( 'github' ) ) : ?>
						<a class="btn btn--gold" href="<?php echo esc_url( $meta( 'github' ) ); ?>" target="_blank" rel="noopener">GitHub</a>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<?php // リンクが無い案件で行き止まりにしない。理由を置いて次の行動につなげる。 ?>
				<p class="work-detail__nolink"><?php echo esc_html( $meta( 'note' ) ? $meta( 'note' ) : '公開URLのない案件です。画面や実装の詳細は面談時にご紹介できます。' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
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
 * 制作実績インデックスの1行(見出し行 + 開閉パネル)を出力する
 * JS が無い環境ではパネルが開いたまま読めるようにしておく。
 */
function takumi_render_work_record( $post, $index ) {
	list( $label, $metric, $sub ) = takumi_work_record_parts( $post );
	$anchor = takumi_work_anchor( $post );
	$panel  = $anchor . '-panel';
	$meta   = fn( $key ) => (string) get_post_meta( $post->ID, '_takumi_' . $key, true );
	$list   = fn( $key ) => implode( ',', array_filter( array_map( 'trim', explode( ',', $meta( $key ) ) ) ) );
	?>
	<div class="work-record-item" id="<?php echo esc_attr( $anchor ); ?>"
		data-year="<?php echo esc_attr( $meta( 'year' ) ); ?>"
		data-tech="<?php echo esc_attr( $list( 'tech' ) ); ?>"
		data-type="<?php echo esc_attr( $list( 'type' ) ); ?>"
		data-category="<?php echo esc_attr( $list( 'category' ) ); ?>">
		<?php // 詳細はモーダルで開く。JS 無効時はパネルがそのまま展開されたまま読める。 ?>
		<button type="button" class="work-record" aria-haspopup="dialog">
			<span class="record-index"><?php echo esc_html( sprintf( '%02d', $index ) ); ?></span>
			<span class="record-title">
				<small><?php echo esc_html( $label ); ?></small>
				<strong><?php echo esc_html( get_the_title( $post ) ); ?></strong>
				<?php if ( $sub ) : ?><em><?php echo esc_html( $sub ); ?></em><?php endif; ?>
			</span>
			<span class="record-metric"><?php echo esc_html( $metric ); ?></span>
			<span class="record-arrow record-arrow--mark" aria-hidden="true"><?php takumi_plus_icon(); ?></span>
		</button>
		<div class="work-record__panel" id="<?php echo esc_attr( $panel ); ?>">
			<div class="work-record__inner">
				<?php takumi_render_work_detail( $post ); ?>
			</div>
		</div>
	</div>
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

/**
 * トップページの Work セクションに出す実績を取得する
 *
 * 「トップページに掲載する」にチェックした実績だけを出す。
 * 1件もチェックが無ければ、Work ページと同じ並びをそのまま使う
 * (チェックを付け忘れてもトップが空にならないようにするため)。
 * 件数の上限はカスタマイザーで指定。0 なら上限なし。
 */
function takumi_get_home_works() {
	$works  = takumi_get_works();
	$picked = array_values( array_filter( $works, fn( $work ) => '1' === (string) get_post_meta( $work->ID, '_takumi_home', true ) ) );
	$list   = $picked ? $picked : $works;

	$limit = (int) get_theme_mod( 'takumi_home_work_limit', 0 );

	return $limit > 0 ? array_slice( $list, 0, $limit ) : $list;
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
			array( 'html', 'HTML', '4 yrs', 90, 'LP・WordPressテーマの制作で使用' ),
			array( 'css', 'CSS', '4 yrs', 85, 'レスポンシブとアニメーション実装で使用' ),
			array( 'js', 'JavaScript', '2 yrs', 70, 'UI実装とスクロール演出(GSAP)で使用' ),
			array( 'ts', 'TypeScript', '1 yr', 65, 'crystallography / FUMI をTypeScriptで構築' ),
			array( 'react', 'React', '1 yr', 60, 'gourmet-Maps(React 19)・Apogee(React 18)で使用' ),
			array( 'vite', 'Vite', '1 yr', 60, 'フロントエンドのビルド環境として常用' ),
			array( 'tailwind', 'Tailwind CSS', '1 yr', 55, 'アプリのUIスタイリングで使用' ),
			array( 'php', 'PHP', '2 yrs', 65, 'Laravel・WordPressテーマ開発で使用' ),
			array( 'laravel', 'Laravel', '1 yr', 55, 'Code_Note・Laravel_ToDo を制作' ),
			array( 'java', 'Java', '1 yr', 55, 'Apogee を Spring Boot 3 で構築' ),
			array( 'spring', 'Spring Boot', '1 yr', 50, '認証(Spring Security / OAuth2)まで実装' ),
			array( 'cs', 'C#', '1 yr', 45, 'gourmet-Maps を ASP.NET Core で制作' ),
			array( 'python', 'Python', '2 yrs', 60, '産学連携のDjango開発と基本構文の習得' ),
			array( 'django', 'Django', 'Learning', 35, '産学連携プロジェクトで制作経験あり' ),
			array( 'nodejs', 'Node.js', '1 yr', 55, 'Express 5 + TypeScript でREST APIを実装' ),
			array( 'mysql', 'MySQL', '1 yr', 55, 'Apogee・Code_Note のテーブル設計とJPA経由の操作' ),
			array( 'docker', 'Docker', '1 yr', 55, 'docker compose で開発環境を構築' ),
			array( 'git', 'Git', '2 yrs', 70, 'ブランチ運用を含めた日常的なバージョン管理' ),
			array( 'github', 'GitHub', '2 yrs', 70, 'チーム開発とGitHub Actionsでの自動化' ),
			array( 'wordpress', 'WordPress', '4 yrs', 90, 'オリジナルテーマの制作で使用' ),
			array( 'threejs', 'Three.js', 'Learning', 30, '本サイトの3D演出で使用' ),
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


/* ============================================================
   管理画面から編集できる文言・データの取り出し
   ============================================================ */

/**
 * 改行入りの見出しを <br> 区切りのHTMLに変換する（各行はエスケープ済み）
 */
function takumi_heading_html( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	$lines = array_filter( array_map( 'trim', $lines ), 'strlen' );
	return implode( '<br>', array_map( 'esc_html', $lines ) );
}

/**
 * Statement セクションの項目を取得する
 * 各要素: array( 種類, 文字 ) 種類: xl-grad / xl-outline / xl-grad2 / text / shape
 */
function takumi_get_statement_items() {
	$raw   = (string) get_theme_mod( 'takumi_statement', TAKUMI_STATEMENT_DEFAULT );
	$kinds = array( 'xl-grad', 'xl-outline', 'xl-grad2', 'text', 'shape' );
	$shapes = array( 'diamond', 'ring', 'star' );
	$items = array();

	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line || false === strpos( $line, '|' ) ) {
			continue;
		}
		list( $kind, $value ) = array_map( 'trim', explode( '|', $line, 2 ) );
		// 種類の綴り間違いはそのまま出すとCSSクラスが崩れるので、既定の種類に寄せる。
		if ( ! in_array( $kind, $kinds, true ) ) {
			$kind = 'text';
		}
		if ( 'shape' === $kind && ! in_array( $value, $shapes, true ) ) {
			continue;
		}
		if ( '' === $value ) {
			continue;
		}
		$items[] = array( $kind, $value );
	}

	return $items;
}

/**
 * 個人開発一覧を取得（管理画面「個人開発」に投稿がなければ既定値を返す）
 * 各要素: array( 'cat' => 英字ラベル, 'title' => 見出し(改行可), 'text' => 説明, 'meta' => メモ, 'icons' => array, 'url' => URL )
 */
function takumi_get_builds_data() {
	$posts = get_posts( array(
		'post_type'      => 'build',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );

	if ( ! $posts ) {
		$repo = trailingslashit( get_theme_mod( 'takumi_github', 'https://github.com/74616b756d69' ) );
		return array(
			array(
				'cat'   => 'APOGEE',
				'title' => "宇宙の打ち上げを、\n日々の予定に並べる",
				'text'  => '次の打ち上げまでのカウントダウン、機関の検索、打ち上げ統計。Apple Calendar と連携して、自分の予定として取り込めるところまで作りました。',
				'meta'  => '設計・API・UI すべて一人で / Spring Boot 3 + React 18 + MySQL',
				'icons' => array( 'java', 'spring', 'react', 'mysql', 'docker' ),
				'url'   => $repo . 'Apogee',
			),
			array(
				'cat'   => 'TABE MAP',
				'title' => "食べたものを、\n地図と順位で残す",
				'text'  => '味・コスパ・雰囲気・接客・また行きたいかの5軸で評価して、地図のピンとランキングに貯めていく記録アプリ。招待コード制のログイン付きです。',
				'meta'  => 'ASP.NET Core 9 + React 19 / EF Core・Identity・Leaflet',
				'icons' => array( 'cs', 'dotnet', 'react', 'vite', 'docker' ),
				'url'   => $repo . 'gourmet-Maps',
			),
			array(
				'cat'   => 'CRYSTALLOGRAPHY',
				'title' => "結晶構造を、\nブラウザで覗く",
				'text'  => '公開データベース COD の API から CIF を取ってきて、そのまま 3D で表示する試作。フロントも API も TypeScript で書いています。',
				'meta'  => 'Express 5 + TypeScript + Vite / Docker Compose',
				'icons' => array( 'ts', 'vite', 'nodejs', 'express', 'docker' ),
				'url'   => $repo . 'crystallography',
			),
			array(
				'cat'   => 'CODE NOTE',
				'title' => "書いたコードを、\nあとから引き出す",
				'text'  => 'Markdown のリアルタイムプレビュー、ラベルでの分類、画像の埋め込み。自分がほしかったものをそのまま作ったノートアプリです。',
				'meta'  => 'Laravel + MySQL / CodeMirror・commonmark',
				'icons' => array( 'php', 'laravel', 'mysql', 'js' ),
				'url'   => $repo . 'Code_Note',
			),
			array(
				'cat'   => 'FUMI',
				'title' => "Mac で、\n宛名を印刷したかった",
				'text'  => '筆まめのようなソフトが Mac に無かったので、自分で作り始めました。年賀状の宛名面を組んで、そのまま印刷に回すための個人ツールです。',
				'meta'  => '制作中 / TypeScript + Vite + Tailwind CSS',
				'icons' => array( 'ts', 'vite', 'tailwind', 'css' ),
				'url'   => $repo . 'FUMI',
			),
		);
	}

	return array_map( function ( $post ) {
		$meta = fn( $key ) => (string) get_post_meta( $post->ID, '_takumi_' . $key, true );
		return array(
			'cat'   => get_the_title( $post ),
			// 見出し未入力ならタイトルをそのまま見出しに使う。
			'title' => '' !== $meta( 'headline' ) ? $meta( 'headline' ) : get_the_title( $post ),
			'text'  => $meta( 'text' ),
			'meta'  => $meta( 'meta' ),
			'icons' => array_filter( array_map( 'trim', explode( ',', $meta( 'icons' ) ) ) ),
			'url'   => $meta( 'url' ),
		);
	}, $posts );
}

/**
 * サイトロゴに表示する文字
 */
function takumi_logo_text() {
	return get_theme_mod( 'takumi_logo_text', "Takumi's Portfolio" );
}
