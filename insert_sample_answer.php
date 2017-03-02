<?php

function insert_sample_answer() {
    global $wpdb;
    
    $user_id = 1;
    $question_id = 1;
    $answer = 'No one gnomes.';
    $upvote = 0;
    
    $table_name = $wpdb->prefix . 'gs_answer'; /*dont know if this is right*/ 
    
    $wpdb->insert(
        $table_name, array(
            'user_id'=> $user_id,
            'question_id'=> $question_id,
            'answer'=> $answer,
            'up_vote'=> $upvote
        )
    );
}