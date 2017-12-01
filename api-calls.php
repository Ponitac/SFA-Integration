<?php
/* 
    This file contains all functionality regarding outgoing and incoming API activity between SFA Wordpress and TalentLMS
*/

require_once(dirname(__FILE__).'helper-functions.php');
    
function prepareUserRegistration($user){
    
    // Check if user is already registered on TalentLMS 
    if(isUserOnTLMS($user)){
        // If user is already registered on TalentLMS, no registration is necessary.
        // Obviously...

    } else {
        // If user is not registered on talentLMS, setup user data and register him
        $user_info = get_userdata($user.get('id')); // Get user info object to retrieve attributes
        registerUserAPICall($user_info);
    }
}

// Takes in a user_info object and registers that user on TalentLMS
function registerUserAPICall($user_info){
    
    // Pull necessary user data from user_info object
    $userFirstName = $user_info->first_name
    $userLastName = $user_info->last_name
    $userEmail = $user_info->user_email
    $userLoginName = $user_info->user_login

    $userPassword = generateTLMSPassword($user_info->user_pass); // Generate the password to be used on the TLMS side
    
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

    } catch (TalentLMS_ApiError $e){
        // echo $e->getMessage();
        $httpStatus = $e.getHTTPStatus();
        // Do stuff that figures shit out
    }
}

// Redirects to TalentLMS, logging in with the given user ID
function redirectToTLMS($userID){

    // call TLMS API
    $returnSet = TalentLMS_User::retrieve({$userID}

    // Retrieve login key from return JSON
    $loginKey = $returnSet['login_key'];

    // Build returnURL
    $returnUrl = "https://beta.social-finance-academy.org/"

    // Redirect user to TLMS
    redirect($loginKey);

    // Log out of Wordpress

}

?>