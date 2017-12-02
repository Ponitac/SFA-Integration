<?php
/* 
    This file contains all functionality regarding outgoing and incoming API activity between SFA Wordpress and TalentLMS
*/

require_once(dirname(__FILE__).'/helper-functions.php');
require_once(dirname(__FILE__).'/database-operations.php');
    
function prepareUserRegistration($user){

    $userFirstName = $user->first_name;
    $userLastName = $user->last_name;
    $userEmail = $user->user_email;
    $userLoginName = $user->user_login;

    $userPassword = generateTLMSPassword(); // Generate the password to be used on the TLMS side
       
    // Check if user is already registered on TalentLMS 
    if(isUserInDatabase($userEmail)){
        // If user is already registered on TalentLMS, no registration is necessary.
        // Obviously...
        
    } else {
        // If user is not registered on talentLMS, setup user data and register him
           // Pull necessary user data from user_info object
        if(registerUserAPICall($userFirstName,$userLastName,$userEmail,$userLoginName,$userPassword)){
                addUserToDatabase($userEmail,$userPassword);
        }
    }
}

// Takes in a user_info object and registers that user on TalentLMS
function registerUserAPICall($userFirstName,$userLastName,$userEmail,$userLoginName,$userPassword){
    
    try{
        $return = TalentLMS_User::signup(array(
            'first_name' => $userFirstName,
            'last_name' => $userLastName,
            'email' => $userEmail, 
            'login' => $userLoginName, 
            'password' => $userPassword));

        return true;

    } catch (Exception $e){
        error_log($e->getMessage());
        error_log($e->getHttpStatus());

        return false;
    }

    // TO BE DONE: 
    /* if(addUserToDatabase($userEmail, $userPassword) == false){
        // Handle error
    } */

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