<?php
/**
 * 図形：リング（うごかす）。
 *
 * 太さ・半径・速度・向きの違う円弧3本。端は丸め、中心は空洞のまま。
 * グラデーションとグレインの定義は template-parts/marquee.php 側にある。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;
?>

<svg class="fig fig--ring" viewBox="0 0 300 300" aria-hidden="true" focusable="false"
	xmlns="http://www.w3.org/2000/svg">

	<?php // 外側・細め（285度）。 ?>
	<g class="rg-rot rg-rot--1">
		<path class="rg-arc rg-arc--1" filter="url(#mq-grain)"
			d="M130.55,39.70 A112,112 0 1 1 38.43,140.24"/>
	</g>

	<?php // 中間・いちばん細い（250度・逆回転）。 ?>
	<g class="rg-rot rg-rot--2">
		<path class="rg-arc rg-arc--2" filter="url(#mq-grain)"
			d="M192.00,222.75 A84,84 0 1 1 203.99,85.65"/>
	</g>

	<?php // 内側・太め（220度）。 ?>
	<g class="rg-rot rg-rot--3">
		<path class="rg-arc rg-arc--3" filter="url(#mq-grain)"
			d="M92.88,160.07 A58,58 0 1 1 200.23,179.00"/>
	</g>

</svg>
