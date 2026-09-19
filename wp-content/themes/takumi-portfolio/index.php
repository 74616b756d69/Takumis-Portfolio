<?php
/**
 * 汎用テンプレート（フォールバック）。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="work-records-section">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p>記事がまだありません。</p>
	<?php endif; ?>
</div>

<?php
get_footer();
