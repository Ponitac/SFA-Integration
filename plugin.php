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

// User registration hook
function registerUserOnTLMS($user_login, $user){
    // Register user in TalentLMS
    // Use functions of api-calls.php
    
    $configuration = parse_ini_file('config.ini'); // Read config
    //Initiate API
    TalentLMS::setApiKey($configuration[key]);
    TalentLMS::setDomain($configuration[domain]);
    prepareUserRegistration($user);
    
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

function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}


register_activation_hook(__FILE__, 'initAPI' );

?>