<?php
/**
 * WORK RECORDS セクション。
 *
 * 呼び出し例:
 *   get_template_part( 'template-parts/work-records', null, array(
 *       'heading' => "つくったものを、<br>記録として残す。",
 *       'lede'    => 'チーム制作から受託、個人開発まで。',
 *       'limit'   => 6,
 *   ) );
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$args    = isset( $args ) && is_array( $args ) ? $args : array();
$heading = $args['heading'] ?? "つくったものを、<br>記録として残す。";
$lede    = $args['lede'] ?? 'チーム制作から受託、個人開発まで。要件を決めるところから公開後の運用まで、そのとき必要だった役割を担当してきました。';
$label   = $args['label'] ?? 'SELECTED WORK / FIELD RECORDS';
$limit   = $args['limit'] ?? 6;

$records = new WP_Query(
	array(
		'post_type'      => 'work',
		'posts_per_page' => $limit,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);

// 実績が 1 件も無ければセクションごと出さない。
if ( ! $records->have_posts() ) {
	return;
}

$total = $records->post_count;
$index = 0;
?>

<section class="work-records-section" id="works" aria-labelledby="works-title">

	<div class="records-kicker">
		<span>01—<?php echo esc_html( sprintf( '%02d', $total ) ); ?></span>
		<p><?php echo esc_html( $label ); ?></p>
	</div>

	<div class="records-heading">
		<h2 id="works-title"><?php echo wp_kses( $heading, array( 'br' => array() ) ); ?></h2>
		<p><?php echo esc_html( $lede ); ?></p>
	</div>

	<div class="records-list">
		<?php
		while ( $records->have_posts() ) :
			$records->the_post();

			$id     = get_the_ID();
			$index++;
			$number = sprintf( '%02d', $index );

			$cat    = takumi_work_meta( $id, 'cat' );
			$sub    = takumi_work_meta( $id, 'sub' );
			$metric = takumi_work_meta( $id, 'metric' );
			$year   = takumi_work_meta( $id, 'year' );
			$role   = takumi_work_meta( $id, 'role' );
			$stack  = takumi_work_meta( $id, 'stack' );
			$url    = takumi_work_meta( $id, 'url' );
			$github = takumi_work_meta( $id, 'github' );
			?>

			<div class="record-item">

				<a class="record" href="<?php the_permalink(); ?>" aria-haspopup="dialog">
					<span class="record-index"><?php echo esc_html( $number ); ?></span>
					<span class="record-body">
						<?php if ( $cat ) : ?>
							<small class="record-cat"><?php echo esc_html( $cat ); ?></small>
						<?php endif; ?>
						<strong class="record-name"><?php the_title(); ?></strong>
						<?php if ( $sub ) : ?>
							<em class="record-sub"><?php echo esc_html( $sub ); ?></em>
						<?php endif; ?>
					</span>
					<span class="record-metric"><?php echo esc_html( $metric ); ?></span>
					<span class="record-arrow" aria-hidden="true">
						<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
					</span>
				</a>

				<?php // モーダルの中身。JS がここを複製して <dialog> に差し込む。 ?>
				<div class="record-detail" hidden>
					<?php if ( $cat ) : ?>
						<span class="rv-cat"><?php echo esc_html( $cat ); ?></span>
					<?php endif; ?>

					<div>
						<h3 id="rv-title"><?php the_title(); ?></h3>
						<?php if ( $sub ) : ?>
							<p class="rv-sub"><?php echo esc_html( $sub ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="rv-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
					<?php endif; ?>

					<?php if ( get_the_content() ) : ?>
						<div class="rv-desc"><?php the_content(); ?></div>
					<?php endif; ?>

					<dl class="rv-meta">
						<div>
							<dt>RECORD</dt>
							<dd><?php echo esc_html( $number . ' / ' . sprintf( '%02d', $total ) ); ?></dd>
						</div>
						<?php
						$meta_rows = array(
							'YEAR'      => $year,
							'ROLE'      => $role,
							'STACK'     => $stack,
							'HIGHLIGHT' => $metric,
						);
						foreach ( $meta_rows as $dt => $dd ) :
							if ( ! $dd ) {
								continue;
							}
							?>
							<div>
								<dt><?php echo esc_html( $dt ); ?></dt>
								<dd><?php echo esc_html( $dd ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>

					<?php if ( $url || $github ) : ?>
						<div class="rv-links">
							<?php if ( $url ) : ?>
								<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">VIEW SITE ↗</a>
							<?php endif; ?>
							<?php if ( $github ) : ?>
								<a href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener noreferrer">GITHUB ↗</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

			</div>

		<?php endwhile; ?>
	</div>

</section>

<dialog class="record-dialog" id="record-dialog" aria-labelledby="rv-title"></dialog>

<?php wp_reset_postdata(); ?>
