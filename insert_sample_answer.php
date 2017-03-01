<?php

global $gs_db_version;
$gs_db_version = '1.0';

function insert_sample_answer() {
    global $wpdb;
    
    $user_id = 1;
    $question_id = 1;
    $answer = 'This is a sample answer.';
    $upvote = 0;
    
    $table_name = $wpdb->perefix . 'gs_answer'; /*dont know if this is right*/ 
    
    wpdb->insert(
        $table_name, array(
            'user_id'=> $user_id,
            'question_id'=> $question_id,
            'answer'=> $answer,
            'upvote'=> $upvote
        )
    )
};
