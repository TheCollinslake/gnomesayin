<?php

function insert_sample_question() {
	global $wpdb;
	
	$user_id = 1;
	$question = 'How many gnomes does it take to answer a question?';
	
	$table_name = $wpdb->prefix . 'gs_question';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'user_id' => $user_id, 
			'question' => $question
		) 
	);
}