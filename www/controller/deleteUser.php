<?php

require_once "../model/User.php";
require_once "../view/session.php";

if (!isset($_SESSION['userId']) || ($_SESSION['userId'] != $idUser && $_SESSION['userPermission'] != '2')) {
    header('location: ./home.php');
    exit;
}

// ---- FIELDS ---- 

$user = new User();


// ---- DELETE USER ----

$user->DeleteUser($idUser);
