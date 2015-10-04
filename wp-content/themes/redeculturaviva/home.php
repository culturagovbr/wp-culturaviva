<?php
/**
 * The template for displaying Home.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Rede_Cultura_Viva
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main"><?php
			if( get_theme_mod('redeculturaviva_display_slider') == 1 )
			{  
				$feature = new WP_Query( array( 'posts_per_page' => 5, 'ignore_sticky_posts' => 1, 'meta_key' => '_home', 'meta_value' => 1 ) );
				if ( $feature->have_posts() ) : ?>
	
					<div class="cycle-slideshow highlights" >
						<ul class="slides">
				        	<div class="cycle-pager"></div>
				        	<div class="cycle-prev"><div class="icon2-left-open"></div></div>
	   					 	<div class="cycle-next"><div class="icon2-right-open"></div></div>
					        <?php while ( $feature->have_posts() ) :
					        	$feature->the_post();

					        	$thumbnail2 = get_post_meta(get_the_ID(), '_thumbnail2', true);
					        	if(strlen($thumbnail2) > 1)
					        	{
					        		$url = $thumbnail2;
					        	}
					        	elseif ( has_post_thumbnail() )
					        	{
			    					$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			    					$thumb = wp_get_attachment_image_src($post_thumbnail_id, 'slider',false);
			    					$url = ''; //TODO default image if apply 
			    					if(is_array($thumb))
			    					{
			    						$url = $thumb[0];
					    			}
					        	}
					        	$title = the_title($before = '', $after = '', false);
					        	?>
						        <li class="cycles-slide" >
							        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="background-image: url(<?php echo $url; ?>)" >
							        	<div class="media slide cf">
							        		<div class="bd">
							        			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php echo strlen($title) > 80 ? (substr($title, 0, 80).'...') : $title ; ?></a></h2>
							        		</div>
							        	</div><!-- /slide -->
							        </article><!-- /article -->
						        </li>
				        	<?php endwhile; ?>
					</ul><!-- .swiper-wrapper -->
				</div><!-- .swiper-container -->
				<?php
				wp_reset_postdata();
				
				else : ?>
					<?php if ( current_user_can( 'edit_theme_options' ) ): ?>
						<div class="empty-feature">
			                <p><?php printf( __( 'To display your featured posts here go to the <a href="%s">Post Edit Page</a> and check the "Feature" box. You can select how many posts you want, but use it wisely.', 'redeculturaviva' ), admin_url('edit.php') ); ?></p>
						</div>
					<?php
					endif;
				endif; // have_posts()
			}
			?>
			<div class="clearfix"> </div>

			<div id="home-sidebar" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-home' ); ?>
			</div><!-- #home-sidebar -->

			<?php
			if(have_posts())
			{?>
				<div class="home-posts-list"><?php
				while ( have_posts() )
				{
					the_post();
					get_template_part( 'template-parts/content', 'home' );
				}?>
				</div><?php
			}?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
