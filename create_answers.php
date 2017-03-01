<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 28/02/2017
 * Time: 18:39
 */
function answers_install_db() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gs_answer';

        $sql = "CREATE TABLE $table_name (
                  UNIQUE KEY id (id),
                  user_id mediumint(9) NOT NULL FOREIGN ,
                  answer text NOT NULL,
                  time_stamp DATETIME_INTERVAL_CODE AUTO CURRENT_TIMESTAMP,
                  upvote mediumint(9),
                  question_id mediumint(9) FOREIGN NOT NULL 
    );";

    }


