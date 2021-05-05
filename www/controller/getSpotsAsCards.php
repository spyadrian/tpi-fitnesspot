<?php

require_once "../model/Spot.php";

$spot = new Spot();


// ---- GET SPOT ----

$someSpots = $spot->GetSpotsOrderByGrade(10);

// get image foreach post