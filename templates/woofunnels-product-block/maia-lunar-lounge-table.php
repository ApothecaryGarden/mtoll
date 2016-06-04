<?php
/**
 * Template to display a pricing table in a list
 *
 * @package WooFunnels/Templates
 * @version 0.1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $product;
?>

<div class="wf-pricing-table-wrapper wf_columns_<?php echo count( $products ); ?>">
<?php foreach( $products as $product ) : ?>
	<div class="wf-pricing-table-product product-item <?php if ( $product->in_cart ) echo 'selected'; ?>">
		<div class="wf-pricing-table-product-header">
			<h3 class="wf-pricing-table-product-title"><?php echo $product->get_title(); ?></h3>
		</div>

		<?php if ( $product->has_attributes() ) : ?>
			<!-- Product Attributes -->
			<div class="wf-pricing-table-product-attributes">

				<?php if ( $product->is_type( 'variation' ) ) : ?>
					<?php foreach( $product->get_variation_attributes() as $attribute_title => $attribute_value ) : ?>
				<h4 class="attribute_title"><?php echo wc_attribute_label( str_replace( 'attribute_', '', $attribute_title ) ); ?></h4>
				<p><?php echo WooFunnels_pb::get_formatted_attribute_value( $attribute_title, $attribute_value, $product->parent->get_attributes() ); ?></p>
					<?php endforeach; ?>
				<?php else : ?>
					<?php foreach( $product->get_attributes() as $attribute ) :
							if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
								continue;
							} ?>
				<h4 class="attribute_title"><?php echo wc_attribute_label( $attribute['name'] ); ?></h4>
				<p><?php
					if ( $attribute['is_taxonomy'] ) {
						$values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
						foreach ( $values as $attribute_value ) {
							echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( $attribute_value ) ), $attribute, $values );
						}
					} else {
						// Convert pipes to commas and display values
						$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
						foreach ( $values as $attribute_value ) {
							echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( $attribute_value ) ), $attribute, $values );
						}
					}
					?>
				</p>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="wf-pricing-table-product-header">
			<div class="wf-pricing-table-product-price">
				<p><?php echo $product->get_price_html(); ?></p>
			</div>
			<div class="product-quantity">
				<?php if ( $product->is_type( 'grouped' ) ) : ?>
					<?php
					$child_ids = '';
					foreach ( $product->get_children( true ) as $child_id ) {
						$child_ids[] = $child_id;
					}
				//	print_r($child_ids);
					$child_ids = implode( ',', $child_ids );
					$var['products'] = $child_ids;

					WooFunnels_pb::woofunnels_offer_block( $var, 'product-checklist' );
					?>
				<?php else : ?>
					<?php // wc_get_template( 'woofunnels-product-block/add-to-cart/wf.php', array( 'product' => $product ), '', WooFunnels::dir( 'templates/' ) ); ?>
					<?php wc_get_template( 'woofunnels-product-block/add-to-cart/button-1.php', array( 'product' => $product ), '', WooFunnels::dir( 'templates/' ) ); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( $product->enable_dimensions_display() && ( $product->has_weight() || $product->has_dimensions() ) ) : ?>
			<div class="wf-pricing-table-product-dimensions">
			<?php if ( $product->has_weight() ) : ?>
				<!-- Product Weight -->
				<h4><?php _e( 'Weight', 'woofunnels' ) ?></h4>
				<p class="product_weight"><?php echo $product->get_weight() . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $product->has_dimensions() ) : ?>
			<!-- Product Dimension -->
				<h4><?php _e( 'Dimensions', 'woofunnels' ) ?></h4>
				<p class="product_dimensions"><?php echo $product->get_dimensions(); ?></p>
			<?php endif; ?>
			</div>
		<?php endif; // $product->enable_dimensions_display() ?>
	</div>
<?php endforeach; // product in product_post?>
</div>
<div class="clear"></div>


