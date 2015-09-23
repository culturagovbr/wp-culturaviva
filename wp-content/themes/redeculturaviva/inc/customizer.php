<?php
/**
 * Rede Cultura Viva Theme Customizer.
 *
 * @package Rede_Cultura_Viva
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function rede_cultura_viva_customize_register( $wp_customize ) {
	
	/**
	 * Customize Image Reloaded Class
	 *
	 * Extend WP_Customize_Image_Control allowing access to uploads made within
	 * the same context
	 *
	 */
	class My_Customize_Image_Reloaded_Control extends WP_Customize_Image_Control {
		/**
		 * Constructor.
		 *
		 * @since 3.4.0
		 * @uses WP_Customize_Image_Control::__construct()
		 *
		 * @param WP_Customize_Manager $manager
		 */
		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );
		}
	
		/**
		 * Search for images within the defined context
		 * If there's no context, it'll bring all images from the library
		 *
		 */
		public function tab_uploaded() {
			$my_context_uploads = get_posts( array(
					'post_type'  => 'attachment',
					'meta_key'   => '_wp_attachment_context',
					'meta_value' => $this->context,
					'orderby'    => 'post_date',
					'nopaging'   => true,
			) );
	
			?>
	            
			<div class="uploaded-target"></div>
	            
			<?php
				if ( empty( $my_context_uploads ) )
	                return;
	            
	            foreach ( (array) $my_context_uploads as $my_context_upload ) {
	                $this->print_tab_image( esc_url_raw( $my_context_upload->guid ) );
	    	}
		}
	}
	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	// Branding section
	$wp_customize->add_section( 'redeculturaviva_branding', array(
			'title'    => __( 'Branding', 'redeculturaviva' ),
			'priority' => 30,
	) );
	
	// Branding section: logo uploader
	$wp_customize->add_setting( 'redeculturaviva_logo', array(
			'capability'  => 'edit_theme_options',
			'sanitize_callback' => 'redeculturaviva_get_customizer_logo_size',
			//'transport'         => 'postMessage'
	) );
	
	$wp_customize->add_control( new My_Customize_Image_Reloaded_Control( $wp_customize, 'redeculturaviva_logo', array(
			'label'     => __( 'Logo', 'redeculturaviva' ),
			'section'   => 'redeculturaviva_branding',
			'settings'  => 'redeculturaviva_logo',
			'context'   => 'redeculturaviva-custom-logo'
	) ) );
	
	// Slider
	$wp_customize->add_section( 'redeculturaviva_slider', array(
			'title'    => __( 'Slider', 'redeculturaviva' ),
			'priority' => 30,
	) );
	$wp_customize->add_setting( 'redeculturaviva_display_slider', array(
			'capability' => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'redeculturaviva_display_slider', array(
			'label'    => __( 'Exibe o slider na página principal', 'redeculturaviva' ),
			'section'  => 'redeculturaviva_slider',
			'type'     => 'checkbox',
			'settings' => 'redeculturaviva_display_slider'
	) );
	
}
add_action( 'customize_register', 'rede_cultura_viva_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function rede_cultura_viva_customize_preview_js() {
	wp_enqueue_script( 'rede_cultura_viva_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'rede_cultura_viva_customize_preview_js' );
