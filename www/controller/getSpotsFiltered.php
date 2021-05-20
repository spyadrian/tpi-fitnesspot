<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   getSpotsFiltered.php
 * Description  :   A controller who control the data that will arrive from the table spots.
 */

require_once "../model/Spot.php";
require_once "../model/Media.php";


// ---- FIELDS ---- 

$NO_IMAGE = 'no-image-found.jpg';

$spot = new Spot();
$media = new Media();

$filter = filter_input(INPUT_POST, 'filter', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$from = filter_input(INPUT_POST, 'from', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$offset = filter_input(INPUT_POST, 'offset', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$onlyPopular = filter_input(INPUT_POST, 'onlyPopular', FILTER_DEFAULT);

$someSpots = [];


// ---- GET SPOT ----


if (empty($filter)) {

    if ($onlyPopular == "true") {
        $someSpots = $spot->GetSpotsOrderByGradeOnlyPopular($from, $offset);
    } else {
        $someSpots = $spot->GetSpotsOrderByGrade($from, $offset);
    }
} else {
    if ($onlyPopular == "true") {
        $someSpots = $spot->GetSpotsWithFilterOnlyPopular($filter, $from, $offset);
    } else {
        $someSpots = $spot->GetSpotsWithFilter($filter, $from, $offset);
    }
}


// ---- ASSOCIATE MEDIA & SPOT ---- 

// intelisense cry, because it's an array of array, but it work perfectly
for ($i = 0; $i < count($someSpots, COUNT_NORMAL); $i++) {

    $m = $media->GetMediasByIdSpot($someSpots[$i]['idSpot'], 1);

    if (empty($m)) $m[0]['fileName'] = $NO_IMAGE;

    $someSpots[$i] += ["media_fileName" => $m[0]['fileName']];
}


// ---- CREATE HTML ---- 

foreach ($someSpots as $s) : ?>

    <div class="card d-flex flex-row shadow w-100 mb-5" style="height: 150px;">


        <a href="./oneSpot.php?idSpot=<?= $s['idSpot'] ?>" class="w-25">
            <img src="../asset/image/<?= $s['media_fileName'] ?>" alt="spot's image" class="w-100" style="height: 148px;">
        </a>


        <div class="card-body d-flex ps-3">

            <!-- Text & Star -->
            <div class="d-flex flex-column justify-content-center align-items-start w-15">
                <h3><?= empty($s['name']) ? 'Spot Nameless' : $s['name'] ?></h3>
                <small id="containerStar" class="d-flex justify-content-between w-33"><?= ConvertGradeToStar($s['averageRate']); ?></small>
            </div>

            <!-- Address -->
            <div class="d-flex justify-content-center align-items-center w-35" href="#">
                <p class="mb-0" onclick="SetResearch('<?= explode(',', $s['address'])[1] ?>')"><?= $s['address'] ?></p>
            </div>

            <!-- Detail -->
            <div class="d-flex justify-content-center align-items-center w-35">
                <p class="mb-0"><?= empty($s['detail']) ? 'This spot dont have any detail.' : $s['detail']  ?></p>
            </div>

            <!-- Heart -->
            <div class="d-flex justify-content-center align-items-center w-15">
                <i class="bi bi-heart-fill" style="font-size: 2rem;"></i>
            </div>

        </div>
    </div>

<?php endforeach;


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
