<?php

require_once "../model/User.php";


// ---- FIELDS ---- 

$filter = filter_input(INPUT_POST, 'filter', FILTER_DEFAULT, FILTER_SANITIZE_STRING);

$user = new User();
$count = 0;


// ---- GET COUNT ----


if (empty($filter)) {
    $count = $user->GetCount();
} else {
    $count = $user->GetCountWithFilter($filter);
}

echo json_encode($count);
