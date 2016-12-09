<?php
/**
 * The Header for our theme
 *
 * @package    WordPress
 * @since      1.0
 */
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
<div class="container container-single penci-enable-lightbox wf-fp-wrapper">
	<div id="main">
		<div class="theiaStickySidebar">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post-entry">
					<div class="inner-post-entry">
						<?php
						$p2['products'] = get_post_meta( get_the_ID(), 'woofunnels_products_to_display', true );
						WooFunnels_pb::woofunnels_offer_block( $p2, 'first-promo-block' );
					//	WooFunnels_pb::woofunnels_offer_block( $p2, 'product-single' );
						?>
						<?php echo WooFunnels_Checkout_Form::get_checkout_form(); ?>
					</div>
				</div>
			</article>
		</div>
	</div>
</div>
<div class="clear-footer"></div>
	<div class="container">
		<div class="theiaStickySidebar">
			<?php the_content(); ?>
		</div>
	</div>
<footer id="footer-section">
	<div class="container">
		<?php if ( ( get_theme_mod( 'penci_footer_logo' ) && ! get_theme_mod( 'penci_hide_footer_logo' ) ) || get_theme_mod( 'penci_footer_copyright' ) || ! get_theme_mod( 'penci_go_to_top' ) ) : ?>
			<div class="footer-logo-copyright<?php if ( ! get_theme_mod( 'penci_footer_logo' ) || get_theme_mod( 'penci_hide_footer_logo' ) ) : echo ' footer-not-logo'; endif; ?><?php if ( get_theme_mod( 'penci_go_to_top' ) ) : echo ' footer-not-gotop'; endif; ?>">
				<?php if ( get_theme_mod( 'penci_footer_logo' ) && ! get_theme_mod( 'penci_hide_footer_logo' ) ) : ?>
					<div id="footer-logo">
						<a href="<?php echo esc_url( home_url('/') ); ?>">
							<img src="<?php echo esc_url( get_theme_mod( 'penci_footer_logo' ) ); ?>" alt="<?php esc_html_e( 'Footer Logo', 'soledad' ); ?>" />
						</a>
					</div>
				<?php endif; ?>
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