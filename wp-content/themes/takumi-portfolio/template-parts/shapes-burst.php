<?php
/**
 * 図形の並び：burst（中心を空けた放射状）。
 *
 * 見出しやボタンの背面に重ねる想定。中央はわざと空けてあるので、
 * 真ん中に文字を置いても図形とぶつからない。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$class = ( isset( $args ) && ! empty( $args['class'] ) ) ? $args['class'] : 'shapes';
?>

<svg class="<?php echo esc_attr( $class ); ?>" viewBox="0 0 720 720"
	preserveAspectRatio="xMidYMid slice" role="presentation" aria-hidden="true"
	xmlns="http://www.w3.org/2000/svg">

	<?php // 上：三角形。 ?>
	<g class="shape" transform="translate(360 86)" style="--depth:28;--fs:1;--fd:-1.6s;--ss:.8;--sd:-4s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="fill-2" d="M0,-38 L34,22 Q38,29 30,29 L-30,29 Q-38,29 -34,22 Z"/>
		</g></g></g>
	</g>

	<?php // 右上：破線リング。 ?>
	<g class="shape" transform="translate(596 154)" style="--depth:40;--float-name:float-x;--fs:1.44;--fd:-3.8s;--ss:1.3;--sd:-9s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<circle class="line-ink line-thin" r="40" stroke-dasharray="9 14"/>
		</g></g></g>
	</g>

	<?php // 右：塗りの円。 ?>
	<g class="shape" transform="translate(648 382)" style="--depth:20;--fs:.9;--fd:-6.2s;--ss:.7;--sd:-12s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__pulse">
			<circle class="fill-1" r="34"/>
		</g></g></g>
	</g>

	<?php // 右下：ジグザグ。 ?>
	<g class="shape" transform="translate(556 590)" style="--depth:16;--float-name:drift;--fs:1.62;--fd:-2.5s;--ss:.36;--sd:-1.8s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="line-3" d="M-42,13 L-21,-13 L0,13 L21,-13 L42,13"/>
		</g></g></g>
	</g>

	<?php // 下：きらめき。 ?>
	<g class="shape" transform="translate(330 646)" style="--depth:34;--fs:1.12;--fd:-.8s;--ss:.32;--sd:-5.5s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__blink">
			<path class="fill-2" d="M0,-40 C4,-14 14,-4 40,0 C14,4 4,14 0,40 C-4,14 -14,4 -40,0 C-14,-4 -4,-14 0,-40 Z"/>
		</g></g></g>
	</g>

	<?php // 左下：角丸スクエア。 ?>
	<g class="shape" transform="translate(126 560)" style="--depth:24;--fs:1.3;--fd:-4.9s;--ss:1;--sd:-16s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<rect class="fill-3" x="-27" y="-27" width="54" height="54" rx="15"/>
		</g></g></g>
	</g>

	<?php // 左：波線。 ?>
	<g class="shape" transform="translate(82 338)" style="--depth:12;--fs:1.2;--fd:-3.1s;--ss:.44;--sd:-2.4s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="line-1" d="M-62,0 q15.5,-21 31,0 t31,0"/>
		</g></g></g>
	</g>

	<?php // 左上：十字。 ?>
	<g class="shape" transform="translate(148 138)" style="--depth:30;--float-name:float-x;--fs:.82;--fd:-5.4s;--ss:.52;--sd:-7s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-ink" d="M0,-26 V26 M-26,0 H26"/>
		</g></g></g>
	</g>

	<?php // 内側：二重の円弧。 ?>
	<g class="shape" transform="translate(492 252)" style="--depth:46;--fs:1.38;--fd:-1.9s;--ss:.62;--sd:-10s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-2 line-thin" d="M-18,0 A18,18 0 0 1 18,0 M-32,0 A32,32 0 0 1 32,0"/>
		</g></g></g>
	</g>

	<?php // 内側：小さな円。 ?>
	<g class="shape" transform="translate(242 486)" style="--depth:50;--float-name:drift;--fs:.96;--fd:-6.8s;--ss:.9;--sd:-13s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__pulse">
			<circle class="fill-3" r="15"/>
		</g></g></g>
	</g>

</svg>
