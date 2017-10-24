<?php
/**
 * The header for our theme.
 *
 * @package Square
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.9.1/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.9.1/sweetalert2.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 

<script src="<?= get_template_directory_uri().'/js/moment.min.js'?>" type="text/javascript"></script>
<script src="<?= get_template_directory_uri().'/js/fullcalendar.min.js'?>" type="text/javascript"></script>
<link href="<?= get_template_directory_uri().'/css/fullcalendar.css'?>" rel='stylesheet'/>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="sq-page">
	<?php 
	$square_header_bg = get_theme_mod( 'square_header_bg','sq-black' ); 
	$square_sticky_header = get_theme_mod( 'square_disable_sticky_header' ); 
	$square_sticky_header_class = ($square_sticky_header) ? ' disable-sticky' : '';
	?>
	<header id="sq-masthead" class="sq-site-header <?php echo esc_attr($square_header_bg.$square_sticky_header_class); ?>">
		<div class="sq-container sq-clearfix">
			<div id="sq-site-branding">
				<?php if ( get_header_image() ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>">
					</a>
				<?php else: ?>
					<?php if ( is_front_page() ) : ?>
						<h1 class="sq-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="sq-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php endif; ?>
					<p class="sq-site-description"><?php bloginfo( 'description' ); ?></p>
				<?php endif; // End header image check. ?>
			</div><!-- .site-branding -->

			<div class="sq-toggle-nav">
				<span></span>
			</div>
			
			<nav id="sq-site-navigation" class="sq-main-navigation">
				<?php 
				wp_nav_menu( array( 
					'theme_location' => 'primary', 
					'container_class' => 'sq-menu sq-clearfix' ,
					'menu_class' => 'sq-clearfix',
					'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				) ); 
				?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->

	<div id="sq-content" class="sq-site-content sq-clearfix">
