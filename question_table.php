<?php
function questions_install_db() {
    global $wpdb;
    global $gs_db_version;
    $table_name = $wpdb->prefix . 'gs_question';

    $charset_collate = $wpdb->get_charset_collate();
    $installed_ver = get_option( "gs_db_version" );

  if ( $installed_ver != $gs_db_version ) {
// sql to create table
    $sql = "CREATE TABLE $table_name ( 
        id mediumint(9) NOT NULL AUTO_INCREMENT, 
        user_id mediumint(9) NOT NULL,
        question text NOT NULL,
        reg_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        up_vote mediumint(9) NOT NULL DEFAULT 0,
        best_answer mediumint(9) NOT NULL DEFAULT 0,
        category mediumint(9) NOT NULL DEFAULT 0,
        UNIQUE KEY id (id)
    );";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    update_option( 'gs_db_version', $gs_db_version );
  }
}