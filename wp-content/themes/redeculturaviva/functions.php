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
		add_action( 'pre_get_posts', array($this, 'pre_get_posts' ));
		add_action( 'excerpt_length', array($this, 'excerpt_length' ));
		
		add_filter('embed_oembed_html', array($this, 'add_video_embed_div'), 10, 3);
		
		add_action( 'wp_ajax_nopriv_get_footer', array($this, 'get_footer'));
		add_action( 'wp_ajax_get_footer', array($this, 'get_footer'));
		add_action( 'wp_ajax_nopriv_get_header', array($this, 'get_header'));
		add_action( 'wp_ajax_get_header', array($this, 'get_header'));
		
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
			'name'          => esc_html__( 'Home Sidebar', 'rede-cultura-viva' ),
			'id'            => 'sidebar-home',
			'description'   => 'Sidebar after highlights',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Sidebar', 'rede-cultura-viva' ),
			'id'            => 'sidebar-footer',
			'description'   => 'Sidebar on footer',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Post Sidebar', 'rede-cultura-viva' ),
			'id'            => 'sidebar-post',
			'description'   => 'Post Sidebar',
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
		wp_enqueue_style( 'rede-cultura-viva-icons2', get_stylesheet_directory_uri()."/fonts/redeculturaviva2/css/redeculturaviva2.css" );
		
		wp_enqueue_style( 'rede-cultura-viva-slideshow', get_stylesheet_directory_uri()."/css/slideshow.css" );
	
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
	
	function pre_get_posts( $query ) {
		if ( $query->is_home() && $query->is_main_query() )
		{
			$query->set( 'posts_per_page', 5 );
			//'ignore_sticky_posts' => 1, 'meta_key' => '_home', 'meta_value' => 1
			
			$meta_query = $query->get('meta_query');
			
			$meta_query[] = array(
				'key'=>'_home',
				'compare'=>'NOT EXISTS',
			);
			$query->set('meta_query',$meta_query);
		}
	}
	
	function excerpt_length($len)
	{
		if ( is_home() && is_main_query() )
		{
			return 10;
		}
		return $len;
	}

	function add_video_embed_div($html, $url, $attr)
	{
		if (strpos($html, "src=" ) !== false) {
			return '<div class="center-embed">'.$html.'</div>';
		} else {
			return $html;
		}
	}
	
	function get_footer()
	{?>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="profile" href="http://gmpg.org/xfn/11">
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<?php wp_head(); ?>
		</head>
		
		<div id="barra-identidade" style="display: none;">
			<div id="barra-brasil"><div id="wrapper-barra-brasil"><div class="brasil-flag"><a class="link-barra" href="http://brasil.gov.br">Brasil</a></div><span class="acesso-info"><a class="link-barra" href="http://brasil.gov.br/barra#acesso-informacao">Acesso à informação</a></span><nav><ul class="list"><li><a id="menu-icon" href="#"></a></li><li class="list-item first"><a class="link-barra" href="http://brasil.gov.br/barra#participe">Participe</a></li><li class="list-item"><a id="barra-brasil-orgao" class="link-barra" href="http://www.servicos.gov.br/?pk_campaign=barrabrasil">Serviços</a></li><li class="list-item"><a class="link-barra" href="http://www.planalto.gov.br/legislacao">Legislação</a></li><li class="list-item last last-item"><a class="link-barra" href="http://brasil.gov.br/barra#orgaos-atuacao-canais">Canais</a></li></ul></nav></div></div>
			<script async="" defer="" type="text/javascript" src="http://barra.brasil.gov.br/barra.js"></script>
		</div>
		
		<?php 
		get_footer();
		die;
	}
	
	function get_header()
	{
		get_header();
		die;
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

/**
 * Load Widgets file.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Load Comment Template file.
 */
require get_template_directory() . '/template-parts/comment-template.php';

/**
 * Load Oportunidade file.
 */
require get_template_directory() . '/inc/oportunidades/oportunidades.php';