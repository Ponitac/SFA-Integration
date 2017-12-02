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

header('Content-Type: text/html; charset=utf-8');
require_once(dirname(__FILE__).'/api-calls.php'); // Require API call library
require_once(dirname(__FILE__).'/TalentLMSLib/lib/TalentLMS.php'); // Require TLMS Library
require_once(dirname(__FILE__).'/sfa-options.php'); // Require options menu for setting api key / domain

// User registration hook
function registerUserOnTLMS($user_login, $user){
    // Register user in TalentLMS
    // Use functions of api-calls.php
    //if (get_option( 'sfa_domain') && get_option( 'sfa_key')) {
        //TalentLMS::setApiKey(get_option( 'sfa_key'));
        //TalentLMS::setDomain(get_option( 'sfa_domain'));
        TalentLMS::setApiKey('YIKUQdyDwdzRYuy5pxJB2uAIQCMqTq');
        TalentLMS::setDomain('courses.socialfinanceacademy.org');
        prepareUserRegistration($user);
    //}    
}
add_action('wp_login', 'registerUserOnTLMS', 10, 2);

// Activation hook
function init(){
    // TODO: Read options
    header('Content-Type: text/html; charset=utf-8');
    
    // ini_set('display_errors', false);    
    $configuration = parse_ini_file('config.ini'); // Read config
    
    try{
        //Initiate API    
        TalentLMS::setApiKey('YIKUQdyDwdzRYuy5pxJB2uAIQCMqTq');
        TalentLMS::setDomain('courses.socialfinanceacademy.org');
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

register_activation_hook(__FILE__, 'init' );

?>