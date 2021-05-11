<?php

require_once "../model/Spot.php";


// ---- FIELDS ---- 

$filter = filter_input(INPUT_POST, 'filter', FILTER_DEFAULT, FILTER_SANITIZE_STRING);

$spot = new Spot();
$count = 0;


// ---- GET COUNT ----


if (empty($filter)) {
    $count = $spot->GetCount();
} else {
    $count = $spot->GetCountWithFilter($filter);
}

echo json_encode($count);
