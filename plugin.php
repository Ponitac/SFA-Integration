<?php
/*
Plugin Name:  sfa-TalentLMS-Integration
Description:  This plugin integrates the TalentLMS API Library and enables WordPress to automatically register and login WordPress users on TalentLMS
Version:      1.0
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
require_once(dirname(__FILE__).'/TalentLMSLib/lib/TalentLMS.php'); // Require TLMS Library
require_once(dirname(__FILE__).'/api-calls.php'); // Require functions for API calls
require_once(dirname(__FILE__).'/sfa-options.php'); // Require options menu for setting the API key and TLMS domain

/**
 * Entry point for registering the logged in user on TLMS
 * 
 * Is triggered by the wp_login hook
 * 
 * A more elegant way to do this would be right after registration, but the post-registration hook we found 
 * was not able to give all the parameters necessary for registration (login name, first name, last name, mail)
 */
function registerUserOnTLMS($user_login, $user){
        TalentLMS::setApiKey(getAPIKEY()); // Set the API key according to the options
        TalentLMS::setDomain(getDomain()); // Set the domain accoding to the options
        prepareUserRegistration($user); // Trigger user registration. $user is given by the wp_login hook
}
add_action('wp_login', 'registerUserOnTLMS', 10, 2);


/**
 * Initiates the plugin, 
 * i.e. sets up the database extension and sets API key and TLMS domain
 */
function init(){
    header('Content-Type: text/html; charset=utf-8');
    initDatabase(); // Create database if not already existing
    
    try{
        //Initiate API
        TalentLMS::setApiKey(getAPIKEY());
        TalentLMS::setDomain(getDomain());
    }
    catch(Exception $e){
        echo $e->getMessage();
    }    
}
//Update Profile when changes appeared
function onProfileUpdate( $user_id, $old_user_data ) {
    prepareProfileUpdate($user_id, $old_user_data);
}
add_action( 'profile_update', 'onProfileUpdate', 10, 2 );

function redirect_via_customized_post(){
    if ($post->ID == getRedirectPostID){
        try{
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        
            //Initiate API    
            TalentLMS::setApiKey(getAPIKEY()); // Set the API key according to the options
            TalentLMS::setDomain(getDomain()); // Set the domain accoding to the options
        
            $userEmail = wp_get_current_user()->user_email;
            $userPassword = getTLMSPasswordByMail($userEmail);
            $logoutRedirect = get_home_url();
        
            $returnSet = TalentLMS_User::login(array(
                'login' => $userEmail,
                'password' => $userPassword, 
                'logout_redirect' => $logoutRedirect)
            );
        
            // 
            
            // Retrieve login key from return JSON
            $loginKey = $returnSet['login_key'];
            header("Location: ".$loginKey );
            exit;
        
        } catch (Exception $e){
        
            try{
                $userEmail = wp_get_current_user()->user_email;
                $returnSet = TalentLMS_User::retrieve(array('email' => $userEmail));
        
                // Retrieve login key from return JSON
                $loginKey = $returnSet['login_key'];
                header("Location: ".$loginKey );
                exit;
            } catch (Exception $e2){
                error_log($e->getMessage());
                error_log($e->getHttpStatus());
                error_log($e2->getMessage());
                error_log($e2->getHttpStatus());
                echo "You need to be signed in to Social Finance Academy in order to access the online courses of Social Finance Academy. Please go back and log in.";
            }
    }
}




add_filter( 'post_link', 'redirect_via_customized_post', 10, 3 );


/**
 * Deletes the database extension table and unregisters the options
 */
function deinstallPlugin(){
    //Delete Dabase
    deleteDatabaseforDeinstallation();
    //Unregister added Options
    unregister_setting( 'sfa_tLMS_option_group', 'sfa_tLMS_options');
}

register_activation_hook( __FILE__ , 'init' );
register_uninstall_hook( __FILE__ , 'deinstallPlugin' );

?>