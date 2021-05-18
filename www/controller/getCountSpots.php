<?php

require_once "../model/Spot.php";


// ---- FIELDS ---- 

$filter = filter_input(INPUT_POST, 'filter', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$onlyPopular = filter_input(INPUT_POST, 'onlyPopular', FILTER_DEFAULT);

$spot = new Spot();
$count = 0;


// ---- GET COUNT ----


if (empty($filter)) {

    if ($onlyPopular == "true") {
        $count = $spot->GetCountOnlyPopular();
    } else {
        $count = $spot->GetCount();
    }
} else {
    if ($onlyPopular == "true") {
        $count = $spot->GetCountWithFilterOnlyPopular($filter);
    } else {
        $count = $spot->GetCountWithFilter($filter);
    }
}

echo json_encode($count);
