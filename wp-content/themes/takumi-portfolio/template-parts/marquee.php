<?php
/**
 * 横スクロールの装飾バンド。
 *
 * 3つの図形と言葉が、途切れずに横へ流れ続ける帯。
 * 図形そのものは template-parts/figure-{key}.php にある。
 *
 * グラデーションとグレインの定義は、このファイルの先頭で一度だけ出力する。
 * 図形は帯のループのために 2 周ぶん複製されるので、定義を図形側に置くと
 * 同じ id が重複してしまうため。
 *
 * 呼び出し例:
 *   get_template_part( 'template-parts/marquee', null, array(
 *       'speed' => '52s',
 *   ) );
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

$args  = isset( $args ) && is_array( $args ) ? $args : array();
$speed = $args['speed'] ?? '52s';

$items = $args['items'] ?? array(
	array(
		'key'  => 'diamond',
		'word' => 'つくる',
		'note' => 'REQUIREMENTS — DESIGN',
	),
	array(
		'key'  => 'ring',
		'word' => 'うごかす',
		'note' => 'BUILD — OPERATION',
	),
	array(
		'key'  => 'star',
		'word' => 'とどける',
		'note' => 'RELEASE — DELIVER',
	),
);
?>


<?php
// 帯は同じ並びを 2 周ぶん並べ、-50% 動かして先頭に戻すことでシームレスにループする。
// 読み上げでは同じ言葉が 2 回続いてしまうため、セクションごと支援技術から隠す。
?>
<section class="marquee-section" aria-hidden="true">
	<?php // グラデーションとフィルタの定義。描画はせず、id を参照させるためだけに置く。 ?>
	<svg class="mq-defs" width="0" height="0" aria-hidden="true" focusable="false"
		xmlns="http://www.w3.org/2000/svg">
		<defs>

			<?php // --- ダイヤモンド（オレンジ → ピンク） --- ?>
			<linearGradient id="mq-dg-a" gradientUnits="userSpaceOnUse" x1="44" y1="20" x2="216" y2="292">
				<stop offset="0" class="s-o1"/><stop offset="1" class="s-o2"/>
			</linearGradient>
			<linearGradient id="mq-dg-b" gradientUnits="userSpaceOnUse" x1="216" y1="20" x2="44" y2="292">
				<stop offset="0" class="s-o3"/><stop offset="1" class="s-o2"/>
			</linearGradient>
			<linearGradient id="mq-dg-c" gradientUnits="userSpaceOnUse" x1="130" y1="20" x2="130" y2="292">
				<stop offset="0" class="s-o3"/><stop offset="0.55" class="s-o1"/><stop offset="1" class="s-o2"/>
			</linearGradient>
			<linearGradient id="mq-dg-d" gradientUnits="userSpaceOnUse" x1="44" y1="292" x2="216" y2="20">
				<stop offset="0" class="s-o2"/><stop offset="1" class="s-o1"/>
			</linearGradient>
			<radialGradient id="mq-dg-e" gradientUnits="userSpaceOnUse" cx="120" cy="86" r="180">
				<stop offset="0" class="s-o3"/><stop offset="0.5" class="s-o1"/><stop offset="1" class="s-o2"/>
			</radialGradient>
			<linearGradient id="mq-dg-f" gradientUnits="userSpaceOnUse" x1="88" y1="80" x2="172" y2="212">
				<stop offset="0" class="s-o4"/><stop offset="1" class="s-o2"/>
			</linearGradient>
			<linearGradient id="mq-dg-shine" gradientUnits="userSpaceOnUse" x1="100" y1="0" x2="160" y2="0">
				<stop offset="0"    stop-color="#fff" stop-opacity="0"/>
				<stop offset="0.38" stop-color="#fff" stop-opacity="0.55"/>
				<stop offset="0.5"  stop-color="#fff" stop-opacity="0.95"/>
				<stop offset="0.62" stop-color="#fff" stop-opacity="0.55"/>
				<stop offset="1"    stop-color="#fff" stop-opacity="0"/>
			</linearGradient>
			<clipPath id="mq-dg-clip">
				<path d="M130,20 L216,146 L130,292 L44,146 Z"/>
			</clipPath>

			<?php // --- リング（グリーン → ブルー） --- ?>
			<linearGradient id="mq-ar-a" gradientUnits="userSpaceOnUse" x1="38" y1="40" x2="262" y2="250">
				<stop offset="0" class="s-g1"/><stop offset="0.5" class="s-g2"/><stop offset="1" class="s-g3"/>
			</linearGradient>
			<linearGradient id="mq-ar-b" gradientUnits="userSpaceOnUse" x1="240" y1="70" x2="60" y2="230">
				<stop offset="0" class="s-g3"/><stop offset="0.5" class="s-g2"/><stop offset="1" class="s-g4"/>
			</linearGradient>
			<linearGradient id="mq-ar-c" gradientUnits="userSpaceOnUse" x1="90" y1="220" x2="215" y2="85">
				<stop offset="0" class="s-g2"/><stop offset="0.5" class="s-g1"/><stop offset="1" class="s-g2"/>
			</linearGradient>

			<?php // --- 星（パープル → ピンク） --- ?>
			<linearGradient id="mq-st-a" gradientUnits="userSpaceOnUse" x1="-72" y1="-88" x2="66" y2="82">
				<stop offset="0" class="s-p1"/><stop offset="0.42" class="s-p2"/><stop offset="1" class="s-p3"/>
			</linearGradient>
			<radialGradient id="mq-st-b" gradientUnits="userSpaceOnUse" cx="0" cy="0" r="40">
				<stop offset="0" class="s-p4"/><stop offset="1" class="s-p3"/>
			</radialGradient>
			<linearGradient id="mq-st-c" gradientUnits="userSpaceOnUse" x1="-26" y1="-26" x2="26" y2="26">
				<stop offset="0" class="s-p2"/><stop offset="1" class="s-p1"/>
			</linearGradient>

			<?php
			// グレイン。乱流を黒のアルファに変換 → 図形の中だけに限定 → 乗算で重ねる。
			// 最終列の -0.38 が効き具合の調整点。これを引かないと乱流の平均値のぶんだけ
			// 全体が一様に暗くなり、ビビッドな色がくすむ。
			?>
			<filter id="mq-grain" x="-20%" y="-20%" width="140%" height="140%"
				color-interpolation-filters="sRGB">
				<feTurbulence type="fractalNoise" baseFrequency="0.75" numOctaves="3"
					stitchTiles="stitch" result="noise"/>
				<feColorMatrix in="noise" type="matrix" result="grain"
					values="0 0 0 0 0
					        0 0 0 0 0
					        0 0 0 0 0
					        0.32 0.32 0.32 0 -0.38"/>
				<feComposite in="grain" in2="SourceGraphic" operator="in" result="grainClipped"/>
				<feBlend in="grainClipped" in2="SourceGraphic" mode="multiply"/>
			</filter>

		</defs>
	</svg>

	<div class="marquee" style="--mq-speed: <?php echo esc_attr( $speed ); ?>">
		<div class="marquee-track">
			<?php for ( $pass = 0; $pass < 2; $pass++ ) : ?>
				<?php foreach ( $items as $item ) : ?>
					<div class="marquee-item">
						<?php get_template_part( 'template-parts/figure', $item['key'] ); ?>
						<p class="mq-label">
							<span class="mq-word"><?php echo esc_html( $item['word'] ); ?></span>
							<span class="mq-note"><?php echo esc_html( $item['note'] ); ?></span>
						</p>
					</div>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
	</div>
</section>
