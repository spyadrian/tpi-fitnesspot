<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   getOneSpot.php
 * Description  :   A controller who control the amout of information we will et of the spot.
 */

require_once "../model/Spot.php";
require_once "../model/Media.php";
require_once "../model/Like.php";
require_once "../model/Comment.php";


// ---- FIELDS ---- 

$NO_IMAGE = 'no-image-found.jpg';

$spot = new Spot();
$media = new Media();
$like = new Like();
$comment = new Comment();

$idSpot = filter_input(INPUT_GET, 'idSpot', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$idUser = null;
if ($actionAllowed)
    $idUser = $_SESSION['userId'];

$mySpot = null;
$myMedias = [];
$myLike = null;
$myComments = [];


// ---- GET SPOT ----

$mySpot = $spot->GetSpot($idSpot);
if (empty($mySpot)) die;
else $mySpot = $mySpot[0];


// ---- GET MEDIA ----

$myMedias = $media->GetMediasByIdSpot($mySpot['idSpot'], 20);

if (empty($myMedias)) $myMedias[0]['fileName'] = $NO_IMAGE;


// ---- GET LIKE ----

// if the user liked the spot
if ($actionAllowed) {
    $myLike = $like->GetLikeById($mySpot['idSpot'], $idUser);
    if (empty($myLike) || $myLike[0]['liked'] == "0") $myLike = "0";
    else $myLike = "1";
}

// ---- GET COMMENT ----

$myComments = $comment->GetCommentsByIdSpot($idSpot);


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
