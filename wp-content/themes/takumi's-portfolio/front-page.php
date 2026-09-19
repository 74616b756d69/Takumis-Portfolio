<?php
/**
 * フロントページテンプレート — 記録簿(FIELD RECORDS)スタイルのトップ
 */
get_header();

$uri    = get_template_directory_uri();
$email  = get_theme_mod( 'takumi_email', 'akahori.t.24kdgn@gmail.com' );
$github = get_theme_mod( 'takumi_github', 'https://github.com/Akasan-T' );
$face   = get_theme_mod( 'takumi_face' ) ?: $uri . '/assets/img/My_face.jpeg';
$works  = takumi_get_works();
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
			<p class="page-hero__label"><span>—</span> Portfolio / Web Developer</p>

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

			<!-- 実績サマリー -->
			<?php
			$career     = takumi_get_career_data();
			$since      = $career ? preg_replace( '/[^0-9].*$/', '', (string) $career[0][0] ) : '2021';
			$stats      = array(
				array( sprintf( '%02d', count( $works ) ), 'Works' ),
				array( sprintf( '%02d', count( takumi_get_skills_data() ) ), 'Skills' ),
				array( $since, 'Since' ),
			);
			?>
			<div class="home-stats">
				<?php foreach ( $stats as $stat ) : ?>
					<div class="home-stats__item">
						<strong><?php echo esc_html( $stat[0] ); ?></strong>
						<span><?php echo esc_html( $stat[1] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- 目次 -->
			<nav class="home-index" aria-label="ページ内ナビゲーション">
				<a href="#profile"><span class="home-index__num">01</span><strong>Profile</strong><em>私について</em></a>
				<a href="#skill"><span class="home-index__num">02</span><strong>Skill</strong><em>できること</em></a>
				<a href="#work"><span class="home-index__num">03</span><strong>Work</strong><em>制作実績</em></a>
				<a href="#contact"><span class="home-index__num">04</span><strong>Contact</strong><em>お問い合わせ</em></a>
			</nav>

			<p class="home-hero__scroll">Scroll</p>
		</div>
	</section>

	<!-- 01 Profile -->
	<section class="section" id="profile">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-head__num">01</span>
				<h2 class="section-head__en">Profile</h2>
				<p class="section-head__ja">私について</p>
			</div>

			<div class="profile-grid">
				<div class="profile-photo" data-reveal>
					<img src="<?php echo esc_url( $face ); ?>" alt="プロフィール写真">
					<span class="profile-photo__tag">Web Developer</span>
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
						<a class="btn" href="<?php echo esc_url( takumi_page_url( 'about' ) ); ?>">More About Me</a>
						<div class="profile-sns">
							<a href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener" aria-label="GitHub"><img src="<?php echo esc_url( $uri ); ?>/assets/img/github-brands.svg" alt="GitHub"></a>
							<a href="<?php echo esc_url( get_theme_mod( 'takumi_x', 'https://x.com/hori_hori_ak' ) ); ?>" target="_blank" rel="noopener" aria-label="X"><img src="<?php echo esc_url( $uri ); ?>/assets/img/x-solid.svg" alt="X"></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- 02 Skill -->
	<section class="section section--alt" id="skill">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-head__num">02</span>
				<h2 class="section-head__en">Skill</h2>
				<p class="section-head__ja">できること</p>
			</div>
		</div>

		<?php
		// 横スクロールのパネル。内容を変えたいときはこの配列を編集する
		$panels = array(
			array( 'FRONTEND', 'HTML / CSS / JavaScript', 'マークアップとUI実装。レスポンシブ対応やアニメーションまで。', array( 'html', 'css', 'js', 'ts' ) ),
			array( 'BACKEND', 'PHP / Python / Java', 'サーバーサイドの実装とデータ処理を担当します。', array( 'php', 'python', 'java', 'cs' ) ),
			array( 'FRAMEWORK', 'Laravel / Django', 'CRUD・認証・バリデーションを備えたWebアプリの構築。', array( 'laravel', 'django' ) ),
			array( 'DATA & INFRA', 'MySQL / Docker / Git', 'データベース設計とチーム開発の環境づくり。', array( 'mysql', 'docker', 'git', 'github' ) ),
			array( 'CMS & 3D', 'WordPress / Three.js', 'テーマ開発と、Web上での3D表現に挑戦しています。', array( 'wordpress', 'threejs' ) ),
		);
		?>
		<div class="hscroll" id="hscroll">
			<div class="hscroll__inner">
				<p class="hscroll__label">Scroll down — panels move sideways</p>
				<div class="hscroll__track" id="hscroll-track">
					<?php foreach ( $panels as $i => $panel ) : ?>
						<div class="hpanel">
							<span class="hpanel__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?> / <?php echo esc_html( $panel[0] ); ?></span>
							<div>
								<h3><?php echo esc_html( $panel[1] ); ?></h3>
								<p><?php echo esc_html( $panel[2] ); ?></p>
								<div class="hpanel__icons">
									<?php foreach ( $panel[3] as $icon ) : ?>
										<img src="<?php echo esc_url( takumi_skill_icon_url( $icon ) ); ?>" alt="<?php echo esc_attr( $icon ); ?>" loading="lazy">
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="home-skills__body" data-reveal style="margin-top: 40px;">
				<p><?php echo esc_html( get_theme_mod( 'takumi_top_skill_desc', 'フロントエンドからバックエンドまで。HTML/CSSでの制作経験を軸に、Laravel・Django などのフレームワークにも挑戦中です。' ) ); ?></p>
				<a class="btn btn--gold" href="<?php echo esc_url( takumi_page_url( 'about' ) ); ?>#skill">View All Skills</a>
			</div>
		</div>
	</section>

	<!-- Statement — スクロールでピン留めし、巨大テキストが横に流れる -->
	<?php
	// [種別, テキスト] 種別: xl-grad / xl-outline / xl-grad2 / text / shape
	$statement = array(
		array( 'xl-grad', 'つくる。' ),
		array( 'text', '手を動かして、形にする。' ),
		array( 'shape', 'diamond' ),
		array( 'xl-outline', 'うごかす。' ),
		array( 'text', '要件定義から運用まで。' ),
		array( 'shape', 'ring' ),
		array( 'xl-grad2', 'とどける。' ),
		array( 'text', 'チームで、最後まで。' ),
		array( 'shape', 'star' ),
	);

	$plain = '';
	foreach ( $statement as $item ) {
		if ( 'shape' !== $item[0] ) {
			$plain .= $item[1];
		}
	}
	?>
	<section class="statement" id="statement">
		<p class="visually-hidden"><?php echo esc_html( $plain ); ?></p>
		<div class="statement__pin" aria-hidden="true">
			<p class="statement__label">Scroll — 横に流れます</p>
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

	<!-- 03 Work -->
	<section class="section" id="work">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-head__num">03</span>
				<h2 class="section-head__en">Work</h2>
				<p class="section-head__ja">制作実績</p>
			</div>

			<div class="works-heading" data-reveal>
				<h2>実装の幅を、<br>結果で見せる。</h2>
				<p><?php echo esc_html( get_theme_mod( 'takumi_top_work_desc', '個人制作から産学連携・実案件まで。チームリーダーとして指揮したプロジェクトも紹介しています。' ) ); ?></p>
			</div>

			<?php if ( $works ) : ?>
				<div class="work-records" data-reveal>
					<?php
					$work_url = takumi_page_url( 'work' );
					$index    = 0;
					foreach ( $works as $work ) {
						takumi_render_work_record( $work, ++$index, $work_url );
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

			<div class="btn-row" data-reveal>
				<a class="btn" href="<?php echo esc_url( takumi_page_url( 'work' ) ); ?>">View All Works</a>
			</div>
		</div>
	</section>

	<!-- 04 Contact -->
	<section class="section section--alt" id="contact">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-head__num">04</span>
				<h2 class="section-head__en">Contact</h2>
				<p class="section-head__ja">お問い合わせ</p>
			</div>

			<div class="contact-box" data-reveal>
				<p><?php echo wp_kses_post( get_theme_mod( 'takumi_top_contact_text', '最後までご覧いただきありがとうございました。<br>制作のご依頼・ご相談など、お気軽にご連絡ください。' ) ); ?></p>

				<?php if ( shortcode_exists( 'contact-form-7' ) ) : ?>
					<div class="contact-form">
						<?php echo do_shortcode( '[contact-form-7 id="3b0857e" title="Contact form 1"]' ); ?>
					</div>
				<?php endif; ?>

				<div class="contact-links">
					<a class="btn" href="mailto:<?php echo esc_attr( $email ); ?>">Email</a>
					<a class="btn btn--gold" href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener">GitHub</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
