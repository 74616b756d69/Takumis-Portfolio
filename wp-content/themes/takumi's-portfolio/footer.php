<?php
/**
 * フッターテンプレート
 */
?>
<!-- Footer -->
<footer class="site-footer">
	<?php takumi_shape_field( 'footer' ); ?>
	<!-- フッター上辺のラインに足を揃えて載る2体 -->
	<span class="footer-mascot footer-mascot--cat"><?php takumi_mascot_cat(); ?></span>
	<span class="footer-mascot footer-mascot--robot"><?php takumi_mascot_robot(); ?></span>
	<p class="site-footer__logo"><?php bloginfo( 'name' ); ?></p>
	<nav class="site-footer__nav">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Top</a>
		<a href="<?php echo esc_url( takumi_page_url( 'about' ) ); ?>">About</a>
		<a href="<?php echo esc_url( takumi_page_url( 'work' ) ); ?>">Work</a>
	</nav>
	<p class="site-footer__copy">© <?php echo esc_html( get_theme_mod( 'takumi_name_ja', 'Takumi Akahori' ) ); ?></p>
</footer>

<button class="pagetop" aria-label="ページ上部へ戻る">↑</button>

<?php wp_footer(); ?>
</body>
</html>
