<?php
require_once '../controller/getSpotsAsCards.php';
?>

<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnesSpot</title>

    <!-- Config -->
    <script src="../config/config.inc.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">

    <!-- Personal Style -->
    <link href="../asset/css/styles.css" rel="stylesheet" />

    <!-- Using API "MapBox" -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css" type="text/css" />

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
</head>

<body class="h-100">

    <!-- NAV -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-lg w-100 px-5">
        <div class="container-fluid">
            <a class="navbar-brand w-33" href="#">FitnesSpot</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Link To Other Pages -->
            <div class="collapse navbar-collapse w-66" id="navbarNav">

                <ul class="navbar-nav w-50 d-flex justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link" href="#">Spots</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Maps</a>
                    </li>
                </ul>

                <ul class="navbar-nav w-50 d-flex justify-content-end">
                    <li class="nav-item">
                        <a href="#" type="button" class="btn btn-link me-2">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" type="button" class="btn btn-primary me-3">Sign In</a>
                    </li>
                </ul>

            </div>

        </div>
    </nav>

    <!-- MAIN -->
    <div class="d-flex h-90 w-100 flex-column">

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
        <div class="d-flex justify-content-around align-items-center flex-wrap w-100 mt-5 px-5">

            <!-- Print all cards -->
            <?php foreach ($someSpots as $s) : ?>

                <div class="card rounded-10 shadow mb-5" style="width: 18rem; min-height: 300px;">

                    <img src="../asset/image/no-image-found.jpg" class="card-img-top rounded-10" alt="spot's image">

                    <div class="card-body">

                        <h5 class="card-title mb-0 text-wrapper-225"><?= empty($s['name']) ? 'Spot Nameless' : $s['name'] ?></h5>

                        <small class="text-muted d-flex justify-content-around w-50">

                            <!-- Star Algo -->
                            <?php

                            $grade = $s['grade'];
                            $starNumber = 0;

                            while ($grade >= 1) {
                                echo '<i class="bi bi-star-fill"></i>';
                                $grade--;
                                $starNumber++;
                            }

                            if ($grade >= 0.5) {
                                echo '<i class="bi bi-star-half"></i>';
                                $starNumber++;
                            }

                            while ($starNumber < 5) {
                                echo '<i class="bi bi-star"></i>';
                                $starNumber++;
                            }

                            ?>

                        </small>

                        <p class="card-text mt-2"><?= empty($s['detail']) ? 'Spot Without Any Detail.' : $s['detail'] ?></p>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>


    </div>
</body>

<!-- Script Js -->
<script src="JS/mapHome.js"></script>

</html>