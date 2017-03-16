<?php
/*
Plugin Name: Gnome Sayin'
Plugin URI: http://example.com
Description: Simple Q&A
Version: 1.0
Author: IM4420
Author URI: 
*/

global $gq_db_version;
global $ga_db_version;
$gq_db_version = '1.6';
$ga_db_version = '1.6';

function welcome() {
    echo "<div class='gnome_wrapper'>";
    echo "<h2>Gnome Sayin'</h2>";
    $validLogin = is_user_logged_in();
	if(!$validLogin) {
      // You must be logged in to submit a form
      gs_showLoginForm();
	} else {
      // Create forms
      gs_showSubmitQuestionForm();
      gs_showAnswerForm();
    }
  
    gs_showAnswerList();
    gs_showQuestionsList();
    echo "</div>";
}

/**
 * Form that allows a user to submit an answer.
 */
function gs_showAnswerForm() {
  echo "<div id='aform_container'>";
  echo "<button id='back_button'>BACK</button>";
  echo "<h3 id='question_headline'>What's the Question???</h3>";
  echo '<form id="answer_form" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
  echo "Your Answer: <input id='answer_text' type='text' name='answer'/>";
  echo "<button type='submit'>Submit</button>";
  echo "</form>";
  echo "</div>";
}

/**
 * Form that allows a user to submit a question.
 */
function gs_showSubmitQuestionForm() {
  echo "<div id='qform_container'>";
  echo "<h3>Enter a new question</h3>";
  echo '<form id="question_form" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
  echo "<input id='question_text' type='text' name='question'/>";
  echo "<button type='submit'>Submit</button>";
  echo "</form>";
  echo "</div>";
}

function gs_showAnswerList() {
  echo "<div id='answer_container'>";
  echo "<h3>Answers:</h3>";
  echo "<div id='answer_table'>Select a question to replace this text.</div>";
  echo "</div>";
}

function gs_showQuestionsList() {
  echo "<div id='question_container'>";
  echo "<h3>Recent Questions</h3>";
  echo "<div id='question_table'>Loading... please wait.</div>";
  echo "</div>";
}

function qp_shortcode() {
    ob_start(); // WP function, way to display plugin fast as it can
    
    welcome();
    
    return ob_get_clean(); 
}

add_shortcode( 'question_plugin', 'qp_shortcode' );

/**
 * Database setup.
 */
// CREATE questions hook
require_once (dirname(__FILE__) . '/question_table.php');
// Called when our plugin is activated
register_activation_hook( __FILE__, 'questions_install_db' ); 


// INSERT questions hook
require_once (dirname(__FILE__) . '/insert_sample_question.php');
// Called when our plugin is activated
register_activation_hook( __FILE__, 'insert_sample_question' ); 


// CREATE questions hook
require_once (dirname(__FILE__) . '/create_answers.php');
// Called when our plugin is
register_activation_hook( __FILE__, 'answers_install_db' ); 


// INSERT questions hook
require_once (dirname(__FILE__) . '/insert_sample_answer.php');
// Called when our plugin is activated
register_activation_hook( __FILE__, 'insert_sample_answer' ); 

/**
 * API setup.
 */
// API hook
require_once (dirname(__FILE__) . '/gnome_api.php');
// Called when our plugin is activated
add_action( 'rest_api_init', 'gs_register_api_hooks' );

/**
 * Admin page setup.
 */
function wpdocs_register_gs_custom_menu_page() {
    add_menu_page(
        __( 'Gnome Sayin', 'textdomain' ),
        'Gnome Options',
        'manage_options',
        'gnomesayin/admin_options.php',
        '',
        plugins_url( 'gnomesayin/images/icon.png' ),
        6
    );
}

add_action( 'admin_menu', 'wpdocs_register_gs_custom_menu_page' );

/**
 * Login.
 *
 * We must execute the login code before HTML is written 
 * to the page. This can be done by registering our check 
 * login function the template_redirect action. 
 */
function gs_check_login(){
	if(isset($_POST["gs-username"]) && isset($_POST["gs-password"])) {
		
    $creds = array(
        'user_login'    => $_POST["gs-username"],
        'user_password' => $_POST["gs-password"],
        'rememember'    => true
    );
 
    $user = wp_signon( $creds, false );
		if ( !is_wp_error($user) ) {
          wp_set_current_user($user->ID);
		}
	}
}

// When we redirect, check the login before rendering HTML
add_action('template_redirect', 'gs_check_login');

function gs_showLoginForm() {
  echo "<h3>Login to enter questions</h3>";
  echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
  echo '<p>';
  echo 'Username<br />';
  echo '<input type="text" name="gs-username" pattern="[a-zA-Z0-9 ]+" value="' . ( isset($_POST["gs-username"]) ? esc_attr( $_POST["gs-username"] ) : '' ) . '" size="40" />';
  echo '</p>';
  echo '<p>';
  echo 'Password<br />';
  echo '<input type="password" name="gs-password" size="40" />';
  echo '</p>';
  echo '<p><input type="submit" name="gs-submitted" value="Send"/></p>';
  echo '</form>';
}

/**
 * Proper way to enqueue scripts and styles
 */
function wpdocs_theme_name_scripts() {
  // WP JavaScript file required for AJAX authentication
  // https://github.com/WP-API/client-js
  wp_enqueue_script( 'wp-api' );
  wp_register_style('gnome_styles', plugins_url('style.css',__FILE__ ));
  wp_enqueue_style('gnome_styles');
  wp_enqueue_script( 'jquery' );
  wp_register_script( 'gnome_js', plugins_url('gnomes.js',__FILE__ ));
  wp_enqueue_script('gnome_js');
  wp_localize_script('gnome_js', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );