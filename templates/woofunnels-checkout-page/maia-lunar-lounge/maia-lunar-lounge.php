<?php
/**
 * The Header for our theme
 *
 * @package    WordPress
 * @since      1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php if ( get_theme_mod( 'penci_favicon' ) ) : ?>
		<link rel="shortcut icon" href="<?php echo esc_url( get_theme_mod( 'penci_favicon' ) ); ?>" type="image/x-icon" />
	<?php endif; ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php bloginfo( 'rss2_url' ); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?> Atom Feed" href="<?php bloginfo( 'atom_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<style type="text/css">
		.featured-carousel .item { opacity: 1; }
	</style>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
/**
 * Get header layout in your customizer to change header layout
 *
 * @author PenciDesign
 */
$header_layout = get_theme_mod( 'penci_header_layout' );
if ( ! isset( $header_layout ) || empty( $header_layout ) ) {
	$header_layout = 'header-1';
}
?>
<a id="close-sidebar-nav" class="<?php echo esc_attr( $header_layout ); ?>"><i class="fa fa-close"></i></a>

<nav id="sidebar-nav" class="<?php echo esc_attr( $header_layout ); ?>">

	<?php if ( ! get_theme_mod( 'penci_header_logo_vertical' ) ) : ?>
		<div id="sidebar-nav-logo">
			<?php if ( ! get_theme_mod( 'penci_mobile_nav_logo' ) ) : ?>
				<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/mobile-logo.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo esc_url( get_theme_mod( 'penci_mobile_nav_logo' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! get_theme_mod( 'penci_header_social_check' ) ) : ?>
		<?php if ( get_theme_mod( 'penci_email_me' ) || get_theme_mod( 'penci_vk' ) || get_theme_mod( 'penci_facebook' ) || get_theme_mod( 'penci_twitter' ) || get_theme_mod( 'penci_google' ) || get_theme_mod( 'penci_instagram' ) || get_theme_mod( 'penci_pinterest' ) || get_theme_mod( 'penci_linkedin' ) || get_theme_mod( 'penci_flickr' ) || get_theme_mod( 'penci_behance' ) || get_theme_mod( 'penci_tumblr' ) || get_theme_mod( 'penci_youtube' ) || get_theme_mod( 'penci_rss' ) ) : ?>
			<div class="header-social sidebar-nav-social">
				<?php include( trailingslashit( get_template_directory() ). 'inc/modules/socials.php' ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	/**
	 * Display main navigation
	 */
	wp_nav_menu( array(
		'container'      => false,
		'theme_location' => 'main-menu',
		'menu_class'     => 'menu',
		'fallback_cb'    => 'penci_menu_fallback',
		'walker'         => new penci_menu_walker_nav_menu()
	) );
	?>
</nav>

<!-- .wrapper-boxed -->
<div class="wrapper-boxed header-style-<?php echo esc_attr( $header_layout ); ?><?php if ( get_theme_mod( 'penci_body_boxed_layout' ) ) : echo ' enable-boxed'; endif;?>">
<header id="header" class="header-<?php echo esc_attr( $header_layout ); ?><?php if( ( ( ! is_home() || ! is_front_page() ) && ! get_theme_mod( 'penci_featured_slider_all_page' ) ) || ( ( is_home() || is_front_page() ) && ! get_theme_mod( 'penci_featured_slider' ) ) ): ?> has-bottom-line<?php endif;?>"><!-- #header -->
	<div class="inner-header">
		<div class="container<?php if( $header_layout == 'header-3' ): echo ' align-left-logo'; if( get_theme_mod( 'penci_header_3_banner' ) || get_theme_mod( 'penci_header_3_adsense' ) ): echo ' has-banner'; endif; endif;?>">
			<div id="logo">
				<h2><a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo esc_url( get_theme_mod( 'penci_logo' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" /></a></h2>
			</div>
			<?php if( ( get_theme_mod( 'penci_header_3_adsense' ) || get_theme_mod( 'penci_header_3_banner' ) ) && $header_layout == 'header-3' ): ?>
				<?php
				$banner_img = get_theme_mod( 'penci_header_3_banner' );
				$open_banner_url = '';
				$close_banner_url = '';
				if( get_theme_mod( 'penci_header_3_banner_url' ) ):
					$banner_url = get_theme_mod( 'penci_header_3_banner_url' );
					$open_banner_url = '<a href="'. esc_url( $banner_url ) .'" target="_blank">';
					$close_banner_url = '</a>';
				endif;
				?>
				<div class="header-banner header-style-3">
					<?php echo wp_kses( $open_banner_url, penci_allow_html() ); ?><img src="<?php echo esc_url( $banner_img ); ?>" alt="Banner" /><?php echo wp_kses( $close_banner_url, penci_allow_html() ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</header>
<!-- end #header -->
<div class="container container-single penci_sidebar right-sidebar penci-enable-lightbox">
	<div class="header-standard header-classic single-header">
		<h1 class="post-title single-post-title"><?php the_title(); ?></h1>
	</div>
	<div id="main">
		<div class="theiaStickySidebar">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="post-entry">
					<div class="inner-post-entry">
						<?php the_content(); ?>
						<?php echo WooFunnels_Checkout_Form::get_checkout_form(); ?>
					</div>
				</div>
			</article>
		</div>
	</div>
	<?php if ( is_active_sidebar( 'wf-cp' ) ) : ?>

	<div id="sidebar">
		<div class="theiaStickySidebar">
			<?php dynamic_sidebar( 'wf-cp' ); ?>
		</div>
	</div>
	<?php endif; ?>
</div>
<div class="clear-footer"></div>
<?php if ( ( is_active_sidebar( 'custom-sidebar-1' ) || is_active_sidebar( 'custom-sidebar-2' ) || is_active_sidebar( 'custom-sidebar-3' ) ) ) : ?>

	<div id="widget-area">
	<div class="container">
	<div class="footer-widget-wrapper">
	<?php /* Widgetised Area */
if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'custom-sidebar-1' ) ) ?>
	</div>
	<div class="footer-widget-wrapper">
<?php /* Widgetised Area */
if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'custom-sidebar-2' ) ) ?>
	</div>
	<div class="footer-widget-wrapper last">
<?php /* Widgetised Area */
	if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'custom-sidebar-3' ) ) ?>
		</div>
		</div>
		</div>
