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
    //header("Content-Type: application/xml; charset=utf-8");

    //Initiate API    
    TalentLMS::setApiKey(getAPIKEY()); // Set the API key according to the options
    TalentLMS::setDomain(getDomain()); // Set the domain accoding to the options

    /**
     * TalentLMS_User::login(array('login' => '{username}', 'password' => '{password}', 'logout_redirect' => '{logoutRedirect}'))
     * Extend database by login name
     * 
     * Get Username
     */

    $userEmail = wp_get_current_user()->user_email;
    $userPassword = getTLMSPasswordByMail($userEmail);
    $logoutRedirect = get_home_url();

    error_log($userEmail);
    error_log($userPassword);

    $returnSet = TalentLMS_User::login(array(
        'login' => $userEmail,
        'password' => $userPassword, 
        'logout_redirect' => $logoutRedirect)
    );

    // $returnSet = TalentLMS_User::retrieve(array('email' => $userEmail));
    
    // Retrieve login key from return JSON
    $loginKey = $returnSet['login_key'];
    header("Location: ".$loginKey );
    exit;

} catch (Exception $e){
    error_log($e->getMessage());
    error_log($e->getHttpStatus());
    echo "You need to be loggin in in order to access the online courses of Social Financ Academy. Please go back and log in.";
}