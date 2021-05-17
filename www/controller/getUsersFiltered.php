<?php

require_once "../model/User.php";


// ---- FIELDS ---- 

$NO_IMAGE = 'no-image-found.jpg';

$user = new User();

$filter = filter_input(INPUT_POST, 'filter', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$from = filter_input(INPUT_POST, 'from', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
$offset = filter_input(INPUT_POST, 'offset', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);

$someUsers = [];


// ---- GET SPOT ----

if (empty($filter)) {
    $someUsers = $user->GetUsers($from, $offset);
} else {
    $someUsers = $user->GetUsersWithFilter($filter, $from, $offset);
}


// ---- CREATE HTML ---- 

foreach ($someUsers as $u) : ?>

    <div class="card d-flex flex-row shadow w-100 mb-5" style="height: 100px;">
        <div class="card-body d-flex px-5">

            <!-- Pseudo -->
            <div class="d-flex flex-column justify-content-center align-items-start w-33">
                <h3><?= $u['pseudo'] ?></h3>
            </div>

            <!-- Email -->
            <div class="d-flex justify-content-center align-items-center w-33" href="#">
                <p class="mb-0" onclick="SetResearch('<?= $u['email'] ?>')"><?= $u['email'] ?></p>
            </div>

            <!-- Modify -->
            <div class="d-flex justify-content-end align-items-center w-33">
                <a href="./oneUser.php?idUser=<?= $u['idUser'] ?>">
                    <i class="bi bi-pencil-square" style="font-size: 2rem; color: orange;"></i>
                </a>
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
