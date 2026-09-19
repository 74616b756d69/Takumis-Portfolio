<?php
/**
 * 図形の並び：rail（縦の細長い列）。
 *
 * 本文の横、余白のある縦長のスペースに沿わせる想定。
 * 上から下へ、線と面が交互に並ぶ。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$class = ( isset( $args ) && ! empty( $args['class'] ) ) ? $args['class'] : 'shapes';
?>

<svg class="<?php echo esc_attr( $class ); ?>" viewBox="0 0 180 880"
	preserveAspectRatio="xMidYMid slice" role="presentation" aria-hidden="true"
	xmlns="http://www.w3.org/2000/svg">

	<?php // 塗りの円。 ?>
	<g class="shape" transform="translate(96 92)" style="--depth:18;--fs:1;--fd:-2.1s;--ss:.9;--sd:-5s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__pulse">
			<circle class="fill-2" r="28"/>
		</g></g></g>
	</g>

	<?php // 六角形（線）。 ?>
	<g class="shape" transform="translate(74 244)" style="--depth:26;--float-name:drift;--fs:1.35;--fd:-4.6s;--ss:1.2;--sd:-11s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-1 line-thin" d="M0,-32 L28,-16 L28,16 L0,32 L-28,16 L-28,-16 Z"/>
		</g></g></g>
	</g>

	<?php // 縦の波線。 ?>
	<g class="shape" transform="translate(100 400)" style="--depth:12;--fs:1.18;--fd:-1.4s;--ss:.42;--sd:-2.8s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="line-3 line-thin" d="M0,-66 q-22,16.5 0,33 t0,33 t0,33"/>
		</g></g></g>
	</g>

	<?php // 十字。 ?>
	<g class="shape" transform="translate(66 546)" style="--depth:32;--fs:.84;--fd:-5.9s;--ss:.55;--sd:-8s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-ink line-thin" d="M0,-22 V22 M-22,0 H22"/>
		</g></g></g>
	</g>

	<?php // ひとしずく（有機的なかたち）。 ?>
	<g class="shape" transform="translate(104 690)" style="--depth:22;--float-name:float-x;--fs:1.5;--fd:-3.3s;--ss:1.05;--sd:-15s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="fill-1" d="M0,-34 C21,-38 40,-19 36,4 C32,27 13,38 -6,34 C-27,30 -40,11 -34,-10 C-29,-27 -17,-31 0,-34 Z"/>
		</g></g></g>
	</g>

	<?php // 二重の円弧。 ?>
	<g class="shape" transform="translate(76 822)" style="--depth:36;--fs:1.22;--fd:-.6s;--ss:.68;--sd:-3.9s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-2 line-thin" d="M-22,0 A22,22 0 0 1 22,0 M-40,0 A40,40 0 0 1 40,0"/>
		</g></g></g>
	</g>

</svg>
