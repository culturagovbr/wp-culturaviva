<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rede_Cultura_Viva
 */

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

<article id="post-<?php the_ID(); ?>" <?php post_class( array('home-post') ); echo empty($url) ? '' : 'style="background-image: url('.$url.')"' ; ?> >
	<div class="entry-content">
		<span class="home-post-middle">
			<?php the_title(); ?>
		</span>
	</div><!-- .entry-content -->

</article><!-- #post-## -->

