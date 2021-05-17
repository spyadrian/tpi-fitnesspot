<?php

require_once "../model/User.php";


// ---- FIELDS ---- 

$user = new User();

$idUser = filter_input(INPUT_GET, 'idUser', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);

$myUser = null;


// ---- GET USER ----

$myUser = $user->GetUser($idUser);
if (empty($myUser)) die;
else $myUser = $myUser[0];

switch ($myUser['permission']) {
    case 1:
        $myUser['permission'] = 'Regular User';
        break;
    case 2:
        $myUser['permission'] = 'Super User';
        break;

    default:
        die;
        break;
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
