<?php

require_once "../model/Spot.php";
require_once "../view/session.php";

SetSessionLevel(2);


// ---- FIELDS ---- 

$spot = new Spot();
$idSpot = filter_input(INPUT_GET, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);


// ---- DELETE SPOT ----

$spot->DeleteSpot($idSpot);


// ---- RETURN PAGE ----

header('location: ../view/home.php');
exit;
