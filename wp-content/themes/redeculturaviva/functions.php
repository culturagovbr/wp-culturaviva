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
	
	function get_fonts()
	{?>
			@font-face {
			    font-family: DroidSans;
			    src: url(<?php echo get_template_directory_uri(). '/fonts/droid-sans/DroidSans.ttf'; ?>);
			}
			
			@font-face {
			    font-family: DroidSans-Bold;
			    src: url(<?php echo get_template_directory_uri(). '/fonts/droid-sans/DroidSans-Bold.ttf'; ?>);
			}<?php
	}
	
	function get_footer($param = '', $die = true)
	{?>
		<style>
			<?php $this->get_fonts(); ?>
			
			*, *::before, *::after {
			    box-sizing: inherit;
			}
			
			body,
			button,
			input,
			select,
			textarea {
				color: #404040;
				font-family: DroidSans;
				font-size: 16px;
				font-size: 1rem;
				line-height: 1.5;
				margin: 0;
			}
			
			.site-info {
				min-height: 50px;
			    padding: 20px 0;
			    width: 100%;
			    background: #f7931e none repeat scroll 0 0;
			    color: #fff;
			}
			
			.site-padding {
			    margin-left: auto;
			    margin-right: auto;
			    width: 100%;
			    max-width: 960px;
			    position: relative;
			    overflow: hidden;
			}
			
			.footer-proudly, .footer-proudly a {
				text-align: center;
				color: #ffffff;
				display: none;
			}
			#footer-sidebar {
				display: block;
				float: left;
				background-color: #078979;
				position: relative;
				overflow: hidden;
				color: #fff;	
				padding: 1em;
			}
			
			#footer-sidebar .widget {
			    float: left;
			    height: 160px;
			    text-align: center;
			    font-size: 0.8em;
			    margin-top: 1em;
			}
			
			#footer-sidebar aside:nth-child(1) {
				width: 46%;
			}
			
			#footer-sidebar aside:nth-child(2),
			#footer-sidebar aside:nth-child(3) {
				width: 13%;
			}
			#footer-sidebar aside:nth-child(4) {
				width: 28%;
			}
			
			#footer-sidebar .widget-title {
				color: #fbed1d;
				font-size: 1.5em;
				text-align: left;
				margin-top: 0;
			}
			
			#footer-sidebar ul {
				padding: 0;
				margin: 0;
			}
			
			#footer-sidebar li {
				list-style: none;
				text-align: left;
			}
			
			#footer-sidebar a {
				text-decoration: none;
				color: #fff;
			}
			
			.site-footer img {
			    height: auto;
			}
		</style>
		
		<div id="barra-identidade" style="display: none;">
			<div id="barra-brasil"><div id="wrapper-barra-brasil"><div class="brasil-flag"><a class="link-barra" href="http://brasil.gov.br">Brasil</a></div><span class="acesso-info"><a class="link-barra" href="http://brasil.gov.br/barra#acesso-informacao">Acesso à informação</a></span><nav><ul class="list"><li><a id="menu-icon" href="#"></a></li><li class="list-item first"><a class="link-barra" href="http://brasil.gov.br/barra#participe">Participe</a></li><li class="list-item"><a id="barra-brasil-orgao" class="link-barra" href="http://www.servicos.gov.br/?pk_campaign=barrabrasil">Serviços</a></li><li class="list-item"><a class="link-barra" href="http://www.planalto.gov.br/legislacao">Legislação</a></li><li class="list-item last last-item"><a class="link-barra" href="http://brasil.gov.br/barra#orgaos-atuacao-canais">Canais</a></li></ul></nav></div></div>
			<script async="" defer="" type="text/javascript" src="http://barra.brasil.gov.br/barra.js"></script>
		</div>
		
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="entry-footer site-padding">
				<div id="footer-sidebar" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'sidebar-footer' ); ?>
				</div><!-- #footer-sidebar -->
			</div>
			<div class="site-info">
				<div class="footer-logos" id="footer-brasil">
					<div id="wrapper-footer-brasil">
						<a href="http://www.acessoainformacao.gov.br/">
							<span class="logo-acesso-footer">
							</span>
						</a>
						<span class="secretaria-footer">
							Secretaria-Geral da Presidência da República
						</span>
						<a href="http://www.brasil.gov.br/">
							<span class="logo-brasil-footer">
							</span>
						</a>
					</div>
				</div>
			</div><!-- .site-info -->
			<div class="footer-proudly site-padding">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'rede-cultura-viva' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'rede-cultura-viva' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'rede-cultura-viva' ), 'rede-cultura-viva', '<a href="http://redelivre.org.br" rel="designer">#redelivre</a>' ); ?>
			</div>
		</footer><!-- #colophon -->
		
		<?php 
		if($die) die();
	}
	
	function get_header($param = '', $die = true)
	{?>
		<style>
			/*--------------------------------------------------------------
			## Header
			--------------------------------------------------------------*/
			
			<?php $this->get_fonts(); ?>
			
			*, *::before, *::after {
			    box-sizing: inherit;
			}
			
			body,
			button,
			input,
			select,
			textarea {
				color: #404040;
				font-family: DroidSans;
				font-size: 16px;
				font-size: 1rem;
				line-height: 1.5;
				margin: 0;
			}
			
			.site-header {
				background-color: #ffffff;
			}
			
			.site-logo {
				width: 110px;
				height: 50px;
			}
			
			.site-logo, .site-navigation {
				display: block;
				float: left;
			}
			
			#wrapper-barra-brasil > nav {
			    position: relative;
			    z-index: 1000;
			}
			
			.site-padding {
			    margin-left: auto;
			    margin-right: auto;
			    width: 100%;
			    max-width: 960px;
			    position: relative;
			    overflow: hidden;
			}
			/*--------------------------------------------------------------
			## Menus
			--------------------------------------------------------------*/
			.entry-navigation {
				background-color: #ffffff; 
			}
			
			.main-navigation {
				display: block;
				float: right;
				max-width: 850px
			}
			
			header .main-navigation a {
			    color: #000;
			}
			
			header .main-navigation a:hover {
			    color: #c0c0c0;
			}
			
			.main-navigation ul {
				display: none;
				list-style: none;
				margin: 0;
				float: right;
			}
			
			.main-navigation li {
			    float: left;
			    padding: 0.75em 1em;
			    position: relative;
			    text-align: center;
			    border-left: 1px solid #dfdfdf;
			    border-right: 1px solid #dfdfdf;
			}
			
			.main-navigation a {
				display: block;
				text-decoration: none;
			}
			
			.main-navigation ul ul {
				box-shadow: 0 3px 3px rgba(0, 0, 0, 0.2);
				float: left;
				position: absolute;
				top: 1.5em;
				left: -999em;
				z-index: 99999;
			}
			
			.main-navigation ul ul ul {
				left: -999em;
				top: 0;
			}
			
			.main-navigation ul ul a {
				width: 200px;
			}
			
			.main-navigation ul ul li {
			
			}
			
			.main-navigation li:hover > a,
			.main-navigation li.focus > a {
			}
			
			.main-navigation ul ul :hover > a,
			.main-navigation ul ul .focus > a {
			}
			
			.main-navigation ul ul a:hover,
			.main-navigation ul ul a.focus {
			}
			
			.main-navigation ul li:hover > ul,
			.main-navigation ul li.focus > ul {
				left: auto;
			}
			
			.main-navigation ul ul li:hover > ul,
			.main-navigation ul ul li.focus > ul {
				left: 100%;
			}
			
			.main-navigation .current_page_item > a,
			.main-navigation .current-menu-item > a,
			.main-navigation .current_page_ancestor > a {
			}
			
			/* Small menu. */
			.menu-toggle,
			.main-navigation.toggled ul {
				display: block;
			}
			
			@media screen and (min-width: 960px) {
				.menu-toggle {
					display: none;
				}
				.main-navigation ul {
					display: block;
				}
			}
			
			.site-main .comment-navigation,
			.site-main .posts-navigation,
			.site-main .post-navigation {
				margin: 0 0 1.5em;
				overflow: hidden;
			}
			
			.comment-navigation .nav-previous,
			.posts-navigation .nav-previous,
			.post-navigation .nav-previous {
				float: left;
				width: 50%;
			}
			
			.comment-navigation .nav-next,
			.posts-navigation .nav-next,
			.post-navigation .nav-next {
				float: right;
				text-align: right;
				width: 50%;
			}
		</style>
		<script type="text/javascript">
		<!--
			var headID = document.getElementsByTagName("head")[0];         
			var cssNode = document.createElement('link');
			cssNode.type = 'text/css';
			cssNode.rel = 'stylesheet';
			cssNode.href = '<?php echo get_template_directory_uri().'/css/icons.css'; ?>';
			cssNode.media = 'screen';
			headID.appendChild(cssNode);
		//-->
		</script>
		<header id="masthead" class="site-header" role="banner">
			<div id="barra-identidade">
				<div id="barra-brasil"><div id="wrapper-barra-brasil"><div class="brasil-flag"><a class="link-barra" href="http://brasil.gov.br">Brasil</a></div><span class="acesso-info"><a class="link-barra" href="http://brasil.gov.br/barra#acesso-informacao">Acesso à informação</a></span><nav><ul class="list"><li><a id="menu-icon" href="#"></a></li><li class="list-item first"><a class="link-barra" href="http://brasil.gov.br/barra#participe">Participe</a></li><li class="list-item"><a id="barra-brasil-orgao" class="link-barra" href="http://www.servicos.gov.br/?pk_campaign=barrabrasil">Serviços</a></li><li class="list-item"><a class="link-barra" href="http://www.planalto.gov.br/legislacao">Legislação</a></li><li class="list-item last last-item"><a class="link-barra" href="http://brasil.gov.br/barra#orgaos-atuacao-canais">Canais</a></li></ul></nav></div></div>
				<script async="" defer="" type="text/javascript" src="http://barra.brasil.gov.br/barra.js"></script>
			</div>
			<?php 
					// Check if there's a custom logo
					$logo = get_theme_mod( 'rede-cultura-viva_logo' );
					$logo_uri = get_template_directory_uri() . '/images/logo-undefined.png';
					if( $logo )
					{
						$logo_uri =  $logo; 
					}
					
					?>
			<div class="entry-navigation site-padding">
				<div class="site-logo">
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class='site-logo-link'>
						<img class="site-logo" src="<?php echo $logo_uri; ?>" alt="Logo <?php bloginfo ( 'name' ); ?>" />
					</a>
				</div>
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'rede-cultura-viva' ); ?></button>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
				</nav><!-- #site-navigation -->
			</div>
		</header><!-- #masthead -->
		<?php
		if($die) die();
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