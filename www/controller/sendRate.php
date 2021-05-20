<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   sendRate.php
 * Description  :   A controller who control that the rate is well sent.
 */

require_once "../model/Vote.php";
require_once "../model/Spot.php";
require_once "../view/session.php";


// ---- FIELDS ---- 

$vote = new Vote();
$spot = new Spot();

$idUser = $_SESSION['userId'];
$idSpot = filter_input(INPUT_POST, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$rate = filter_input(INPUT_POST, 'rate', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);

$mySpot = null;


// ---- ADD / UPDATE VOTE ----

$myVote = $vote->GetVoteById($idSpot, $idUser);

if (empty($myVote))
    $vote->AddVote($idSpot, $idUser, $rate);
else
    $vote->UpdateVote($idSpot, $idUser, $rate);


// ---- UPDATE & GET SPOT AVERAGE RATE ----

$vote->UpdateAverageRateSpot($idSpot);
$mySpot = $spot->GetSpot($idSpot);


// ---- RETURN STAR ---- 

echo ConvertGradeToStar($mySpot[0]['averageRate']);


// ---- FUNCTION ----

function ConvertGradeToStar($averageRate)
{
    $str_result = "";
    $starNumber = 0;

    while ($averageRate >= 1) {
        $str_result .= '<i class="bi bi-star-fill"></i>';
        $averageRate--;
        $starNumber++;
    }

    if ($averageRate >= 0.5) {
        $str_result .= '<i class="bi bi-star-half"></i>';
        $starNumber++;
    }

    while ($starNumber < 5) {
        $str_result .= '<i class="bi bi-star"></i>';
        $starNumber++;
    }

    return $str_result;
}
