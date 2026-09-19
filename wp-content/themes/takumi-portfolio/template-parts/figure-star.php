<?php
/**
 * 図形：星（とどける）。
 *
 * 4方向のスパークル。主役1つと、時間差で光る小さな星2つ。
 * グラデーションとグレインの定義は template-parts/marquee.php 側にある。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;
?>

<svg class="fig fig--star" viewBox="0 0 300 300" aria-hidden="true" focusable="false"
	xmlns="http://www.w3.org/2000/svg">

	<?php // 主役の星。 ?>
	<g transform="translate(150 150)">
		<g class="st-blink">
			<g class="st-swing">
				<path filter="url(#mq-grain)" fill="url(#mq-st-a)"
					d="M0,-118 C7,-40 40,-7 118,0 C40,7 7,40 0,118 C-7,40 -40,7 -118,0 C-40,-7 -7,-40 0,-118 Z"/>
			</g>
		</g>
	</g>

	<?php // 届いた先で一緒に光る、小さな星。 ?>
	<g transform="translate(252 68)">
		<g class="st-blink st-blink--2">
			<g class="st-swing st-swing--2">
				<path filter="url(#mq-grain)" fill="url(#mq-st-b)"
					d="M0,-38 C2,-13 13,-2 38,0 C13,2 2,13 0,38 C-2,13 -13,2 -38,0 C-13,-2 -2,-13 0,-38 Z"/>
			</g>
		</g>
	</g>

	<g transform="translate(56 240)">
		<g class="st-blink st-blink--3">
			<g class="st-swing st-swing--3">
				<path filter="url(#mq-grain)" fill="url(#mq-st-c)"
					d="M0,-26 C1.5,-9 9,-1.5 26,0 C9,1.5 1.5,9 0,26 C-1.5,9 -9,1.5 -26,0 C-9,-1.5 -1.5,-9 0,-26 Z"/>
			</g>
		</g>
	</g>

</svg>
