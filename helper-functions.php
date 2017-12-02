<?php
/**
 * This file provides useful functions to be used throughout the plugin.
 */

/**
 * Generates a random 8 character password with no special characters
 * 
 * This is currently used to create a random password that may be used for registering
 * new users on TalentLMS.
 */
function generateTLMSPassword(){
    $newPassword = "";
    
    // Generate a freaking password lmao
    $newPassword = wp_generate_password( 8, false);

    return $newPassword;
}

/**
 * Returns the TalentLMS API key from the sfa options
 */
function getAPIKey() {
    $sfaoptions = get_option( 'sfa_tLMS_options');
    if(!empty($sfaoptions)){
        $key = $sfaoptions['sfa_key'];
    }
    return $key;
}

/**
 * Returns the TalentLMS domain from the sfa options
 */
function getDomain() {
    $sfaoptions = get_option( 'sfa_tLMS_options');
    if(!empty($sfaoptions)){
        $domain = $sfaoptions['sfa_domain'];
    }
    return $domain;
}

/**
 * Checks if the user is already registered on TalentLMS via an API call
 * Returns true if the user is already registered
 * Returns false if the user is not yet registered
 * 
 * This function is currently NOT in use since we implemented a wordpress database extension that 
 * keeps track of who is already registered 
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
    
    return $userIsOnTLMS;
}


?>