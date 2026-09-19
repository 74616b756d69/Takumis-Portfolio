<?php
/**
 * 図形：ダイヤモンド（つくる）。
 *
 * 12枚のファセットを面のみで構成。グラデーションとグレインの定義は
 * template-parts/marquee.php 側にあり、ここでは id を参照するだけ。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;
?>

<svg class="fig fig--diamond" viewBox="0 0 260 340" aria-hidden="true" focusable="false"
	xmlns="http://www.w3.org/2000/svg">

	<g class="dm-float">
		<g class="dm-swing">
			<g class="dm-turn">

				<?php // 乗算で重ねる範囲を石の中だけに閉じ込める。 ?>
				<g style="isolation: isolate">

					<?php // グレインはファセット全体にまとめて掛ける。 ?>
					<g filter="url(#mq-grain)">
					<path class="dm-facet" fill="url(#mq-dg-c)" d="M130,20 L44,146 L88,146 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-e)" d="M130,20 L88,146 L130,80 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-a)" d="M130,20 L130,80 L172,146 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-b)" d="M130,20 L172,146 L216,146 Z"/>

					<path class="dm-facet" fill="url(#mq-dg-d)" d="M44,146 L130,292 L130,212 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-c)" d="M44,146 L130,212 L88,146 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-a)" d="M172,146 L130,212 L130,292 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-e)" d="M172,146 L130,292 L216,146 Z"/>

					<path class="dm-facet" fill="url(#mq-dg-f)" d="M88,146 L130,80 L130,146 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-b)" d="M130,80 L172,146 L130,146 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-a)" d="M88,146 L130,146 L130,212 Z"/>
					<path class="dm-facet" fill="url(#mq-dg-f)" d="M130,146 L172,146 L130,212 Z"/>
					</g>

					<?php // きらっと横切るハイライト。傾きは石の重心まわりの回転で作る。 ?>
					<g clip-path="url(#mq-dg-clip)" style="mix-blend-mode: screen">
						<g transform="rotate(-18 130 156)">
							<g class="dm-shine">
								<rect x="100" y="-150" width="60" height="620" fill="url(#mq-dg-shine)"/>
							</g>
						</g>
					</g>

				</g>

				<?php // 光が頂点に達した瞬間に弾けるスパークル。 ?>
				<g transform="translate(130 20)">
					<g class="dm-spark">
						<path d="M0,-16 Q3,-3 16,0 Q3,3 0,16 Q-3,3 -16,0 Q-3,-3 0,-16 Z" fill="#fff"/>
					</g>
				</g>

			</g>
		</g>
	</g>

</svg>
