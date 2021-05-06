<?php
require_once './navBar.php';
require_once '../controller/getSpotsAsCards.php';
?>

<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <?php require './header.php' ?>

    <title>FitnesSpot ─ Home</title>
</head>

<body class="h-100">

    <!-- NAV -->
    <?= GetNavBar('home'); ?>

    <!-- MAIN -->
    <div class="d-flex h-90 w-100 flex-column bg-main">

        <!-- BANNER -->
        <div class="d-flex justify-content-center align-items-center w-100 banner-bg mb-3" style="min-height: 400px;">

            <!-- Search Bar -->
            <div class="w-33">
                <div class="input-group shadow-lg">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" />
                    <button type="button" class="btn btn-primary" style="width: 100px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- CARDS -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 w-100 mt-5 px-6">

            <!-- Print all cards -->
            <?php foreach ($someSpots as $s) : ?>

                <div class="card rounded-10 shadow mb-5" style="width: 18rem; min-height: 300px;">

                    <img src="../asset/image/<?= $s['media_fileName'] ?>" class="card-img-top rounded-top-10" alt="spot's image" style="height: 160px;">

                    <div class="card-body">

                        <h5 class="card-title mb-1 text-wrapper-225"><?= empty($s['name']) ? 'Spot Nameless' : $s['name'] ?></h5>

                        <small class="text-muted d-flex justify-content-between w-33"><?= ConvertGradeToStar($s['grade']); ?></small>

                        <!-- Maybe make a WRAPPER -->
                        <p class="card-text mt-2"><?= empty($s['detail']) ? 'This spot dont have any detail.' : $s['detail'] ?></p>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>


    </div>
</body>

</html>