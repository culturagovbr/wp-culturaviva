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

$url = ''; //TODO default image if apply
if ( has_post_thumbnail() )
{
	$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
	$thumb = wp_get_attachment_image_src($post_thumbnail_id, 'full',false);

	if(is_array($thumb))
	{
		$url = $thumb[0];
	}
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php
	if(has_post_thumbnail())
	{?>
		<div class="post-thumbnail-box">
			<img class="post-thumbnail" src="<?php echo $url; ?>"/>
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

