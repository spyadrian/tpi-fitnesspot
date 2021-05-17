<?php

require_once "../model/User.php";


// ---- FIELDS ---- 

$user = new User();

$pseudo = filter_input(INPUT_POST, 'signUp_Pseudo', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'signUp_Email', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'signUp_Password', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$repeatPassword = filter_input(INPUT_POST, 'signUp_RepeatPassword', FILTER_DEFAULT, FILTER_SANITIZE_STRING);

$passwordHash = hash('sha256', $password . $user->PASSWORD_SEL);

$error = null;
$myUser = null;


// ---- FILTRE DATA ----

if ($password != $repeatPassword)
    $error = 'The repeated password must be the same as the real password. ';

if (empty($repeatPassword))
    $error = 'The repeated password must not be empty. ';

if (empty($password))
    $error = 'The password must not be empty. ';

if (empty($email))
    $error = 'The email must not be empty. ';

if (empty($pseudo))
    $error = 'The pseudo must not be empty. ';


// ---- CREATE USER ----

if ($error == null) {
    $myUser = $user->AddUser($pseudo, $email, $passwordHash, $user->PERMISSION_USER);

    header("location: ../view/signIn.php");
    exit;
}
