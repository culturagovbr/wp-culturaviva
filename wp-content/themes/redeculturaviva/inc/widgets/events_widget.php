<?php

class Mapas_Culturais_Events_Widget extends WP_Widget
{

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
				'Mapas_Culturais_Events_Widget', // Base ID
				__( 'Mapas Culturais Events Widget', 'rede-cultura-viva' ), // Name
				array( 'description' => __( 'Mapas Culturais Events Widget', 'rede-cultura-viva' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance )
	{
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'Mapas_Culturais_Events_Widget', 'widget' );
		}
		
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}
		
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		
		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}
		
		ob_start();
		
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Mapas Culturais Events List', 'rede-cultura-viva' );
		
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		
		//if ($r->have_posts())
		{
		?>
			<?php echo $args['before_widget']; ?>
			<?php if ( $title ) {
				echo $args['before_title']. '<span '.( array_key_exists('cssclasstitle', $instance) && !empty($instance['cssclasstitle']) ? 'class="'.$instance['cssclasstitle'].'"' : ''). '>' . $title . '</span>'. $args['after_title'];
			} ?>
			<ul>
			<?php /*while ( $r->have_posts() ) : $r->the_post(); ?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<?php if ( $show_date ) : ?>
					<span class="post-date"><?php echo get_the_date(); ?></span>
				<?php endif; ?>
				</li>
			<?php endwhile; */ for ( $i = 1; $i < 4; $i++) :  ?>
				<li>
					<div class="col1">
						<span class="post-date"><?php echo (date('d') + $i)."/".date('m'); ?></span>
					</div>
					<div class="col2">
						<a href="#"><?php echo "Evento ".$i; ?></a>
					</div>
				</li>
				<?php endfor; ?>
			</ul>
			<?php echo $args['after_widget']; ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		}
		
		if ( ! $this->is_preview() )
		{
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'Mapas_Culturais_Events_Widget', $cache, 'widget' );
		}
		else
		{
			ob_end_flush();
		}
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$cssclasstitle  = isset( $instance['cssclasstitle'] ) ? esc_attr( $instance['cssclasstitle'] ) : '';

		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'cssclasstitle' ); ?>"><?php _e( 'Title CSS Class:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cssclasstitle' ); ?>" name="<?php echo $this->get_field_name( 'cssclasstitle' ); ?>" type="text" value="<?php echo $cssclasstitle; ?>" /></p>
		<?php
		
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['cssclasstitle'] = strip_tags($new_instance['cssclasstitle']);
		
		return $instance;
	}
}