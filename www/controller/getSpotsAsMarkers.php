<?php

require_once "../model/Spot.php";


// ---- FIELDS ---- 

$spot = new Spot();

$latMin = filter_input(INPUT_POST, 'latMin', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$latMax = filter_input(INPUT_POST, 'latMax', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$lonMin = filter_input(INPUT_POST, 'lonMin', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$lonMax = filter_input(INPUT_POST, 'lonMax', FILTER_DEFAULT, FILTER_SANITIZE_STRING);

$someSpots = [];


// ---- GET SPOT ----

$someSpots = $spot->GetSpotsBetweenLonLat($latMin, $latMax, $lonMin, $lonMax, 50);

echo json_encode($someSpots);
