<?php 
/*export csv */
function admin_post_list_add_export_button() {
    ?>
	<form action="" method="post" name="frmCSVExport" id="frmCSVExport"
	enctype="multipart/form-data">
      <input type="submit" name="export_all_posts" class="button button-primary" value="<?php _e('Export All Orders'); ?>" />
	  </form>
  <?php   
}

/* // add_action( 'manage_posts_extra_tablenav', 'admin_post_list_add_export_button', 20, 1 );
function func_export_all_posts() { */
    if(isset($_POST['export_all_posts'])) {
         $arg = array(
            'post_type' => 'shop_order',            
            'numberposts' => -1,
			'meta_key' => '_license_no'						
		 ); 
			
		$orders = wc_get_orders( array('numberposts' => -1, 'meta_key' => '_license_no') );
		$dt = date('Y-m-d-H-i-s') ;
		$filename = 'WooOrder-'.$dt ;
        
       /* $arr_post = get_posts($arg);*/
        if ($orders) {			
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
  
            $file = fopen('php://output', 'w');
  
             fputcsv($file, array('ID', 'status', '_payment_method', '_payment_method_title', '_billing_first_name', '_billing_last_name'));
				// $sku = get_post_meta( $postID, '', true );  
           foreach( $orders as $order ){
                /* setup_postdata($post); */
                $order_id =  $order->get_id();
				$order = wc_get_order( $order_id );
				$order_data = $order->get_data(); 
				
				$status = $order->get_status();
				$_order_key = get_post_meta( $order_id, '_order_key', true ); 
				$_payment_method = $order->get_payment_method();
				$_payment_method_title = $order->get_payment_method_title();              
                $_billing_first_name = $order_data['billing']['first_name']; 
				$_billing_last_name = $order_data['billing']['last_name']; 				
				fputcsv($file, array($order_id, $status, $_payment_method, $_payment_method_title, $_billing_first_name, $_billing_last_name ));
            }  
            exit();
		}else{
			echo '<script type="text/javascript">alert("Error");</script>';
		}
    }
	
	admin_post_list_add_export_button(); 
/* }
 
add_action( 'init', 'func_export_all_posts' );
https://stackoverflow.com/questions/39401393/how-to-get-woocommerce-order-details?noredirect=1&lq=1
/*end of export csv */