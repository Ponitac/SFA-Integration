<?php
/* 
    This file contains all functionality regarding outgoing and incoming API activity between SFA Wordpress and TalentLMS
*/

require_once(dirname(__FILE__).'/helper-functions.php');
    
function prepareUserRegistration($user){
       
    // Check if user is already registered on TalentLMS 
    if(isUserOnTLMS($user->user_email)){
        // If user is already registered on TalentLMS, no registration is necessary.
        // Obviously...
        error_log("User is already on TLMS - let's move him there");
        redirectToTLMS($user->user_email);
    } else {
        // If user is not registered on talentLMS, setup user data and register him
        registerUserAPICall($user);
    }
}

// Takes in a user_info object and registers that user on TalentLMS
function registerUserAPICall($user){
    
    // Pull necessary user data from user_info object
    $userFirstName = $user->first_name;
    $userLastName = $user->last_name;
    $userEmail = $user->user_email;
    $userLoginName = $user->user_login;

    $userPassword = generateTLMSPassword($user->user_pass); // Generate the password to be used on the TLMS side
    
    try{
        $return = TalentLMS_User::signup(array(
            'first_name' => $userFirstName,
            'last_name' => $userLastName,
            'email' => $userEmail, 
            'login' => $userLoginName, 
            'password' => $userPassword));

        /* $tlms_userID = $return['id'];
        $tlms_login = $return['login'];
        $tlms_first_name = $return['first_name'];
        $tlms_last_name = $return['last_name'];
        $tlms_email = $return['email'];
        $tlms_loginKey = $return['loginKey']; */

    } catch (Exception $e){
        error_log($e->getMessage());
        error_log($e->getHttpStatus());
        // Do stuff that figures shit out
    }

}

// Redirects to TalentLMS, logging in with the given user ID
function redirectToTLMS($userEmail){

    // call TLMS API
    try{
        $returnSet = TalentLMS_User::retrieve(array('email' => $userEmail));
        // Retrieve login key from return JSON
        $loginKey = $returnSet['login_key'];
        error_log($loginKey);
        wp_redirect( $loginKey );
        exit;

        // Build returnURL
        //$returnUrl = "https://beta.social-finance-academy.org/";
    
        // Redirect user to TLMS
        //redirect($loginKey);
    } catch (Exception $e){
        error_log($e->getMessage());
        error_log($e->getHttpStatus());
    }
    

    // Log out of Wordpress

}

?>