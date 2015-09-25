<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rede_Cultura_Viva
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="entry-footer site-padding">
			<div id="footer-sidebar" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-footer' ); ?>
			</div><!-- #footer-sidebar -->
		</div>
		<div class="site-info">
			<div class="footer-logos" id="footer-brasil"><div id="wrapper-footer-brasil"><a href="http://www.acessoainformacao.gov.br/"><span class="logo-acesso-footer"></span></a><a href="http://www.brasil.gov.br/"><span class="logo-brasil-footer"></span></a></div></div>
		</div><!-- .site-info -->
		<div class="footer-proudly site-padding">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'rede-cultura-viva' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'rede-cultura-viva' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'rede-cultura-viva' ), 'rede-cultura-viva', '<a href="http://redelivre.org.br" rel="designer">#redelivre</a>' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
