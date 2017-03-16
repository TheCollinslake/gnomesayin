<?php
/**
 * Gnomesayin' API
 *
 */
function gs_register_api_hooks() {
  $namespace = 'gnomesayin/v1';

  register_rest_route( $namespace, '/questions/', array(
      'methods' => 'GET',
      'callback' => 'gs_get_questions',
  ) );
    
  register_rest_route( $namespace, '/questions/', array(
    // EDITABLE the same as 'POST' or 'PUT'
    'methods'   => WP_REST_Server::EDITABLE,
    'callback' => 'gs_add_question',
  ) );
  
  register_rest_route( $namespace, '/questions/upvote/', array(
    // EDITABLE the same as 'POST' or 'PUT'
    'methods'   => WP_REST_Server::EDITABLE,
    'callback' => 'gs_upvote_question',
  ) );
  
  register_rest_route( $namespace, '/answers/', array(
      'methods' => 'GET',
      'callback' => 'gs_get_answers',
  ) );
  
  register_rest_route( $namespace, '/answers/', array(
    // EDITABLE the same as 'POST' or 'PUT'
    'methods'   => WP_REST_Server::EDITABLE,
    'callback' => 'gs_add_answer',
  ) );
  
  register_rest_route( $namespace, '/answers/upvote/', array(
    // EDITABLE the same as 'POST' or 'PUT'
    'methods'   => WP_REST_Server::EDITABLE,
    'callback' => 'gs_upvote_answer',
  ) );
}

/**
 * Adds new question to the database. User must be logged in.
 *
 * METHOD: PUT
 * URL: http://localhost:8888/wp-json/gnomesayin/v1/questions
 */
function gs_add_question($request_data) {
  global $wpdb;
  $parameters = $request_data->get_params();
  $validLogin = is_user_logged_in();
  
  if($validLogin) {
    // User is authenticated, insert data into database
    $user_id = get_current_user_id();
    $return[] = array(
      'logged_in' => $validLogin,
      'user_id' => $user_id,
      'question' => $parameters["question"]
    );
    
    $table_name = $wpdb->prefix . 'gs_question';
    $wpdb->insert( 
		$table_name, 
		array( 
			'user_id' => $user_id, 
			'question' => $parameters["question"]
		) 
	);
    $return[] = $parameters;
    $response = new WP_REST_Response( $return, 200 );
    $response->header('Content-Type', 'application/json');
  } else {
    // User not logged in, throw an error
    $return = array(
      'error' => 'Invalid credentials.'
    );
    $response = new WP_REST_Response( $return, 401 );
    $response->header( 'Access-Control-Allow-Origin', apply_filters( 'gs_access_control_allow_origin','*' ) );
    $response->header('Content-Type', 'application/json');
  }
  
  return $response;
}


/**
 *
 * METHOD: POST
 */
function gs_upvote_question($request_data) {
  global $wpdb;
  $parameters = $request_data->get_params();
  $safe_id = intval( $parameters['question_id'] );
  if ( !$safe_id ) {
    $safe_id = 1;
  }
  $table_name = $wpdb->prefix . 'gs_question';

  $sql_query = "UPDATE $table_name SET up_vote = up_vote + 1 WHERE id = $safe_id;";
  $return[] = array(
    'message' => 'Successfully updated ' . $safe_id
  );

  $wpdb->query( $sql_query );
  $response = new WP_REST_Response( $return, 200 );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'gs_access_control_allow_origin','*' ) );
  $response->header('Content-Type', 'application/json');
  
  return $response;
}

/**
 *
 * METHOD: POST
 */
function gs_upvote_answer($request_data) {
  global $wpdb;
  $parameters = $request_data->get_params();
  $safe_id = intval( $parameters['answer_id'] );
  if ( !$safe_id ) {
    $safe_id = 1;
  }
  $table_name = $wpdb->prefix . 'gs_answer';

  $sql_query = "UPDATE $table_name SET up_vote = up_vote + 1 WHERE id = $safe_id;";
  $return[] = array(
    'message' => 'Successfully updated ' . $safe_id
  );

  $wpdb->query( $sql_query );
  $response = new WP_REST_Response( $return, 200 );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'gs_access_control_allow_origin','*' ) );
  $response->header('Content-Type', 'application/json');
  
  return $response;
}

