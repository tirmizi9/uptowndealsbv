<?php
/*
Plugin Name: Woo Add to Cart Url
Plugin URI: https://codeweavers.net
Description: Get Woocommerce Products add to cart url
Version: 1.0.0
Author: Syed Muzaffar Tirmizi
Author URI: https://www.upwork.com/freelancers/syedtirmizi
License: GNU Public License v3
*/

function register_wooaddtocart_submenu_page() {
    add_submenu_page( 'woocommerce', 'Add to Cart Url Page', 'Add to Cart url', 'manage_options', 'mjt_wooaddtocart_submenu_page', 'mjt_wooaddtocart_submenu_page_callback' ); 
	
	add_submenu_page( 'woocommerce', 'Export Order', 'Export Order', 'manage_options', 'mjt_export_order_submenu_page', 'mjt_export_order_submenu_page_callback' ); 
}
function mjt_wooaddtocart_submenu_page_callback() {
	 echo '<div class="wrap">
            <h2>Add to Cart Urls</h2>';
   include_once('importcsv.php');
   echo '</div>';
}
add_action('admin_menu', 'register_wooaddtocart_submenu_page',99); 

// https://wordpress.stackexchange.com/questions/101773/add-a-subitem-to-woocommerce-section


function mjt_export_order_submenu_page_callback(){ 
	echo '<div class="wrap">
			<h2>Export Orders</h2>';
	include_once('export-order-csv.php');
	echo '</div>';
}

include_once('ordermeta.php');


