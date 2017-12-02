<?php
/**
 * Template Name: TLMS Redirect Template
 *
 * @package WordPress
 * @subpackage Social Finance Academy
 */
echo ("Is this real life?");
require_once(dirname(__FILE__).'/TalentLMSLib/lib/TalentLMS.php'); // Require TLMS Library

try{
    //Initiate API    
    TalentLMS::setApiKey('YIKUQdyDwdzRYuy5pxJB2uAIQCMqTq');
    TalentLMS::setDomain('courses.socialfinanceacademy.org');

    $userEmail = wp_get_current_user()->user_email;//"matt.ramin@gmail.com";
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
    //redirect($loginKey);
} catch (Exception $e){
    error_log($e->getMessage());
    error_log($e->getHttpStatus());
}