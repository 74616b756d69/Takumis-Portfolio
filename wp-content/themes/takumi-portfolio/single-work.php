<?php
/**
 * 実績の単体ページ。
 *
 * JS が動いていればトップの行クリックでモーダルが開くため、通常は経由しない。
 * JS 無効時のフォールバック、および直接 URL を共有したいときの受け皿。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$id     = get_the_ID();
	$cat    = takumi_work_meta( $id, 'cat' );
	$sub    = takumi_work_meta( $id, 'sub' );
	$metric = takumi_work_meta( $id, 'metric' );
	$year   = takumi_work_meta( $id, 'year' );
	$role   = takumi_work_meta( $id, 'role' );
	$stack  = takumi_work_meta( $id, 'stack' );
	$url    = takumi_work_meta( $id, 'url' );
	$github = takumi_work_meta( $id, 'github' );
	?>

	<article class="work-records-section">
		<div class="record-visual shapes-host">

			<?php // 記事の右上に図形を重ねる。読み上げ対象ではない装飾。 ?>
			<?php
			get_template_part(
				'template-parts/hero-shapes',
				null,
				array(
					'variant' => 'corner',
					'mode'    => 'over',
					'tone'    => 'dark',
					'quiet'   => true,
				)
			);
			?>

			<?php if ( $cat ) : ?>
				<span class="rv-cat"><?php echo esc_html( $cat ); ?></span>
			<?php endif; ?>

			<div>
				<h1 id="rv-title" class="rv-name"><?php the_title(); ?></h1>
				<?php if ( $sub ) : ?>
					<p class="rv-sub"><?php echo esc_html( $sub ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="rv-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>

			<div class="rv-desc"><?php the_content(); ?></div>

			<dl class="rv-meta">
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

			<div class="rv-links">
				<?php if ( $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">VIEW SITE ↗</a>
				<?php endif; ?>
				<?php if ( $github ) : ?>
					<a href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener noreferrer">GITHUB ↗</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/#works' ) ); ?>">← WORK 一覧へ</a>
			</div>

		</div>
	</article>

<?php
endwhile;

get_footer();
