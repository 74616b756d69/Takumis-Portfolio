<?php
/**
 * 図形の並び：corner（角に寄せた塊）。
 *
 * カードやモーダルの右上に重ねる想定。右上へ向かって密になり、
 * 左下へ抜けるほど図形が小さく散っていく。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$class = ( isset( $args ) && ! empty( $args['class'] ) ) ? $args['class'] : 'shapes';
?>

<svg class="<?php echo esc_attr( $class ); ?>" viewBox="0 0 400 400"
	preserveAspectRatio="xMaxYMin slice" role="presentation" aria-hidden="true"
	xmlns="http://www.w3.org/2000/svg">

	<?php // 大きな破線リング。角からはみ出させて奥行きを出す。 ?>
	<g class="shape" transform="translate(320 76)" style="--depth:30;--fs:1.6;--fd:-2.4s;--ss:1.5;--sd:-6s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<circle class="line-ink line-thin" r="62" stroke-dasharray="10 16"/>
		</g></g></g>
	</g>

	<?php // 塗りの円。 ?>
	<g class="shape" transform="translate(178 118)" style="--depth:20;--fs:1;--fd:-4.8s;--ss:.9;--sd:-2s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__pulse">
			<circle class="fill-1" r="30"/>
		</g></g></g>
	</g>

	<?php // カプセル。少し傾けて置く。 ?>
	<g class="shape" transform="translate(282 218)" style="--depth:38;--float-name:float-x;--fs:1.3;--fd:-1.3s;--ss:.6;--sd:-9s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<rect class="fill-3" x="-40" y="-13" width="80" height="26" rx="13" transform="rotate(-22)"/>
		</g></g></g>
	</g>

	<?php // 四分円（扇）。 ?>
	<g class="shape" transform="translate(126 268)" style="--depth:16;--fs:.92;--fd:-6.1s;--ss:1.15;--sd:-14s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="fill-2" d="M-18,-18 L30,-18 A48,48 0 0 1 -18,30 Z"/>
		</g></g></g>
	</g>

	<?php // きらめき。 ?>
	<g class="shape" transform="translate(342 318)" style="--depth:44;--fs:1.5;--fd:-.9s;--ss:.3;--sd:-4.4s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__blink">
			<path class="fill-2" d="M0,-30 C3,-10.5 10.5,-3 30,0 C10.5,3 3,10.5 0,30 C-3,10.5 -10.5,3 -30,0 C-10.5,-3 -3,-10.5 0,-30 Z"/>
		</g></g></g>
	</g>

	<?php // 小さな十字。 ?>
	<g class="shape" transform="translate(64 168)" style="--depth:24;--float-name:drift;--fs:.78;--fd:-3.2s;--ss:.5;--sd:-7.5s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-ink line-thin" d="M0,-16 V16 M-16,0 H16"/>
		</g></g></g>
	</g>

</svg>
