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
    TalentLMS::setApiKey('YIKUQdyDwdzRYuy5pxJB2uAIQCMqTq');
    TalentLMS::setDomain('courses.socialfinanceacademy.org');

    $userEmail = wp_get_current_user()->user_email;
    error_log($userEmail);
    $returnSet = TalentLMS_User::retrieve(array('email' => $userEmail));
    
    // Retrieve login key from return JSON
    $loginKey = $returnSet['login_key'];
    error_log($loginKey);
    header("Location: ".$loginKey );
    exit;

    // Build returnURL
    //$returnUrl = "https://beta.social-finance-academy.org/";

    // Redirect user to TLMS
    // redirect($loginKey);
} catch (Exception $e){
    error_log($e->getMessage());
    error_log($e->getHttpStatus());
    echo "You need to be loggin in in order to access the online courses of Social Financ Academy. Please go back and log in.";
}