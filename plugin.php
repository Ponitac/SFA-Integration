<?php
/*
Plugin Name:  sfa-TalentLMS-Integration
Description:  Plugin to implement the TalentLMS API and connects wp-User to their Talent-LMS accounts.
Version:      0.1
Author:       Matteo Ramin, Jan-Ulrich Holtgrave
Author URI:   https://github.com/ponitac
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:  /languages

sfa-TalentLMS-Integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
sfa-TalentLMS-Integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with sfa-TalentLMS-Integration. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


require_once(dirname(__FILE__).'/api-calls.php'); // Require API call library
require_once(dirname(__FILE__).'/TalentLMSLib/lib/TalentLMS.php'); // Require TLMS Library
require_once(dirname(__FILE__).'/sfa-options.php'); // Require options menu for setting api key / domain

/* global $tLMS_db_version;
$tLMS_db_version = '1.0';

function createDatabase() {
    global $wpdb;
    global $tLMS_db_version;

    $table_name = $wpdb->prefix . 'sfatLMS';

    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE  TABLE $table_name (id mediumint(9) NOT NULL AUTO_INCREMENT,
    mail text NOT NULL,
    PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );
    add_option( 'tLMS_db_version', $tLMS_db_version );
}

function updateDB($talentLMSUser){
    global $wpdb;
    $table_name = $wpdb->prefix . 'sfatLMS';

    if (is_array($talentLMSUser)) {
        foreach ($talentLMSUser as $User) {
            $wpdb->insert(
                $table_name,
                array('mail' => $user['email'])
            )
        }
    }
} */

// User registration hook
function registerUserOnTLMS($user_login, $user){
    // Register user in TalentLMS
    // Use functions of api-calls.php
    error_log(get_option('sfa_domain'));
    error_log(get_option( 'sfa_key'));
    if (get_option( 'sfa_domain')) {
        TalentLMS::setDomain(get_option( 'sfa_domain'));
        TalentLMS::setApiKey(get_option( 'sfa_key'));
        prepareUserRegistration($user);    
    }else{
        error_log("Error in API Set");
    }

    
}
add_action('wp_login', 'registerUserOnTLMS', 10, 2);

// Activation hook
function init(){
    // Read options
    header('Content-Type: text/html; charset=utf-8');
    
    // ini_set('display_errors', false);
    
    $configuration = parse_ini_file('config.ini'); // Read config
    
    try{
        //Initiate API    
        TalentLMS::setApiKey($configuration[key]);
        TalentLMS::setDomain($configuration[domain]);
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

    // Init Button 'go to TalentLMS'
    
}

// Deactivation hook
function deactivatePlugin(){
    // Hide button
}

// Deinstallation hook
function deinstallPlugin(){
    // Delete all the things
}

 function initAPI(){

    	
    add_filter( 'wp_get_nav_menu_items', 'custom_nav_menu_items2', 20, 2 );

    $configuration = parse_ini_file('config.ini');

    ini_set('display_errors', false);

    header('Content-Type: text/html; charset=utf-8');

    try{

        //Initiate API
        TalentLMS::setApiKey($configuration[key]);
        TalentLMS::setDomain($configuration[domain]);

        //Get Users
        $users = TalentLMS_User::all();

        foreach($users as $user){
            if ($user['first_name']=='Matteo')  error_log($user['first_name'], 0);
        }

    }
    catch(Exception $e){
        echo $e->getMessage();
    }
}    


function custom_nav_menu_items2( $items, $menu ) {
  if ( $menu->slug == 'main_menu' ) {
    $top = _custom_nav_menu_item( 'Top level', '/some-url', 100 );

    $items[] = $top;
    $items[] = _custom_nav_menu_item( 'First Child', '/some-url', 101, $top->ID );
    $items[] = _custom_nav_menu_item( 'Third Child', '/some-url', 103, $top->ID );
    $items[] = _custom_nav_menu_item( 'Second Child', '/some-url', 102, $top->ID );
  }

  return $items;
}

register_activation_hook(__FILE__, 'initAPI' );

?>