<?php endif; ?>
<footer id="footer-section">
	<div class="container">
		<?php if ( ! get_theme_mod( 'penci_footer_social' ) ) : ?>
			<?php if ( get_theme_mod( 'penci_email_me' ) || get_theme_mod( 'penci_vk' ) || get_theme_mod( 'penci_facebook' ) || get_theme_mod( 'penci_twitter' ) || get_theme_mod( 'penci_google' ) || get_theme_mod( 'penci_instagram' ) || get_theme_mod( 'penci_pinterest' ) || get_theme_mod( 'penci_linkedin' ) || get_theme_mod( 'penci_flickr' ) || get_theme_mod( 'penci_behance' ) || get_theme_mod( 'penci_tumblr' ) || get_theme_mod( 'penci_youtube' ) || get_theme_mod( 'penci_rss' ) ) : ?>
				<div class="footer-socials-section">
					<ul class="footer-socials">
					</ul>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( ( get_theme_mod( 'penci_footer_logo' ) && ! get_theme_mod( 'penci_hide_footer_logo' ) ) || get_theme_mod( 'penci_footer_copyright' ) || ! get_theme_mod( 'penci_go_to_top' ) ) : ?>
			<div class="footer-logo-copyright<?php if ( ! get_theme_mod( 'penci_footer_logo' ) || get_theme_mod( 'penci_hide_footer_logo' ) ) : echo ' footer-not-logo'; endif; ?><?php if ( get_theme_mod( 'penci_go_to_top' ) ) : echo ' footer-not-gotop'; endif; ?>">
				<?php if ( get_theme_mod( 'penci_footer_logo' ) && ! get_theme_mod( 'penci_hide_footer_logo' ) ) : ?>
					<div id="footer-logo">
						<a href="<?php echo esc_url( home_url('/') ); ?>">
							<img src="<?php echo esc_url( get_theme_mod( 'penci_footer_logo' ) ); ?>" alt="<?php esc_html_e( 'Footer Logo', 'soledad' ); ?>" />
						</a>
					</div>
				<?php endif; ?>

				<?php if( get_theme_mod( 'penci_footer_menu' ) ): ?>
					<div class="footer-menu-wrap">
					<?php
					/**
					 * Display main navigation
					 */
					wp_nav_menu( array(
						'container'      => false,
						'theme_location' => 'footer-menu',
						'menu_class'     => 'footer-menu'
					) );
					?>
					</div>
				<?php endif; /* End check if enable footer menu */?>

				<?php if ( get_theme_mod( 'penci_footer_copyright' ) ) : ?>
					<div id="footer-copyright">
						<p><?php echo wp_kses( get_theme_mod( 'penci_footer_copyright' ), penci_allow_html() ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( ! get_theme_mod( 'penci_go_to_top' ) ) : ?>
					<div class="go-to-top-parent"><a href="#" class="go-to-top"><span><i class="fa fa-angle-up"></i><br><?php esc_html_e( 'Back To Top', 'soledad' ); ?></span></a></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</footer>

</div><!-- End .wrapper-boxed -->

<div id="fb-root"></div>

<?php wp_footer(); ?>
</body>
</html>