<?php

require_once "../model/Vote.php";


// ---- FIELDS ---- 

$vote = new Vote();

$idSpot = filter_input(INPUT_POST, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$idUser = filter_input(INPUT_POST, 'idUser', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$rate = filter_input(INPUT_POST, 'rate', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);


// ---- ADD / UPDATE VOTE ----

$myVote = $vote->GetVoteById($idSpot, $idUser);

if (empty($myVote))
    $vote->AddVote($idSpot, $idUser, $rate);
else
    $vote->UpdateVote($idSpot, $idUser, $rate);


// ---- UPDATE SPOT AVERAGE RATE ----

$vote->UpdateAverageRateSpot($idSpot);

echo 'success';
