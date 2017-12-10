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
 * Retrieves all necessary data in order to call the TLMS 'Edit User' API Call and updates the database extension
 */
function prepareProfileUpdate($user_id, $old_user_data){
    $user = get_user_by( 'id', $user_id );

    // Get old and new user email
    $previous_mail = $old_user_data->user_email;
    $new_mail = $user->user_email;
    
    if($new_mail != $previous_mail){ // Check if mail has changed on profile update
        // Mail has changed

        $tlms_user_id = getTLMSUserIdByMail($previous_mail); // Get TLMS User ID for API Call

        try{
            updateUserEmailAPICall($tlms_user_id, $new_mail);
        } catch (Exception $e){
            error_log($e->getMessage());
            error_log($e->getHttpStatus());
            return;
        }
        
        if( editUserInDatabase($previous_mail, $new_mail)){
            // Success, do nothing
        } else {
            // Failure, Roll back changes on TLMS?
        }
        
    } else {
        // Mail did not change, live happily ever after
    }  
}

/**
 * Tries to call the TLMS 'Edit User' API Call via user ID and user email
 * Returns nothing on success
 * Throws an exception on failure
 */
function updateUserEmailAPICall($tlms_user_id, $user_email_new){
    // Call API
    try{
        TalentLMS_User::edit(array(
            'user_id' => $tlms_user_id,
            // 'first_name' => '{firstName}',
            // 'last_name' => '{lastName}', 
            'email' => $user_email_new
            // 'login' => '{userName}',
            // 'password' => '{password}', 
            // 'bio' => '{bio}', 
            // 'timezone' => '{timeZone}', 
            // 'credits' => '{credits}'
            )
        );
    } catch(Exception $e){
        throw $e;
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