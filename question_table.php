<?php
    function questions_install_db() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gs_question';

// sql to create table
    $sql = "CREATE TABLE $table_name (

        Unique Key id (id) PRIMARY KEY, 
        user_id mediumint(9) NOT NULL FOREIGN KEY,
        question NOT NULL,
        reg_date TIMESTAMP,
        up_vote mediumint(9),
        best_answer,
        category
        
    )";

?>