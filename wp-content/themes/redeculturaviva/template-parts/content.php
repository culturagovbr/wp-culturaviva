<?php
/**
 * Template part for displaying posts.
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

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php rede_cultura_viva_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="archive-col1">
		<?php
		if(has_post_thumbnail())
		{?>
			<div class="post-thumbnail-box">
				<img class="post-thumbnail" src="<?php echo $url; ?>"/>
			</div><?php
		}?>
	</div>
	<div class="archive-col2">
		<div class="entry-content">
			<?php the_excerpt(); ?>
            <a href="<?php the_permalink(); ?>"><?php print( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'rede-cultura-viva' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );
			?></a>
	
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'rede-cultura-viva' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div>

	<footer class="entry-footer">
		<?php rede_cultura_viva_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
