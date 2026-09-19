<?php
/**
 * 図形の並び：hero（16:9 いっぱいに散らす）。
 *
 * demo/hero-shapes.html の並びそのまま。ページの主役の背面に敷く想定。
 * 速度と遅延は図形ごとに style 属性の変数で散らしてある。
 *   --depth … パララックスの奥行き
 *   --fs / --fd … 浮遊の速さ（倍率）と遅延
 *   --ss / --sd … 回転の速さ（倍率）と遅延
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$class = ( isset( $args ) && ! empty( $args['class'] ) ) ? $args['class'] : 'shapes';
?>

<svg class="<?php echo esc_attr( $class ); ?>" viewBox="0 0 960 540"
	preserveAspectRatio="xMidYMid slice" role="presentation" aria-hidden="true"
	xmlns="http://www.w3.org/2000/svg">

	<?php // 1. 円。 ?>
	<g class="shape" transform="translate(150 150)" style="--depth:26;--fs:1;--fd:-.4s;--ss:1;--sd:-3s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<circle class="fill-1" r="46"/>
		</g></g></g>
	</g>

	<?php // 2. 三角形。 ?>
	<g class="shape" transform="translate(810 130)" style="--depth:34;--float-name:float-x;--fs:1.45;--fd:-2.7s;--ss:.72;--sd:-8s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="fill-2" d="M0,-52 L47,30 Q52,40 40,40 L-40,40 Q-52,40 -47,30 Z"/>
		</g></g></g>
	</g>

	<?php // 3. 波線。 ?>
	<g class="shape" transform="translate(480 440)" style="--depth:16;--fs:1.18;--fd:-5.1s;--ss:.4;--sd:-1.5s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="line-1" d="M-78,0 q19.5,-26 39,0 t39,0 t39,0"/>
		</g></g></g>
	</g>

	<?php // 4. リング。 ?>
	<g class="shape" transform="translate(270 430)" style="--depth:42;--float-name:float-x;--fs:1.7;--fd:-1.2s;--ss:1.35;--sd:-12s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<circle class="line-ink" r="35" stroke-dasharray="9 13"/>
		</g></g></g>
	</g>

	<?php // 5. 十字。 ?>
	<g class="shape" transform="translate(680 380)" style="--depth:30;--fs:.86;--fd:-3.9s;--ss:.55;--sd:-6s;--dir:reverse">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<path class="line-ink" d="M0,-30 V30 M-30,0 H30"/>
		</g></g></g>
	</g>

	<?php // 6. 角丸スクエア。 ?>
	<g class="shape" transform="translate(120 330)" style="--depth:20;--fs:1.32;--fd:-6.5s;--ss:.95;--sd:-15s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__spin">
			<rect class="fill-3" x="-30" y="-30" width="60" height="60" rx="16"/>
		</g></g></g>
	</g>

	<?php // 7. ジグザグ。 ?>
	<g class="shape" transform="translate(860 330)" style="--depth:48;--float-name:float-x;--fs:1.58;--fd:-4.3s;--ss:.33;--sd:-2.2s">
		<g class="shape__pllx"><g class="shape__float"><g class="shape__wobble">
			<path class="line-3" d="M-45,14 L-22,-14 L0,14 L22,-14 L45,14"/>
		</g></g></g>
	</g>

</svg>
