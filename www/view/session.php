<?php
require_once '../model/User.php';

session_start();

function SetSessionLevel($level, $urlToRedirect = '../index.php')
{
    if ($_SESSION['userPermission'] < $level) {
        header('location: ' . $urlToRedirect);
        exit;
    }
}
