<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   session.php
 * Description  :   It's a module where the session start and where we can set the level of protection.
 */


require_once '../model/User.php';

session_start();

/**
 * Allow to set a level of protection according to the session of the user. 
 * @param   level           the level of protection
 * @param   urlToRedirect   the url the user will be redirect if he doesn't have the right permission
 */
function SetSessionLevel($level, $urlToRedirect = '../index.php')
{
    if ($_SESSION['userPermission'] < $level) {
        header('location: ' . $urlToRedirect);
        exit;
    }
}
