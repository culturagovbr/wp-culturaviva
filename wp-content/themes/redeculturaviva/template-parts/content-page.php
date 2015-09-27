<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rede_Cultura_Viva
 */

?>

<?php
$thumbnail_id = get_post_thumbnail_id(); 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php
	if(intval($thumbnail_id) > 0)
	{?>
		<div class="post-thumbnail-box">
			<div class="post-thumbnail" style="background-image: url(<?php echo wp_get_attachment_url( $thumbnail_id ); ?>);">
			</div>
		</div><?php
	}?>
	
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'rede-cultura-viva' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'rede-cultura-viva' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

