<?php

require_once "../model/Spot.php";
require_once "../model/Media.php";
require_once "../model/Like.php";


// ---- FIELDS ---- 

$NO_IMAGE = 'no-image-found.jpg';

$spot = new Spot();
$media = new Media();
$like = new Like();

$idSpot = filter_input(INPUT_GET, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);

$mySpot = null;
$myMedias = [];
$myLike = null;


// ---- GET SPOT & MEDIA & LIKE ----

$mySpot = $spot->GetSpot($idSpot);
if (empty($mySpot)) return;
else $mySpot = $mySpot[0];

$myMedias = $media->GetMediasByIdSpot($mySpot['idSpot'], 20);

if (empty($myMedias)) $myMedias[0]['fileName'] = $NO_IMAGE;

// if the user liked the spot
$myLike = $like->GetLikeById($mySpot['idSpot'], $mySpot['idUser']);
if (empty($myLike) || $myLike[0]['liked'] == "0") $myLike = "0";
else $myLike = "1";

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
