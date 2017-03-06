<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 28/02/2017
 * Time: 18:39
 */
function answers_install_db() {
    global $wpdb;
    global $ga_db_version;
    $table_name = $wpdb->prefix . 'gs_answer';

        $charset_collate = $wpdb->get_charset_collate();
    $installed_ver = get_option( "gs_db_version" );
    
      if ( $installed_ver != $ga_db_version ) {
        $sql = "CREATE TABLE $table_name (
                  id mediumint(9) NOT NULL AUTO_INCREMENT,
                  user_id mediumint(9) NOT NULL,
                  answer text NOT NULL,
                  time_stamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  up_vote mediumint(9) NOT NULL DEFAULT 0,
                  question_id mediumint(9) NOT NULL,
                  UNIQUE KEY id (id)
        );";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    update_option( 'ga_db_version', $ga_db_version );
  }

}