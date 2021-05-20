<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   getSpotsAsCards.php
 * Description  :   A controller who control the data that will arrive from the table spots as cards.
 */

require_once "../model/Spot.php";
require_once "../model/Media.php";


// ---- FIELDS ---- 

$NO_IMAGE = 'no-image-found.jpg';

$spot = new Spot();
$media = new Media();

$someSpots = [];


// ---- GET SPOT ----

$someSpots = $spot->GetSpotsOrderByGrade(0, 8);


// ---- ASSOCIATE MEDIA & SPOT ---- 

// intelisense cry, because it's an array of array, but it work perfectly
for ($i = 0; $i < count($someSpots, COUNT_NORMAL); $i++) {

    $m = $media->GetMediasByIdSpot($someSpots[$i]['idSpot'], 1);

    if (empty($m)) $m[0]['fileName'] = $NO_IMAGE;

    $someSpots[$i] += ["media_fileName" => $m[0]['fileName']];
}


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
