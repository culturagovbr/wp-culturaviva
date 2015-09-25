<?php
/**
 * Rede Cultura Viva functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Rede_Cultura_Viva
 */

class RedeCulturaViva
{

	public function __construct()
	{
		add_action( 'after_setup_theme', array($this, 'setup' ));
		add_action( 'after_setup_theme', array($this, 'content_width'), 0 );
		add_action( 'widgets_init', array($this, 'widgets_init' ));
		add_action( 'wp_enqueue_scripts', array($this, 'scripts' ));
	}
	
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function setup() {
		
		require( get_template_directory() . '/inc/hacklab_post2home/hacklab_post2home.php' );
		
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Rede Cultura Viva, use a find and replace
		 * to change 'rede-cultura-viva' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'rede-cultura-viva', get_template_directory() . '/languages' );
	
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
	
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
	
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
	
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'rede-cultura-viva' ),
		) );
	
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
	
		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );
	
		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'rede_cultura_viva_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}
	
	
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'rede_cultura_viva_content_width', 640 );
	}
	
	
	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	function widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'rede-cultura-viva' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
	
	/**
	 * Enqueue scripts and styles.
	 */
	function scripts() {
		wp_enqueue_style( 'rede-cultura-viva-style', get_stylesheet_uri() );
		
		wp_enqueue_style( 'rede-cultura-viva-icons', get_stylesheet_directory_uri()."/css/icons.css" );
	
		wp_enqueue_script( 'rede-cultura-viva-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	
		wp_enqueue_script( 'rede-cultura-viva-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		wp_enqueue_script('jquery-cycle2', get_template_directory_uri() . '/js/jquery.cycle2.min.js', array('jquery'));
		wp_enqueue_script('jquery-cycle2-carousel', get_template_directory_uri() . '/js/jquery.cycle2.carousel.min.js', array('jquery-cycle2'));
		wp_enqueue_script('jquery-cycle2-swipe', get_template_directory_uri() . '/js/jquery.cycle2.swipe.min.js', array('jquery-cycle2'));
		wp_enqueue_script('jquery-cycle2-center', get_template_directory_uri() . '/js/jquery.cycle2.center.min.js', array('jquery-cycle2'));
		wp_enqueue_script('jquery-slider-scroller', get_template_directory_uri() . '/js/jquery.slider.scroller.js', array('jquery-cycle2'));
		
	}

}

global $RedeCulturaViva;
$RedeCulturaViva = new RedeCulturaViva(); 
	
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';