<?php


/*  Generates a password to be used for registering a new user on TalentLMS
    
 */
function generateTLMSPassword($encryptedUserPass){
    $newPassword = "";
    
    // Generate a freaking password lmao
    $newPassword = "1234678" //TO BE REPLACED, obviously

    return $newPassword;
}

/*  Checks if the user is already registered on TalentLMS 
    Returns true if the user is already registered
    Returns false if the user is not yet registered
*/
function isUserOnTLMS($userId){
    $userIsOnTLMS = FALSE;

    // Check Wordpress database extension for the respective entry.

    return $userIsOnTLMS;
}

// Redirects the user to the given URL
function redirect($url){
    header("location:$url");
    exit;
}


?>