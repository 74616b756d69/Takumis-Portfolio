<?php
/**
 * 図形レイヤーの呼び出し口。
 *
 * 図形の並びは template-parts/shapes-{variant}.php に分けてある。
 * どのページでも、置きたい場所でこれを呼ぶだけでよい。
 *
 * 呼び出し例:
 *   // セクションの区切りに、帯として流し込む。
 *   get_template_part( 'template-parts/hero-shapes', null, array(
 *       'variant' => 'band',
 *   ) );
 *
 *   // 親要素に重ねる（親に class="shapes-host" を付けておく）。
 *   get_template_part( 'template-parts/hero-shapes', null, array(
 *       'variant' => 'corner',
 *       'mode'    => 'over',
 *       'tone'    => 'dark',
 *       'quiet'   => true,
 *   ) );
 *
 * 引数:
 *   variant … hero / band / corner / rail / burst（既定は hero）
 *   mode    … inline（流し込み・既定） / over（親に重ねる）
 *   tone    … dark（暗い地の上） / brand（サイト本来の配色）
 *   quiet   … true で存在を薄くする
 *   class   … 外側 div に足すクラス
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();

$variant = $args['variant'] ?? 'hero';
if ( ! in_array( $variant, array( 'hero', 'band', 'corner', 'rail', 'burst' ), true ) ) {
	$variant = 'hero';
}

$tone = $args['tone'] ?? '';
if ( ! in_array( $tone, array( 'dark', 'brand' ), true ) ) {
	$tone = '';
}

$is_over = ( $args['mode'] ?? 'inline' ) === 'over';

$layer_class = 'shapes-layer';
if ( $is_over ) {
	$layer_class .= ' shapes-layer--over';
}
if ( ! empty( $args['class'] ) ) {
	$layer_class .= ' ' . $args['class'];
}

$svg_class = 'shapes shapes--' . $variant;
if ( $tone ) {
	$svg_class .= ' shapes--' . $tone;
}
if ( ! empty( $args['quiet'] ) ) {
	$svg_class .= ' shapes--quiet';
}
?>

<div class="<?php echo esc_attr( $layer_class ); ?>">
	<?php
	get_template_part(
		'template-parts/shapes',
		$variant,
		array(
			'class' => $svg_class,
		)
	);
	?>
</div>
