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

	<?php
		global $RedeCulturaViva;
		$RedeCulturaViva->get_footer('', false);
	?>
	
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
