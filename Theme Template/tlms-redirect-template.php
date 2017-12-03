<?php
/**
 * Template Name: TLMS Redirect Template
 *
 * @package WordPress
 * @subpackage Social Finance Academy
 */

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