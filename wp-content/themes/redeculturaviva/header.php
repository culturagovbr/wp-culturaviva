<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rede_Cultura_Viva
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'rede-cultura-viva' ); ?></a>

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

	<div id="content" class="site-content site-padding">
