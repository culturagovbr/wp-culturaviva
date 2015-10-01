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
		
		add_filter( 'embed_oembed_html', array($this, 'add_video_embed_div'), 10, 3);
		
		add_action( 'wp_ajax_nopriv_get_footer', array($this, 'get_footer'));
		add_action( 'wp_ajax_get_footer', array($this, 'get_footer'));
		add_action( 'wp_ajax_nopriv_get_header', array($this, 'get_header'));
		add_action( 'wp_ajax_get_header', array($this, 'get_header'));
		add_action( 'init', array($this, 'custom_rewrite_rules'));
		add_action( 'wp_loaded', array($this, 'check_rewrite' ));
		add_action( 'add_meta_boxes', array($this, 'custom_metas') );
		add_action( 'save_post', array($this, 'save_post_meta') );
		
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
			
			<?php include get_template_directory(). '/css/footer.css';?>
			
		</style>
		<div class="site-footer-html">
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
		</div><?php
		if($die) die();
	}
	
	function get_header($param = '', $die = true)
	{?>
		<style>
			/*--------------------------------------------------------------
			## Header
			--------------------------------------------------------------*/
			
			<?php $this->get_fonts(); ?>
			
			<?php include get_template_directory(). '/css/header.css';?>
			
		</style>
		<script type="text/javascript">
		<!--
			var headID = document.getElementsByTagName("head")[0];         
			var cssNode = document.createElement('link');
			cssNode.type = 'text/css';
			cssNode.rel = 'stylesheet';
			cssNode.href = '<?php echo get_template_directory_uri().(is_admin() ? '/css/icons-prefix.css' : '/css/icons.css'); ?>';
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
		</header><!-- #masthead --><?php
		if($die) die();
	}
	
	function custom_rewrite_rules()
	{
	
		add_rewrite_rule('oportunidades(.*)', 'index.php?post_type=oportunidade$matches[1]', 'top');
		add_rewrite_rule('noticias(.*)', 'index.php?category_name=noticias$matches[1]', 'top');
		add_rewrite_rule('videos(.*)', 'index.php?category_name=videos$matches[1]', 'top');
		
	}
	
	function check_rewrite()
	{
		$rules = get_option( 'rewrite_rules' );
		$found = false;
		if(is_array($rules))
		{
			foreach ($rules as $rule)
			{
				if(strpos($rule, 'videos') !== false)
				{
					$found = true;
					break;
				}
			}
			if ( ! $found ) {
				global $wp_rewrite; $wp_rewrite->flush_rules();
			}
		}
	}
	
	public static function has_sidebar($post_id = null)
	{
		if(is_null($post_id)) $post_id = get_the_ID();
		
		if(!is_int($post_id) || $post_id < 1 ) return true;
		
		return ! (get_post_meta($post_id, '_hide-sidebar', true) == 'Y');
	}
	
	function custom_metas()
	{
		add_meta_box("sidebar_meta", __("Post Layout", 'rede-cultura-viva'), array($this, 'sidebar_meta'), 'post', 'side', 'default');
	}
	
	function sidebar_meta()
	{
		
		wp_nonce_field( 'redeculturaviva_meta_inner_custom_box', 'redeculturaviva_meta_inner_custom_box_nonce' );
		
		$id = 'hide-sidebar';
		$value = "Y";
		$label_item = __("Hide Sidebar", 'rede-cultura-viva');
		$post_id = get_the_ID();
		$i = 1;
		$dado = get_post_meta($post_id, '_'.$id, true);
		
		?>
		<div class="redeculturaviva-item redeculturaviva-item-checkbox <?php echo $id; ?>">
			<div class="redeculturaviva-item-input-checkbox-block"><?php
				if(array_key_exists($id, $_REQUEST))
				{
					if(is_string($_REQUEST[$id])) 
					{
						$dado = $_REQUEST[$id];
					}
				}
				echo '<input id="'.("$id-option-$i").'" type="checkbox" name="'.$id.'" value="'.$value.'" '.($value == $dado ? 'checked="checked"': '').' ><label for="'.("$id-option-$i").'" class="redeculturaviva-item-input-checkbox" >'.$label_item.'</label>';?>
			</div>
		</div><?php
	}
	
	public function save_post_meta( $post_id )
	{
		/*
		 * We need to verify this came from the our screen and with proper authorization,
			* because save_post can be triggered at other times.
			*/
	
		// Check if our nonce is set.
		if ( ! isset( $_POST['redeculturaviva_meta_inner_custom_box_nonce'] ) )
		{
			return $post_id;
		}
	
		$nonce = $_POST['redeculturaviva_meta_inner_custom_box_nonce'];
	
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'redeculturaviva_meta_inner_custom_box' ) )
		{
			return $post_id;
		}
	
		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return $post_id;
		}
		
		$id = 'hide-sidebar';
		
		if( array_key_exists($id, $_POST) )
		{
			update_post_meta($post_id, "_".$id, $_POST[$id]);
		}
		else // checkbox not checked
		{
			delete_post_meta($post_id, "_".$id);
		}
		
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