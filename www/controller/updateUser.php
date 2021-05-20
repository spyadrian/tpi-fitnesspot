<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   updateUser.php
 * Description  :   A controller who control the data who will update an user.
 */

require_once "../model/User.php";


// ---- FIELDS ---- 

$user = new User();

$pseudo = filter_input(INPUT_POST, 'updateUser_Pseudo', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'updateUser_Email', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'updateUser_Password', FILTER_DEFAULT, FILTER_SANITIZE_STRING);

$passwordHash = hash('sha256', $password . $user->PASSWORD_SEL);

$error = null;
$myUser = null;


// ---- FILTRE DATA ----


if (empty($password))
    $error = 'The password must not be empty. ';

if (empty($email))
    $error = 'The email must not be empty. ';

if (empty($pseudo))
    $error = 'The pseudo must not be empty. ';


// ---- CREATE USER ----

if ($error == null) {
    $myUser = $user->UpdateUser($idUser, $pseudo, $email, $passwordHash);
}
