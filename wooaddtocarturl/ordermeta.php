<?php 

// add_filter ('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout() {
   
    $checkout_url =wc_get_checkout_url();
    return $checkout_url;
}

/* Add Custom Field @ WooCommerce Checkout Page */
add_action( 'woocommerce_before_order_notes', 'mjtwoo_add_custom_checkout_field' );
  
function mjtwoo_add_custom_checkout_field( $checkout ) { 
   $current_user = wp_get_current_user();
   $saved_license_no = $current_user->license_no;
   woocommerce_form_field( 'license_no', array(        
      'type' => 'hidden',        
      'class' => array( 'form-row-wide','external_product' ),        
      'label' => '',        
      'placeholder' => 'External Product',        
      'required' => true,        
      'default' => $saved_license_no, 	  
   ), $checkout->get_value( 'license_no' ) ); 
}

/*  Validate Custom Field @ WooCommerce Checkout Page */
add_action( 'woocommerce_checkout_process', 'mjtwoo_validate_new_checkout_field' );
  
function mjtwoo_validate_new_checkout_field() {    
   if ( ! $_POST['license_no'] ) {
      wc_add_notice( 'This is External Product', 'error' );
   }
}

/* Save & Display Custom Field @ WooCommerce Order */
add_action( 'woocommerce_checkout_update_order_meta', 'mjtwoo_save_new_checkout_field' );
  
function mjtwoo_save_new_checkout_field( $order_id ) { 
    if ( $_POST['license_no'] ) update_post_meta( $order_id, '_license_no', esc_attr( $_POST['license_no'] ) );
}
 
add_action( 'woocommerce_thankyou', 'mjtwoo_show_new_checkout_field_thankyou' );
   
function mjtwoo_show_new_checkout_field_thankyou( $order_id ) {    
   if ( get_post_meta( $order_id, '_license_no', true ) ) echo '<p style="color: transparent; height: 0; line-height: 0px;  padding: 0;  margin: 0;">' . get_post_meta( $order_id, '_license_no', true ) . '</p>';
}
  
add_action( 'woocommerce_admin_order_data_after_billing_address', 'mjtwoo_show_new_checkout_field_order' );
   
function mjtwoo_show_new_checkout_field_order( $order ) {    
   $order_id = $order->get_id();
   if ( get_post_meta( $order_id, '_license_no', true ) ) echo '<p>' . get_post_meta( $order_id, '_license_no', true ) . '</p>';
}
 
add_action( 'woocommerce_email_after_order_table', 'mjtwoo_show_new_checkout_field_emails', 20, 4 );
  
function mjtwoo_show_new_checkout_field_emails( $order, $sent_to_admin, $plain_text, $email ) {
    if ( get_post_meta( $order->get_id(), '_license_no', true ) ) echo '<p style="color: transparent; height: 0; line-height: 0px;  padding: 0;  margin: 0;">' . get_post_meta( $order->get_id(), '_license_no', true ) . '</p>';
} 


/* custom js script */
add_action('wp_footer', 'mjtwoo_is_external_checkout');
function mjtwoo_is_external_checkout(){
	if(is_page('checkout') && isset($_GET['extpdt'])){ 
	$extpdt = $_GET['extpdt']; 
	?>
		<script type="text/javascript"> 
			jQuery(document).ready(function(){
				var extpdt = '<?php echo $extpdt ;?>';
				jQuery('.external_product span #license_no').val('External Product');
			});
		</script>
	<?php }
}