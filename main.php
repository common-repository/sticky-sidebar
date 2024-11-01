<?php
/*
Plugin Name: Sticky Sidebar
Description: Make a sticky sidebar and place it anywhere with shortcode.
Tags: sticky widget, sticky sidebar, sidebar, widget, shortcode, sticky
Author URI: http://funboards.dk/
Author: Kjeld Hansen
Text Domain: sticky_sidebar
Requires at least: 4.0
Tested up to: 4.4.2
Version: 1.0
*/

add_action('admin_menu','sticky_widget_admin_menu');
function sticky_widget_admin_menu() { 
    add_menu_page(
		"Sticky Widget",
		". Settings",
		8,
		__FILE__,
		"sticky_widget_admin_menu_list",
		plugins_url( 'Sticky-Widget/img/sticky-icon.png') , 40
	); 
}

function sticky_widget_admin_menu_list(){
	include 'sticky-admin.php';
}



if(get_option( 'ri_sticky_widget_id' )){
	$stky_option = get_option( 'ri_sticky_widget_id' );
	if($stky_option): $i=1;
		foreach($stky_option as $id=>$val):
			if(get_option( 'ri_sticky_widget_'.$val )){ $snmd = get_option( 'ri_sticky_widget_'.$val ); }
			$sbn = $snmd[name]; $sbd = $snmd[des]; 
			ri_sidebar_generator($val, $sbn, $sbd);
		endforeach;
	endif;
}

function ri_sidebar_generator($id, $nm, $des){
	
$args = array(
	'name'          => $nm,
    'id'            => 'rissb'.$id,          
	'description'   => $des,
	'class'         => '',
	'before_widget' => '<li id="%1$s" class="widget ri-sticky-wdg %2$s">',
	'after_widget'  => '</li>',
	'before_title'  => '<h2 class="widgettitle">',
	'after_title'   => '</h2>' ); 
	
	register_sidebar( $args );
}

if (!shortcode_exists('sticky_sidebar')) {
	add_shortcode('sticky_sidebar', 'sticky_sidebar_fn');
}

function sticky_sidebar_fn($args){
	
	$sbid = 'rissb'.$args[0];
	if(dynamic_sidebar( $sbid )){  }
}

add_action('wp_head','sticky_widget_load_js');

function sticky_widget_load_js(){
	?>
    <script type="text/javascript">
    	 //jQuery('.ri-sticky-wdg')
	jQuery( document ).ready(function() {
		 var wspos = jQuery(".ri-sticky-wdg").offset();
		 var w = jQuery(window);
		 var top1 = wspos.top-w.scrollTop();
		 if(top1<0){ jQuery(".ri-sticky-wdg").addClass('ri-sticky-fixed'); }
		 jQuery( window ).scroll(function() {
			var w = jQuery(window);
			var top1 = wspos.top-w.scrollTop();
			var top1 = wspos.top-w.scrollTop();
			if(top1<0){ jQuery(".ri-sticky-wdg").addClass('ri-sticky-fixed'); }else{ jQuery(".ri-sticky-wdg").removeClass('ri-sticky-fixed'); }
			jQuery(".ri-sticky-wdg").css({'top':-top1});
		 });
	});
	
    </script>
    <style type="text/css">
    	.ri-sticky-fixed {
			position: relative;
			/*top: 0;*/
			/*background: #fff;*/
		}
    </style>
   
    <?php
}