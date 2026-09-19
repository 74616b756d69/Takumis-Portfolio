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
			<p class="page-hero__sub">制作実績</p>
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
			// 絞り込みの選択肢は、実際に並ぶレコードのデータから組み立てる。
			// ボタンを固定文字列で持つと、管理画面の入力とズレた瞬間に
			// 「押しても 0 件」になるため、値そのものを拾う。
			$facets = array(
			'category' => array( 'label' => 'Category', 'values' => array() ),
				// 既存の軸
				'type' => array( 'label' => 'Type', 'values' => array() ),
				'tech' => array( 'label' => 'Tech', 'values' => array() ),
				'year' => array( 'label' => 'Year', 'values' => array() ),
			);
			if ( $works ) {
				foreach ( $works as $facet_work ) {
					foreach ( array_keys( $facets ) as $facet_key ) {
						$facet_raw = (string) get_post_meta( $facet_work->ID, '_takumi_' . $facet_key, true );
						foreach ( array_filter( array_map( 'trim', explode( ',', $facet_raw ) ) ) as $facet_value ) {
							$facets[ $facet_key ]['values'][ $facet_value ] = true;
						}
					}
				}
			} else {
				// 投稿が未登録のときは works.js 同梱データの語彙に合わせる。
				$facets['type']['values'] = array_fill_keys( array( 'front', 'back', 'design' ), true );
				$facets['tech']['values'] = array_fill_keys( array( 'html/css', 'js', 'php', 'python' ), true );
				$facets['year']['values'] = array_fill_keys( array( '2025', '2024' ), true );
			}
			$facet_row = 0;
			?>

			<!-- Filters -->
			<div class="records-filter" role="toolbar" aria-label="作品の絞り込み">
				<?php
				foreach ( $facets as $facet_key => $facet ) :
					if ( ! $facet['values'] ) {
						continue;
					}
					$facet_row++;
					?>
					<div class="records-filter__row">
						<span class="records-filter__label"><?php echo esc_html( $facet['label'] ); ?></span>

						<div class="records-filter__track" data-group="<?php echo esc_attr( $facet_key ); ?>">
							<button type="button" class="filter-btn is-active" data-group="<?php echo esc_attr( $facet_key ); ?>" data-value="all" aria-pressed="true">All</button>
							<?php foreach ( array_keys( $facet['values'] ) as $facet_value ) : ?>
								<button type="button" class="filter-btn" data-group="<?php echo esc_attr( $facet_key ); ?>" data-value="<?php echo esc_attr( $facet_value ); ?>" aria-pressed="false" tabindex="-1"><?php echo esc_html( $facet_value ); ?><span class="filter-btn__num"></span></button>
							<?php endforeach; ?>

							<?php // 選択中を示す下線。位置と幅は JS が transform で動かす。 ?>
							<span class="records-filter__ink" aria-hidden="true"></span>
						</div>

						<?php if ( 1 === $facet_row ) : ?>
							<div class="records-filter__tools">
								<?php // 件数は JS がレコードから数えて入れる。 ?>
								<p class="records-count" aria-live="polite"><strong>--</strong> / <span class="records-count__total">--</span></p>
								<button type="button" class="filter-reset">Reset</button>
							</div>
						<?php else : ?>
							<span aria-hidden="true"></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="work-records" id="work-records" data-reveal<?php echo $works ? ' data-source="server"' : ''; ?>>
				<?php
				$record_index = 0;
				foreach ( $works as $work ) {
					takumi_render_work_record( $work, ++$record_index );
				}
				?>
			</div>
			<p class="works-empty">条件に一致する作品が見つかりませんでした。</p>
		</div>
	</section>
</main>

<?php get_footer(); ?>
