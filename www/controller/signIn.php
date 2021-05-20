<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   signIn.php
 * Description  :   A controller who control the connexion of an user.
 */

require_once '../view/session.php';
require_once "../model/User.php";


// ---- FIELDS ---- 

$user = new User();

$email = filter_input(INPUT_POST, 'signIn_Email', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'signIn_Password', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$passwordHash = hash('sha256', $password . $user->PASSWORD_SEL);

$error = null;
$myUser = null;


// ---- GET USER ----

$myUser = $user->GetUserByEmailAndPassword($email, $passwordHash);


// ---- FILTRE DATA ----

if (empty($myUser))
    $error = 'Your password or email is wrong. ';

if (empty($password))
    $error = 'The password must not be empty. ';

if (empty($email))
    $error = 'The email must not be empty. ';


// ---- RETURN HOME SET SESSION ----

if ($error == null) {
    $_SESSION["userId"] = $myUser[0]['idUser'];
    $_SESSION['userPermission'] = $myUser[0]['permission'];

    header("location: ../view/home.php");
    exit;
}
