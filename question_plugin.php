<?php
/*
Plugin Name: GnomeSayin Plugin
Plugin URI: http://example.com
Description: Just shortcode
Version: 0.1
Author: Dynamic Web Apps
Author URL: http://example.com
*/

function welcome() {
        
    echo "<h1>Welcome</h1>";
}

function qp_shortcode() {
    ob_start(); // WP function, way to display plugin fast as it can
    
    welcome();
    
    return ob_get_clean(); 
}

add_shortcode( 'question_plugin', 'qp_shortcode' );

?>