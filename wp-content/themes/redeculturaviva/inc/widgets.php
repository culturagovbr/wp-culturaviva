<?php

class RedeCulturaVivaWidgets
{
	function __construct()
	{
		add_filter( 'widget_text', 'shortcode_unautop');
		add_filter( 'widget_text', 'do_shortcode', 11);
		add_filter( 'widget_text', array( $this, 'autoembed'), 12 );
		add_action( 'widgets_init', array($this, 'widgets_init' ));
	}
	
	function widgets_init()
	{
		register_widget( 'Mapas_Culturais_Events_Widget' );
		register_widget( 'Social_Widget' );
	}
	
	function autoembed($content)
	{
		global $wp_embed;
		return $wp_embed->autoembed($content);
	}
	
}

$RedeCulturaVivaWidgets = new RedeCulturaVivaWidgets();

/**
 * Load Mapas_Culturais_Events_Widget file.
 */
require dirname(__FILE__) . '/widgets/events_widget.php';
/**
 * Load Social_Widget file.
 */
require dirname(__FILE__) . '/widgets/social_widget.php';