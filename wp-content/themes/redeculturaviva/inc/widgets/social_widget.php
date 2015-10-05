<?php

class Social_Widget extends WP_Widget
{

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
				'Social_Widget', // Base ID
				__( 'Redes Sociais', 'rede-cultura-viva' ), // Name
				array( 'description' => __( 'Widget para Redes Sociais', 'rede-cultura-viva' ), ) // Args
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
			$cache = wp_cache_get( 'Social_Widget', 'widget' );
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
		
		$defaults = array(
			'title' => '',
			'diaspora' => '#',
			'cdbr' => '#',
			'facebook' => '#',
			'instagram' => '#',
			'youtube' => '#',
			'twitter' => '#',
			'googleplus' => '#',
		);
		
		$instance = array_merge($defaults, $instance);
		
		ob_start();
		
		$title = $instance['title'];
		
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		
		echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title']. '<span '.( array_key_exists('cssclasstitle', $instance) && !empty($instance['cssclasstitle']) ? 'class="'.$instance['cssclasstitle'].'"' : ''). '>' . $title . '</span>'. $args['after_title'];
		} ?>
		<ul class="social-list">
			<li><a class="social-diaspora icon2 icon2-diaspora-asterisk" title="<?php _e( 'Diáspora', 'rede-cultura-viva' ); ?>" href="<?php echo $instance['diaspora']; ?>" rel="nofollow" target="_blank"><span class="assistive-text"></span></a></li>
			<li><a class="social-cdbr icon2 icon2-cdbr" title="<?php _e( 'Cultura Digital BR', 'rede-cultura-viva' ); ?>" href="<?php echo $instance['cdbr']; ?>" rel="nofollow" target="_blank"><span class="assistive-text"></span></a></li>
			<li><a class="social-facebook icon-facebook-squared" title="<?php _e( 'Facebook', 'rede-cultura-viva' ); ?>" href="<?php echo $instance['facebook']; ?>" rel="nofollow" target="_blank"><span class="assistive-text"></span></a></li>
			<li><a class="social-instagram icon2 icon2-instagram" title="<?php _e( 'Instagram', 'rede-cultura-viva' ); ?>" href="<?php echo $instance['instagram']; ?>" rel="nofollow" target="_blank"><span class="assistive-text"></span></a></li>
			<li><a class="social-youtube icon2 icon2-youtube" title="<?php _e( 'Youtube', 'rede-cultura-viva' ); ?>" href="<?php echo $instance['youtube']; ?>" rel="nofollow" target="_blank"><span class="assistive-text"></span></a></li>
			<li><a class="social-twitter icon-twitter" title="<?php _e( 'Twitter', 'rede-cultura-viva' ); ?>" href="<?php echo $instance['twitter']; ?>" rel="nofollow" target="_blank"><span class="assistive-text"></span></a></li>
	    	<li><a class="social-googleplus icon-gplus" title="<?php _e( 'Google+', 'rede-cultura-viva' ); ?>" href="<?php echo $instance['googleplus']; ?>" rel="nofollow" target="_blank"><span class="assistive-text"></span></a></li>
		</ul>
		<?php echo $args['after_widget']; 
		
		if ( ! $this->is_preview() )
		{
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'Social_Widget', $cache, 'widget' );
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
	public function form( $instance )
	{
		$defaults = array(
				'title' => '',
				'diaspora' => '#',
				'cdbr' => '#',
				'facebook' => '#',
				'instagram' => '#',
				'youtube' => '#',
				'twitter' => '#',
				'googleplus' => '#',
		);
		
		$instance = array_merge($defaults, $instance);
		
		extract($instance); // TODO esc attr
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'diaspora' ); ?>"><?php _e( 'Diáspora:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'diaspora' ); ?>" name="<?php echo $this->get_field_name( 'diaspora' ); ?>" type="text" value="<?php echo $diaspora; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'cdbr' ); ?>"><?php _e( 'Cultura Digital BR:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cdbr' ); ?>" name="<?php echo $this->get_field_name( 'cdbr' ); ?>" type="text" value="<?php echo $cdbr; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo $facebook; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'Instagram:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" type="text" value="<?php echo $instagram; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo $youtube; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo $twitter; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'googleplus' ); ?>"><?php _e( 'Google+:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'googleplus' ); ?>" name="<?php echo $this->get_field_name( 'googleplus' ); ?>" type="text" value="<?php echo $googleplus; ?>" /></p>
		<?php
		
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance )
	{
		// processes widget options to be saved
		$instance = $old_instance;
		foreach ($new_instance as $key => $value)
		{
			$instance[$key] = esc_url_raw( $new_instance[$key] );
		}
		
		$instance['title'] = strip_tags($new_instance['title']);
		
		return $instance;
	}
}