<?php
require_once './navBar.php';
require_once '../controller/getOneSpot.php';

?>

<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <?php require './header.php' ?>

    <title>FitnesSpot ─ One Spot</title>
</head>

<body class="h-100">

    <!-- NAV -->
    <?= GetNavBar('allSpots') ?>

    <!-- MAIN -->
    <div class="d-flex flex-column h-90 w-100 px-6 bg-main">

        <!-- POTENTIAL ERROR -->
        <?= (isset($errorAlert)) ? $errorAlert : null; ?>

        <!-- HEADER -->
        <div class="d-flex justify-content-start mt-5 mb-5">
            <p class="h2"><strong><?= empty($mySpot['name']) ? 'Spot Nameless' : $mySpot['name'] ?></strong></p>
        </div>

        <!-- BODY  -->
        <div class="d-flex justify-content-between align-items-start flex-wrap h-90 w-100">


            <!-- Print card -->
            <div class="card rounded-10 shadow w-30">

                <div id="carouselImageSpot" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        <?php foreach ($myMedias as $key => $m) : ?>

                            <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>">
                                <img src="../asset/image/<?= $m['fileName'] ?>" class="card-img-top rounded-top-10" alt="spot's image">
                            </div>

                        <?php endforeach; ?>

                    </div>

                    <a class="carousel-control-prev" type="button" data-bs-target="#carouselImageSpot" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next" type="button" data-bs-target="#carouselImageSpot" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>

                <div class="card-body">

                    <!-- TODO : VOTE WITH AN MODAL -->
                    <!-- TODO : LIKE ON CLICK -->
                    <div class="d-flex flex-row justify-content-between align-items-start">
                        <small class="d-flex justify-content-between w-33" data-bs-toggle="modal" data-bs-target="#exampleModal"><?= ConvertGradeToStar($mySpot['averageRate']); ?></small>
                        <p class="card-text text-muted" onclick="AddLike()" <?= empty($myLike) ? '' : 'style="color: #ff5454 !important;"' ?>>
                            <?= $mySpot['numberLike'] ?>
                            <i class="bi bi-heart-fill" style="font-size: 0.9rem;"></i>
                        </p>
                    </div>

                    <p class="card-text mt-2"><b><?= $mySpot['address'] ?></b></p>

                    <p class="card-text mt-2"><?= empty($mySpot['detail']) ? 'This spot dont have any detail.' : $mySpot['detail'] ?></p>

                    <div class="d-flex justify-content-end">
                        <p class="card-text text-muted" style="font-size: 0.8rem;">Create the : <?= $mySpot['createdAt'] ?></p>
                    </div>

                </div>
            </div>


            <!-- Map -->
            <div class="w-60 h-100 rounded-10">

                <div id='map' class="w-100 h-100 rounded-10"></div>

            </div>

            <!-- hidden lat/lon & id -->
            <input id="mySpotLat" name="mySpotLat" type="hidden" value="<?= $mySpot['lat'] ?>">
            <input id="mySpotLon" name="mySpotLon" type="hidden" value="<?= $mySpot['lon'] ?>">

            <input id="mySpotId" name="mySpotId" type="hidden" value="<?= $mySpot['idSpot'] ?>">
            <input id="myUserId" name="myUserId" type="hidden" value="<?= $mySpot['idUser'] ?>">

            <input id="myUserLiked" name="myUserLiked" type="hidden" value="<?= $myLike ?>">


            <!-- Modal for vote -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Rate your spot !</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body container">
                            <!-- Credit to : Mert Cukuren  -->
                            <?php include './ratingStar.php' ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="SendRate()">Send rate</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

<script src="javascript/mapOneSpot.js"></script>
<script src="javascript/likesVotesManager.js"></script>

</html>