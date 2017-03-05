<?php
/**
 * API
 *
 */
add_action( 'rest_api_init', 'dt_register_api_hooks' );

function dt_register_api_hooks() {
  $namespace = 'gnomesayin/v1';

  register_rest_route( $namespace, '/questions/', array(
      'methods' => 'GET',
      'callback' => 'gs_get_questions',
  ) );
}

// http://localhost:8888/wp-json/gnomesayin/v1/questions
function gs_get_questions() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'gs_question';
  $sql_query = "SELECT * 
                FROM " . $table_name . ";";
  $gs_rows = $wpdb->get_results( $sql_query );
  $return = array();
  foreach ( $gs_rows as $row ) 
  {
    $return[] = array(
      'id' => $row->id,
      'user_id' => $row->user_id,
      'question' => $row->question,
      'reg_date' => $row->reg_date,
      'up_votes' => $row->up_votes
    );  
  }
  $response = new WP_REST_Response( $return );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'gs_access_control_allow_origin','*' ) );
  $response->header('Content-Type', 'application/json');
  
  return $response;
}