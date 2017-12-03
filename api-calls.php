<?php
/**
 * This file contains all functionality regarding outgoing and incoming API activity between SFA Wordpress and TalentLMS
*/

require_once(dirname(__FILE__).'/helper-functions.php'); // Require helper functions
require_once(dirname(__FILE__).'/database-operations.php'); // Require database functionality
    
/**
 * Prepares all the necessary data for user registration and
 * triggers the user registration API call
 */
function prepareUserRegistration($user){

    // Get all relevant user data from $user object
    $userFirstName = $user->first_name;
    $userLastName = $user->last_name;
    $userEmail = $user->user_email;
    

    // Generate the password to be used on the TLMS side
    $userPassword = generateTLMSPassword(); 
       
    // Check if user is already registered on TalentLMS 
    if(isUserInDatabase($userEmail)){
        // If user is already registered on TalentLMS, no registration is necessary.
        // Obviously...        
    } else {
        // If user is not registered on talentLMS, try to register him
        if(registerUserAPICall( // Returns true if API call was successful
            $userFirstName, 
            $userLastName, 
            $userEmail, 
            $userEmail,
            $userPassword)){ 
                addUserToDatabase($userEmail, $userPassword); // Adds the user to the wordpress database extension
        }
    }
}

/**
 * Tries to register a user with the given parameters on the target TalentLMS instance
 * Returns true if the user was successfully registered
 * Returns false if the call failed
 */
function registerUserAPICall($userFirstName, $userLastName, $userEmail, $userLoginName, $userPassword){
    
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
}

?>