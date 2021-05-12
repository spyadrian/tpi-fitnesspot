<?php

require_once "../model/Like.php";


// ---- FIELDS ---- 

$like = new Like();

$idSpot = filter_input(INPUT_POST, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$idUser = filter_input(INPUT_POST, 'idUser', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT); //! not suppose to get it like that
$liked = filter_input(INPUT_POST, 'liked', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$liked = ($liked == "1") ? "0" : "1";

$myLike = null;


// ---- ADD / UPDATE VOTE ----

$myLike = $like->GetLikeById($idSpot, $idUser);

if (empty($myLike))
    $like->AddLike($idSpot, $idUser, $liked);
else
    $like->UpdateLike($idSpot, $idUser, $liked);


// ---- UPDATE SPOT NUMBER LIKE ----

$like->UpdateNumberLikeSpot($idSpot);

echo 'success';
