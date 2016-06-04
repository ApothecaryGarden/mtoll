<?php
/**
 * Template to display a single product as per standard WooCommerce Templates
 *
 * @package WooFunnels/Templates
 * @version 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$the_post_id = $post->ID;

foreach ( $products as $single_product ) :

	$product      = $single_product;
	$post         = $single_product->post;

	?>
	<div class="wf-single-product wf-fp-block">

		<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php woocommerce_template_single_title(); ?>
			<?php
				/**
				 * woocommerce_before_single_product_summary hook
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
			?>

			<div class="summary entry-summary product-item <?php if ( $product->in_cart ) { echo 'selected'; } ?>">

				<?php
				//	woocommerce_template_single_title();
					woocommerce_template_single_excerpt();
				?>



			</div><!-- .summary -->

			<meta itemprop="url" content="<?php the_permalink(); ?>" />
			<div class="product-quantity">

				<?php
					/**
					 * woofunnels_single_add_to_cart hook
					 *
					 * @hooked wf_single_add_to_cart - 10
					 */
					woocommerce_template_single_price();
					do_action( 'woofunnels_single_add_to_cart', $the_post_id );
				?>

			</div>
		</div><!-- #product-<?php the_ID(); ?> -->

	</div>
<?php endforeach; ?>

<?php wp_reset_postdata(); ?>
