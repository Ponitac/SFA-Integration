<?php


/*  Generates a password to be used for registering a new user on TalentLMS

 */
function generateTLMSPassword($encryptedUserPass){
    $newPassword = "";
    
    // Generate a freaking password lmao
    $newPassword = "1234678"; //TO BE REPLACED, obviously

    return $newPassword;
}

/**
 * Checks if the user is already registered on TalentLMS 
 * Returns true if the user is already registered
 * Returns false if the user is not yet registered
 * TODO: Rewrite so the check runs via the internal database 
*/
function isUserOnTLMS($userEmail){
    $userIsOnTLMS = FALSE;

    try{
        $response = 
            TalentLMS_User::retrieve(array('email' => $userEmail));
        
        if(!empty($response['login_key'])){        
            $userIsOnTLMS = TRUE;
        }
    } catch (Exception $e){
        echo $e->getMessage();
    }
    
    // Alternatively: Check Wordpress database extension for the respective entry.
    return $userIsOnTLMS;
}

// Redirects the user to the given URL
function redirect($url){
    
    header("location:$url");
    exit;
}

function getAPIKey() {
    $sfaoptions = get_option( 'sfa_tLMS_options');
    if(!empty($sfaoptions)){
        $key = $sfaoptions['sfa_key'];
    }
    return key;
}

function getDomain() {
    $sfaoptions = get_option( 'sfa_tLMS_options');
    if(!empty($sfaoptions)){
        $domain = $sfaoptions['sfa_domain'];
    }
    return $domain;
}


?>