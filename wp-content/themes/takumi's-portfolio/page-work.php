<?php
/**
 * Template Name: Work(制作実績)
 * 固定ページ「work」用テンプレート
 */
get_header();

$works = takumi_get_works();
?>

<main>
	<section class="page-hero">
		<canvas class="stars-canvas"></canvas>
		<div class="container page-hero__inner">
			<p class="page-hero__label"><span>03</span> Selected Work / 2024—2025</p>
			<h1 class="page-hero__title">Work</h1>
			<p class="page-hero__sub">制作実績</p>
			<p class="page-hero__lead">これまでに手掛けた制作物をまとめています。インデックスから気になる番号を選ぶと、その詳細へ移動します。</p>
		</div>
	</section>

	<!-- Field Records — 実績インデックス -->
	<section class="works-section" id="records">
		<div class="container">
			<div class="section-kicker" data-reveal>
				<span><?php echo esc_html( $works ? sprintf( '01—%02d', count( $works ) ) : '01—08' ); ?></span>
				<p>WORK INDEX / FIELD RECORDS</p>
			</div>

			<div class="works-heading" data-reveal>
				<h2>実装の幅を、<br>結果で見せる。</h2>
				<p>企業・個人・チーム制作を横断し、要件定義から運用まで必要な場所を担当してきました。気になる番号を選ぶと、下の詳細へ移動します。</p>
			</div>

			<div class="work-records" id="work-records" data-reveal<?php echo $works ? ' data-source="server"' : ''; ?>>
				<?php
				$record_index = 0;
				foreach ( $works as $work ) {
					takumi_render_work_record( $work, ++$record_index );
				}
				?>
			</div>
		</div>
	</section>

	<section class="section" style="padding-top: 40px;">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-head__num">02</span>
				<h2 class="section-head__en">Details</h2>
				<p class="section-head__ja">作品詳細</p>
			</div>

			<!-- Filters -->
			<div class="filter-groups" data-reveal>
				<div class="filter-row">
					<span class="filter-row__label">Type</span>
					<button class="filter-btn" data-group="type" data-value="front">フロントエンド</button>
					<button class="filter-btn" data-group="type" data-value="back">バックエンド</button>
					<button class="filter-btn" data-group="type" data-value="design">デザイン</button>
				</div>
				<div class="filter-row">
					<span class="filter-row__label">Tech</span>
					<button class="filter-btn" data-group="tech" data-value="html/css">HTML/CSS</button>
					<button class="filter-btn" data-group="tech" data-value="js">JavaScript</button>
					<button class="filter-btn" data-group="tech" data-value="php">PHP</button>
					<button class="filter-btn" data-group="tech" data-value="python">Python</button>
				</div>
				<div class="filter-row">
					<span class="filter-row__label">Year</span>
					<button class="filter-btn" data-group="year" data-value="2025">2025</button>
					<button class="filter-btn" data-group="year" data-value="2024">2024</button>
					<button class="filter-reset">すべて表示</button>
				</div>
			</div>

			<?php if ( $works ) : ?>
				<!-- 制作実績(カスタム投稿)から描画 -->
				<div class="works-grid" id="works-grid" data-source="server">
					<?php foreach ( $works as $work ) {
						takumi_render_work_card( $work );
					} ?>
				</div>
			<?php else : ?>
				<!-- 投稿が未登録の場合は works.js の同梱データで描画 -->
				<div class="works-grid" id="works-grid"></div>
			<?php endif; ?>
			<p class="works-empty">条件に一致する作品が見つかりませんでした。</p>
		</div>
	</section>
</main>

<?php get_footer(); ?>