/**
 * Return questions. This request is public.
 *
 * METHOD: GET
 * URL: http://localhost:8888/wp-json/gnomesayin/v1/questions
 */
function gs_get_questions() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'gs_question';
  $user_table = $wpdb->prefix . 'users';
  $sql_query = "SELECT * FROM $table_name gs INNER JOIN $user_table user ON user.ID = gs.user_id ORDER BY gs.reg_date DESC;";


  $gs_rows = $wpdb->get_results( $sql_query );
  $return = array();
  foreach ( $gs_rows as $row ) 
  {
    $return[] = array(
      'id' => $row->id,
      'user_name' => $row->user_nicename,
      'user_id' => $row->user_id,
      'question' => $row->question,
      'reg_date' => $row->reg_date,
      'answer_count' => 2 // Hard coded to 2. Should probably be fixed. #Gnome
    );  
  }
  $response = new WP_REST_Response( $return, 200 );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'gs_access_control_allow_origin','*' ) );
  $response->header('Content-Type', 'application/json');
  
  return $response;
}

/**
 * Return questions. This request is public.
 *
 * METHOD: GET
 * URL: http://localhost:8888/wp-json/gnomesayin/v1/answers?question_id=1
 */
function gs_get_answers($request_data) {
  global $wpdb;
  $parameters = $request_data->get_params();
  $safe_id = intval( $parameters['question_id'] );
  if ( !$safe_id ) {
    $safe_id = 1;
  }

  $table_name = $wpdb->prefix . 'gs_answer';
  $user_table = $wpdb->prefix . 'users';
  $sql_query = "SELECT * FROM $table_name answer INNER JOIN $user_table user ON user.ID = answer.user_id WHERE answer.question_id = $safe_id ORDER BY answer.up_vote DESC;";
  $gs_rows = $wpdb->get_results( $sql_query );
  $return = array();
  foreach ( $gs_rows as $row ) 
  {
    $return[] = array(
      'id' => $row->id,
      'user_name' => $row->user_nicename,
      'user_id' => $row->user_id,
      'answer' => $row->answer,
      'time_stamp' => $row->reg_date,
      'up_vote' => $row->up_vote
    );  
  }
  $response = new WP_REST_Response( $return, 200 );
  $response->header( 'Access-Control-Allow-Origin', apply_filters( 'gs_access_control_allow_origin','*' ) );
  $response->header('Content-Type', 'application/json');
  
  return $response;
}

/**
 * Adds new question to the database. User must be logged in.
 *
 * METHOD: PUT
 * URL: http://localhost:8888/wp-json/gnomesayin/v1/questions
 */
function gs_add_answer($request_data) {
  global $wpdb;
  $parameters = $request_data->get_params();
  $validLogin = is_user_logged_in();
  
  if($validLogin) {
    // User is authenticated, insert data into database
    $user_id = get_current_user_id();
    $return[] = array(
      'logged_in' => $validLogin,
      'user_id' => $user_id,
      'answer' => $parameters["answer"]
    );
    
    $table_name = $wpdb->prefix . 'gs_answer';
    $wpdb->insert( 
		$table_name, 
		array( 
			'user_id' => $user_id, 
            'question_id' => $parameters["question_id"],
			'answer' => $parameters["answer"]
		) 
	);
    $response = new WP_REST_Response( $return, 200 );
    $response->header('Content-Type', 'application/json');
  } else {
    // User not logged in, throw an error
    $return = array(
      'error' => 'Invalid credentials.'
    );
    $response = new WP_REST_Response( $return, 401 );
    $response->header( 'Access-Control-Allow-Origin', apply_filters( 'gs_access_control_allow_origin','*' ) );
    $response->header('Content-Type', 'application/json');
  }
  
  return $response;
}