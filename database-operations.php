<?php 

/**
 * This file provides all database related functionality
 */

global $tLMS_db_version;
global $wpdb;
$table_prefix = 'sfaTLMS';
$tLMS_db_version = '1.0';


/**
 * Triggers the creation of the database extension if it does not yet exist
 */
function initDatabase(){
    $table_prefix = 'sfaTLMS';
    global $wpdb;
    $table_name = $wpdb->prefix . $table_prefix;
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){ // Check if database already exists
        createDatabase();
    }
}

/**
 * Creates a database with the prefix 'sfatLMS' and three fields:
 *  id: auto-incrementing ID
 *  mail: the mail of the user
 *  passwrd: the TLMS password of the user
 */
function createDatabase() {
    global $wpdb;
    $table_prefix = 'sfaTLMS';
    $tLMS_db_version = '1.0';

    $table_name = $wpdb->prefix . $table_prefix;

    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT, 
        mail text NOT NULL,
        passwrd text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );
    add_option( 'tLMS_db_version', $tLMS_db_version );
}

/**
 * Adds a new user to the database (if a user with that mail does not already exist).
 * Returns 1 if successful or false if unsuccessful.
 */
function addUserToDatabase($email, $password){
    global $wpdb;
    $table_prefix = 'sfaTLMS';
    $table_name = $wpdb->prefix . $table_prefix;
    
    return $wpdb->insert(
        $table_name, 
        array( 
            'mail' => $email, 
            'passwrd' => $password, 
        )
    );
}

/**
 * Returns the email and password corresponding to the requested email.
 * Returns an empty string if user was not found. 
 */
function getTLMSPasswordByMail($email){
    global $wpdb;
    $table_prefix = 'sfaTLMS';
    $table_name = $wpdb->prefix . $table_prefix;

    $password = "";

    $password = $wpdb->get_var($wpdb->prepare("SELECT passwrd FROM $wpdb->$table_name WHERE mail = %s", $email));

    return $password;
}

/**
 * Checks if the user is already in the database.
 * Returns true if he is already registered in the database.
 * Returns false if he is not registered in the database.
 */
function isUserInDatabase($email){
    $isInDatabase = FALSE;
    global $wpdb;
    $table_prefix = 'sfaTLMS';
    $table_name = $wpdb->prefix . $table_prefix;

    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %s WHERE mail = %s",  $wpdb->$table_name, $email));

    if($count > 0){
        $isInDatabase = TRUE;
    }
    return $isInDatabase;
}

?>