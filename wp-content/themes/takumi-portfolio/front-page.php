<?php
/**
 * トップページ。
 *
 * セクションを順に差し込んでいく。
 *
 * @package takumi-portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/marquee' );

get_template_part(
	'template-parts/work-records',
	null,
	array(
		'heading' => 'つくったものを、<br>記録として残す。',
		'lede'    => 'チーム制作から受託、個人開発まで。要件を決めるところから公開後の運用まで、そのとき必要だった役割を担当してきました。',
		'limit'   => 6,
	)
);

get_footer();
