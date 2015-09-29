<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rede_Cultura_Viva
 */

?>

<?php
$thumbnail_id = get_post_thumbnail_id(); 
?>

<article id="post-<?php the_ID(); ?>" <?php intval($thumbnail_id) > 0 ? post_class('has-thumbnail') : post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header --><?php
	if(intval($thumbnail_id) > 0)
	{?>
		<div class="post-thumbnail-box">
			<div class="post-thumbnail" style="background-image: url(<?php echo wp_get_attachment_url( $thumbnail_id ); ?>);">
			</div>
		</div><?php
	}?>

	<div class="entry-content">
		<div class="entry-meta">
			<?php rede_cultura_viva_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'rede-cultura-viva' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="entry-share cf">
	        <ul class="share-social cf">
	        	<?php  $post_permalink = get_permalink(); ?>
		    	<li><a class="share-twitter icon-twitter" title="<?php _e( 'Share on Twitter', 'rede-cultura-viva' ); ?>" href="http://twitter.com/intent/tweet?original_referer=<?php echo $post_permalink; ?>&text=<?php echo $post->post_title; ?>&url=<?php echo $post_permalink; ?>" rel="nofollow" target="_blank"><span class="assistive-text"><?php _e( 'Share on Twitter', 'rede-cultura-viva' ); ?></span></a></li>
		    	<li><a class="share-facebook icon-facebook-squared" title="<?php _e( 'Share on Facebook', 'rede-cultura-viva' ); ?>" href="https://www.facebook.com/sharer.php?u=<?php echo $post_permalink; ?>" rel="nofollow" target="_blank"><span class="assistive-text"><?php _e( 'Share on Facebook', 'rede-cultura-viva' ); ?></span></a></li>
		    	<li><a class="share-googleplus icon-gplus" title="<?php _e( 'Share on Google+', 'rede-cultura-viva' ); ?>" href="https://plus.google.com/share?url=<?php echo $post_permalink; ?>" rel="nofollow" target="_blank"><span class="assistive-text"><?php _e( 'Share on Google+', 'rede-cultura-viva' ); ?></span></a></li>
			</ul>
			<div class="share-shortlink">
				<span aria-hidden="true" class="icon-publish"></span>
	        	<input type="text" value="<?php if ( $shortlink = wp_get_shortlink( $post->ID ) ) echo $shortlink; else the_permalink(); ?>" onclick="this.focus(); this.select();" readonly="readonly" />
			</div>
	    </div><!-- .entry-share -->
		
		<?php rede_cultura_viva_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

