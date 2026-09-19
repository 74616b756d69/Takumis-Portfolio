<?php
/**
 * 図形の並び：band（横長の帯）。
 *
 * セクションとセクションのあいだに挟む区切り。小さめの図形を
 * 横一列に、高さを少しずつずらして並べている。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$class = ( isset( $args ) && ! empty( $args['class'] ) ) ? $args['class'] : 'shapes';
?>

<svg class="<?php echo esc_attr( $class ); ?>" viewBox="0 0 1200 160"
	preserveAspectRatio="xMidYMid slice" role="presentation" aria-hidden="true"
	xmlns="http://www.w3.org/2000/svg">

	<?php // 破線のリング。 ?>
	<g class="shape" transform="translate(80 78)" style="--depth:14;--fs:1.2;--fd:-1.1s;--ss:1.4;--sd:-4s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<circle class="line-ink line-thin" r="26" stroke-dasharray="7 11"/>
		</g></g></g>
	</g>

	<?php // 小さな円。 ?>
	<g class="shape" transform="translate(210 58)" style="--depth:22;--fs:.9;--fd:-3.4s;--ss:.6;--sd:-7s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__pulse">
			<circle class="fill-1" r="16"/>
		</g></g></g>
	</g>

	<?php // ジグザグ。 ?>
	<g class="shape" transform="translate(340 88)" style="--depth:10;--float-name:drift;--fs:1.5;--fd:-2.2s;--ss:.35;--sd:-1s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="line-3 line-thin" d="M-34,10 L-17,-10 L0,10 L17,-10 L34,10"/>
		</g></g></g>
	</g>

	<?php // 角丸スクエア。 ?>
	<g class="shape" transform="translate(470 70)" style="--depth:18;--fs:1.15;--fd:-5.6s;--ss:1;--sd:-11s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<rect class="fill-3" x="-20" y="-20" width="40" height="40" rx="11"/>
		</g></g></g>
	</g>

	<?php // 波線。 ?>
	<g class="shape" transform="translate(610 92)" style="--depth:8;--fs:1.35;--fd:-4.1s;--ss:.45;--sd:-2.6s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="line-2 line-thin" d="M-54,0 q13.5,-18 27,0 t27,0"/>
		</g></g></g>
	</g>

	<?php // 十字。 ?>
	<g class="shape" transform="translate(740 62)" style="--depth:26;--float-name:float-x;--fs:.8;--fd:-1.8s;--ss:.5;--sd:-9s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-ink line-thin" d="M0,-18 V18 M-18,0 H18"/>
		</g></g></g>
	</g>

	<?php // 三角形。 ?>
	<g class="shape" transform="translate(870 84)" style="--depth:16;--fs:1.05;--fd:-6.3s;--ss:.85;--sd:-5s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="fill-2" d="M0,-26 L23,15 Q26,20 20,20 L-20,20 Q-26,20 -23,15 Z"/>
		</g></g></g>
	</g>

	<?php // アーチ（半円）。 ?>
	<g class="shape" transform="translate(1000 90)" style="--depth:20;--fs:1.28;--fd:-2.9s;--ss:.7;--sd:-13s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="fill-1" d="M-26,12 A26,26 0 0 1 26,12 Z"/>
		</g></g></g>
	</g>

	<?php // きらめき。 ?>
	<g class="shape" transform="translate(1120 64)" style="--depth:30;--float-name:float-x;--fs:1.45;--fd:-.7s;--ss:.28;--sd:-3.5s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__blink">
			<path class="fill-2" d="M0,-24 C2.4,-8.4 8.4,-2.4 24,0 C8.4,2.4 2.4,8.4 0,24 C-2.4,8.4 -8.4,2.4 -24,0 C-8.4,-2.4 -2.4,-8.4 0,-24 Z"/>
		</g></g></g>
	</g>

</svg>
