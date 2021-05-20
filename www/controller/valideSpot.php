<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   valideSpot.php
 * Description  :   A controller who control the validation of a spot.
 */

require_once "../model/Spot.php";
require_once "../view/session.php";

SetSessionLevel(2);


// ---- FIELDS ---- 

$spot = new Spot();
$idSpot = filter_input(INPUT_GET, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);


// ---- VALIDE SPOT ----

$spot->ValideSpot($idSpot, 1);


// ---- RETURN PAGE ----

header('location: ../view/oneSpot.php?idSpot=' . $idSpot);
exit;
