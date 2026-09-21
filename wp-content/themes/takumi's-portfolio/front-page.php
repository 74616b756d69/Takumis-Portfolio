<?php
/**
 * フロントページテンプレート — 記録簿(FIELD RECORDS)スタイルのトップ
 */
get_header();

$uri    = get_template_directory_uri();
$email  = get_theme_mod( 'takumi_email', 'akahori.t.24kdgn@gmail.com' );
$github = get_theme_mod( 'takumi_github', 'https://github.com/74616b756d69' );
$face   = get_theme_mod( 'takumi_face' ) ?: $uri . '/assets/img/akahori-icon-512.png';
$works  = takumi_get_home_works(); // 掲載する実績は管理画面「制作実績」の「トップページに掲載する」で選ぶ
?>

<main class="home">

	<!-- Hero -->
	<section class="home-hero" id="top">
		<canvas class="stars-canvas"></canvas>
		<span class="blob blob--1" aria-hidden="true"></span>
		<span class="blob blob--2" aria-hidden="true"></span>
		<span class="blob blob--3" aria-hidden="true"></span>
		<span class="blob blob--4" aria-hidden="true"></span>
		<span class="blob blob--5" aria-hidden="true"></span>
		<div class="container home-hero__inner">
			<p class="page-hero__label"><span>—</span> <?php echo esc_html( get_theme_mod( 'takumi_hero_label', 'Portfolio / Web Developer' ) ); ?></p>

			<h1 class="home-hero__title">
				<?php
				// 1行ずつに分けて組む(2行目だけ差し色)
				$statement = get_theme_mod( 'takumi_hero_statement', "BUILDING\nTHE FUTURE,\nONE LINE AT A TIME." );
				$lines     = preg_split( '/\r\n|\r|\n/', $statement );
				foreach ( $lines as $i => $line ) {
					printf(
						'<span class="%s">%s</span>',
						1 === $i ? 'is-accent' : '',
						esc_html( $line )
					);
				}
				?>
			</h1>

			<p class="home-hero__name">
				<?php echo esc_html( get_theme_mod( 'takumi_name_ja', '赤堀 匠海' ) ); ?>
				<span><?php echo esc_html( get_theme_mod( 'takumi_name_en', 'Takumi Akahori' ) ); ?></span>
			</p>

			<p class="home-hero__lead"><?php echo esc_html( get_theme_mod( 'takumi_top_tagline', 'フロントエンドからバックエンドまで、想いをかたちにする。' ) ); ?></p>

			<p class="home-hero__scroll"><?php echo esc_html( get_theme_mod( 'takumi_hero_scroll', 'Scroll' ) ); ?></p>
		</div>
	</section>

	<!-- 01 Profile -->
	<section class="section section--loose" id="profile">
		<?php takumi_shape_field( 'profile' ); ?>
		<div class="container">
			<?php // 見出しは置かない。すぐ下の名前がこのセクションの見出しになる。 ?>
			<p class="lead__meta lead__meta--solo" data-reveal><span>01</span><?php echo esc_html( get_theme_mod( 'takumi_top_profile_en', 'Profile' ) ); ?> <i><?php echo esc_html( get_theme_mod( 'takumi_top_profile_ja', '私について' ) ); ?></i></p>

			<div class="profile-grid">
				<div class="profile-photo" data-reveal>
					<img src="<?php echo esc_url( $face ); ?>" alt="プロフィールイラスト">
					<span class="profile-photo__tag"><?php echo esc_html( get_theme_mod( 'takumi_profile_photo_tag', 'Web Developer' ) ); ?></span>
				</div>

				<div class="profile-body" data-reveal>
					<p class="name-ja"><?php echo esc_html( get_theme_mod( 'takumi_name_ja', '赤堀 匠海' ) ); ?></p>
					<p class="name-en"><?php echo esc_html( get_theme_mod( 'takumi_name_en', 'Akahori Takumi' ) ); ?></p>
					<p class="motto"><?php echo esc_html( get_theme_mod( 'takumi_motto', 'Behind every smile lies effort' ) ); ?></p>

					<div class="bio">
						<p><?php echo esc_html( get_theme_mod( 'takumi_bio', '2004年岐阜県生まれ。KADOKAWAドワンゴ情報工科学院に在籍し、Web開発を学習中。産学連携プロジェクトではチームリーダーを経験。' ) ); ?></p>
					</div>

					<?php takumi_render_profile_facts(); ?>

					<div class="profile-actions">
						<a class="btn" href="<?php echo esc_url( takumi_page_url( 'about' ) ); ?>"><?php echo esc_html( get_theme_mod( 'takumi_top_profile_btn', 'More About Me' ) ); ?></a>
						<div class="profile-sns">
							<a href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener" aria-label="GitHub"><img src="<?php echo esc_url( $uri ); ?>/assets/img/github-brands.svg" alt="GitHub"></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Statement — スクロールでピン留めし、巨大テキストが横に流れる -->
	<?php
	// 横に流れる大きな文字。内容は管理画面「セクション見出し設定」で編集する。
	$statement = takumi_get_statement_items();

	$plain = '';
	foreach ( $statement as $item ) {
		if ( 'shape' !== $item[0] ) {
			$plain .= $item[1];
		}
	}
	?>
	<?php takumi_flow_line(); ?>
	<section class="statement" id="statement">
		<p class="visually-hidden"><?php echo esc_html( $plain ); ?></p>
		<div class="statement__pin" aria-hidden="true">
			<p class="statement__label"><?php echo esc_html( get_theme_mod( 'takumi_statement_label', 'Scroll — 横に流れます' ) ); ?></p>
			<div class="statement__track" id="statement-track">
				<?php foreach ( $statement as $item ) : ?>
					<?php if ( 'shape' === $item[0] ) : ?>
						<span class="statement__shape statement__shape--<?php echo esc_attr( $item[1] ); ?>"><?php takumi_shape_svg( $item[1] ); ?></span>
					<?php else : ?>
						<span class="statement__word statement__word--<?php echo esc_attr( $item[0] ); ?>"><?php echo esc_html( $item[1] ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php takumi_flow_line( 'flow-line--flip' ); ?>

	<!-- 03 Work -->
	<section class="section" id="work">
		<?php takumi_shape_field( 'work' ); ?>
		<div class="container">
			<div class="lead" data-reveal>
				<p class="lead__meta"><span>03</span><?php echo esc_html( get_theme_mod( 'takumi_top_work_en', 'Work' ) ); ?> <i><?php echo esc_html( get_theme_mod( 'takumi_top_work_ja', '制作実績' ) ); ?></i></p>
				<div class="lead__body">
					<h2 class="lead__title"><?php echo takumi_heading_html( get_theme_mod( 'takumi_work_heading', "実装の幅を、\n結果で見せる。" ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 行ごとにエスケープ済み ?></h2>
					<p class="lead__lede"><?php echo esc_html( get_theme_mod( 'takumi_top_work_desc', '個人制作から産学連携・実案件まで。チームリーダーとして指揮したプロジェクトも紹介しています。' ) ); ?></p>
				</div>
			</div>

			<?php // 実績の写真を円周に並べた棚。見出しは上の 03 Work をそのまま使うので bare で出す。 ?>
			<?php takumi_card_ring( array( 'bare' => true ) ); ?>

			<?php if ( $works ) : ?>
				<div class="work-records" data-reveal data-source="server">
					<?php
					$index = 0;
					foreach ( $works as $work ) {
						takumi_render_work_record( $work, ++$index );
					}
					?>
				</div>
			<?php else : ?>
				<div class="home-thumbs" data-reveal>
					<?php
					foreach ( array( 'YLMEMORIA/YL MEMORIA.png', 'hikariwo/HiKaRiWo_LP.png', 'img/portfolio.png' ) as $img ) {
						printf(
							'<a href="%s"><img src="%s" alt="制作実績" loading="lazy"></a>',
							esc_url( takumi_page_url( 'work' ) ),
							esc_url( $uri . '/assets/img/' . $img )
						);
					}
					?>
				</div>
			<?php endif; ?>

			<p class="section-more" data-reveal>
				<a href="<?php echo esc_url( takumi_page_url( 'work' ) ); ?>"><?php echo esc_html( get_theme_mod( 'takumi_top_work_more', '実績の一覧を見る' ) ); ?></a>
			</p>
		</div>
	</section>

	<?php takumi_flow_line(); ?>

	<!-- 04 Contact -->
	<section class="section section--loose" id="contact">
		<?php takumi_shape_field( 'contact' ); ?>
		<div class="container">
			<div class="lead" data-reveal>
				<p class="lead__meta"><span>04</span><?php echo esc_html( get_theme_mod( 'takumi_top_contact_en', 'Contact' ) ); ?> <i><?php echo esc_html( get_theme_mod( 'takumi_top_contact_ja', 'お問い合わせ' ) ); ?></i></p>
				<div class="lead__body">
					<h2 class="lead__title"><?php echo takumi_heading_html( get_theme_mod( 'takumi_contact_heading', "話を聞くところから、\nはじめさせてください。" ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 行ごとにエスケープ済み ?></h2>
					<p class="lead__lede"><?php echo wp_kses_post( get_theme_mod( 'takumi_top_contact_text', '最後までご覧いただきありがとうございました。制作のご依頼・ご相談など、お気軽にご連絡ください。' ) ); ?></p>
				</div>
			</div>

			<?php
			// 連絡先。表示用のラベルは URL の末尾から拾う（@ハンドルとして見せる）。
			$channels = array(
				array(
					'key'   => 'Email',
					'label' => $email,
					'href'  => 'mailto:' . $email,
					'ext'   => false,
				),
				array(
					'key'   => 'GitHub',
					'label' => '@' . basename( untrailingslashit( $github ) ),
					'href'  => $github,
					'ext'   => true,
				),
			);
			?>

			<?php // 連絡先は Work の記録行と同じ「1 行ずつ罫線で区切る」形に揃える。 ?>
			<ul class="contact-channels" data-reveal>

				<?php foreach ( $channels as $channel ) : ?>
					<li>
						<a class="contact-channels__row" href="<?php echo esc_url( $channel['href'] ); ?>"<?php echo $channel['ext'] ? ' target="_blank" rel="noopener"' : ''; ?>>
							<span class="contact-channels__key"><?php echo esc_html( $channel['key'] ); ?></span>
							<span class="contact-channels__val"><?php echo esc_html( $channel['label'] ); ?></span>
							<span class="contact-channels__arrow" aria-hidden="true"><?php takumi_arrow_icon(); ?></span>
						</a>
					</li>
				<?php endforeach; ?>

				<?php
				// フォームだけは外部に飛ばさず、その場で開く。
				// JS が無い環境では開いたまま表示される（下の is-collapsible を JS が付ける）。
				?>
				<?php $form_id = get_theme_mod( 'takumi_contact_form_id', '3b0857e' ); ?>
				<li class="contact-disclosure">
					<?php if ( $form_id && shortcode_exists( 'contact-form-7' ) ) : ?>
						<button type="button" class="contact-channels__row contact-disclosure__toggle"
							aria-expanded="false" aria-controls="contact-form-panel">
							<span class="contact-channels__key">Form</span>
							<span class="contact-channels__val"><?php echo esc_html( get_theme_mod( 'takumi_contact_form_label', 'フォームから送る' ) ); ?></span>
							<span class="contact-channels__arrow contact-disclosure__mark" aria-hidden="true"><?php takumi_plus_icon(); ?></span>
						</button>

						<div class="contact-disclosure__panel" id="contact-form-panel">
							<div class="contact-disclosure__inner">
								<div class="contact-form">
									<?php echo do_shortcode( sprintf( '[contact-form-7 id="%s"]', esc_attr( $form_id ) ) ); ?>
								</div>
							</div>
						</div>
					<?php else : ?>
						<?php // CF7 が無効なとき。開く中身が無いので、行は出さずに一言だけ。 ?>
						<p class="contact-form__fallback"><?php echo esc_html( get_theme_mod( 'takumi_contact_form_fallback', 'フォームは準備中です。上のアドレスへ直接お送りください。' ) ); ?></p>
					<?php endif; ?>
				</li>

			</ul>

		</div>
	</section>

</main>

<?php get_footer(); ?>
