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

		<?php // さらに手前に、トップ・About と同じ浮遊ブロブを重ねる。 ?>
		<span class="blob blob--1" aria-hidden="true"></span>
		<span class="blob blob--2" aria-hidden="true"></span>
		<span class="blob blob--3" aria-hidden="true"></span>
		<span class="blob blob--4" aria-hidden="true"></span>
		<span class="blob blob--5" aria-hidden="true"></span>

		<div class="container page-hero__inner">
			<p class="page-hero__label"><span>03</span> <?php echo esc_html( get_theme_mod( 'takumi_work_hero_label', 'Selected Work / 2024—2025' ) ); ?></p>
			<h1 class="page-hero__title">Work</h1>
			<p class="page-hero__sub"><?php echo esc_html( get_theme_mod( 'takumi_work_hero_sub', '制作実績' ) ); ?></p>
			<p class="page-hero__lead"><?php echo esc_html( get_theme_mod( 'takumi_work_hero_lead', 'これまでに手掛けた制作物をまとめています。気になる番号を選ぶと、その場で詳細が開きます。' ) ); ?></p>
		</div>
	</section>

	<!-- Field Records — 実績インデックス -->
	<section class="works-section" id="records">
		<?php takumi_shape_field( 'work-index' ); ?>
		<?php takumi_section_art( 'diamond', array( 'x' => '94%', 'y' => '74%', 'size' => 'clamp(96px, 10vw, 158px)' ) ); ?>
		<div class="container">
			<div class="section-kicker" data-reveal>
				<span><?php echo esc_html( $works ? sprintf( '01—%02d', count( $works ) ) : '01—08' ); ?></span>
				<p><?php echo esc_html( get_theme_mod( 'takumi_work_kicker', 'WORK INDEX / FIELD RECORDS' ) ); ?></p>
			</div>

			<div class="works-heading" data-reveal>
				<h2><?php echo takumi_heading_html( get_theme_mod( 'takumi_work_heading', "実装の幅を、\n結果で見せる。" ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 行ごとにエスケープ済み ?></h2>
				<p><?php echo esc_html( get_theme_mod( 'takumi_work_index_desc', '企業・個人・チーム制作を横断し、要件定義から運用まで必要な場所を担当してきました。気になる番号を開くと、その場で詳細が読めます。' ) ); ?></p>
			</div>

			<?php
			// 絞り込みの軸・ラベル・選択肢はカスタマイザー「Work: 絞り込み設定」と
			// 各実績の入力から組み立てる（takumi_get_work_facets）。
			$facets      = takumi_get_work_facets( $works );
			$all_label   = get_theme_mod( 'takumi_work_filter_all', 'All' );
			$reset_label = get_theme_mod( 'takumi_work_filter_reset', 'Reset' );
			$facet_row   = 0;
			?>

			<!-- Filters -->
			<?php if ( $facets ) : ?>
				<div class="records-filter" role="toolbar" aria-label="作品の絞り込み">
					<?php
					foreach ( $facets as $facet_key => $facet ) :
						$facet_row++;
						?>
						<div class="records-filter__row">
							<span class="records-filter__label"><?php echo esc_html( $facet['label'] ); ?></span>

							<div class="records-filter__track" data-group="<?php echo esc_attr( $facet_key ); ?>">
								<button type="button" class="filter-btn is-active" data-group="<?php echo esc_attr( $facet_key ); ?>" data-value="all" aria-pressed="true"><?php echo esc_html( $all_label ); ?></button>
								<?php foreach ( array_keys( $facet['values'] ) as $facet_value ) : ?>
									<button type="button" class="filter-btn" data-group="<?php echo esc_attr( $facet_key ); ?>" data-value="<?php echo esc_attr( $facet_value ); ?>" aria-pressed="false" tabindex="-1"><?php echo esc_html( takumi_work_facet_value_label( $facet_key, $facet_value ) ); ?><span class="filter-btn__num"></span></button>
								<?php endforeach; ?>

								<?php // 選択中を示す下線。位置と幅は JS が transform で動かす。 ?>
								<span class="records-filter__ink" aria-hidden="true"></span>
							</div>

							<?php if ( 1 === $facet_row ) : ?>
								<div class="records-filter__tools">
									<?php // 件数は JS がレコードから数えて入れる。 ?>
									<p class="records-count" aria-live="polite"><strong>--</strong> / <span class="records-count__total">--</span></p>
									<button type="button" class="filter-reset"><?php echo esc_html( $reset_label ); ?></button>
								</div>
							<?php else : ?>
								<span aria-hidden="true"></span>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="work-records" id="work-records" data-reveal<?php echo $works ? ' data-source="server"' : ''; ?>>
				<?php
				$record_index = 0;
				foreach ( $works as $work ) {
					takumi_render_work_record( $work, ++$record_index );
				}
				?>
			</div>
			<p class="works-empty"><?php echo esc_html( get_theme_mod( 'takumi_work_empty', '条件に一致する作品が見つかりませんでした。' ) ); ?></p>
		</div>
	</section>
</main>

<?php get_footer(); ?>
