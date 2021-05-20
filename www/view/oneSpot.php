<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   oneSpot.php
 * Description  :   It's the view who show the information of one particular spot.
 */


require_once './navBar.php';
require_once './session.php';


// ---- SECURITY ----

$actionAllowed = true;
$adminActionAllowed = false;
if (!isset($_SESSION['userId'])) {
    $actionAllowed = false;

    if (isset($_SESSION['userPermission']) && $_SESSION['userPermission'] > 2)
        $adminActionAllowed = true;
}


// ---- SUBMIT ----

$submit = filter_input(INPUT_POST, 'addComment_Submit', FILTER_DEFAULT);

if (isset($submit) && $submit == 'Add Comment') {

    SetSessionLevel(1, './signIn.php');
    require_once '../controller/addComment.php';
}

require_once '../controller/getOneSpot.php';


// ---- ERROR ----

$errorAlert;

if (isset($error)) {
    $errorAlert = '<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">' . $error
        . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

?>

<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <?php require './header.php' ?>

    <title>FitnesSpot ─ One Spot</title>
</head>

<body>

    <!-- NAV -->
    <?= GetNavBar('allSpots') ?>

    <!-- MAIN -->
    <div class="d-flex flex-column h-90 w-100 px-6 bg-main">

        <!-- HEADER -->

        <?php if ($adminActionAllowed) : ?>

            <div class="d-flex justify-content-between align-items-center mt-5 mb-5">

                <div class="d-flex justify-content-start align-items-end">
                    <p class="h2 me-3"><strong><?= empty($mySpot['name']) ? 'Spot Nameless' : $mySpot['name'] ?></strong></p>
                    <p class="h2 text-muted text-end" style="font-size: 1rem; line-height: 1.5;"><?= ($mySpot['validate'] == '0') ? 'Not Official' : 'Official' ?></p>
                </div>

                <div class="d-flex justify-content-end align-items-center">
                    <a href="../controller/valideSpot.php?idSpot=<?= $mySpot['idSpot'] ?>" type="button" class="btn btn-primary me-3 h-100">Valide</a>
                    <a href="../controller/deleteSpot.php?idSpot=<?= $mySpot['idSpot'] ?>" type="button" class="btn btn-danger h-100">Delete</a>
                </div>

            </div>

        <?php else : ?>

            <div class="d-flex justify-content-start align-items-end mt-5 mb-5">
                <p class="h2 me-3"><strong><?= empty($mySpot['name']) ? 'Spot Nameless' : $mySpot['name'] ?></strong></p>
                <p class="h2 text-muted text-end" style="font-size: 1rem; line-height: 1.5;"><?= ($mySpot['validate'] == '0') ? 'Not Official' : 'Official' ?></p>
            </div>

        <?php endif; ?>


        <!-- BODY -->
        <div class="d-flex flex-column w-100">

            <!-- UPPER BODY  -->
            <div class="d-flex justify-content-between align-items-start flex-wrap h-100 w-100 mb-5">

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

                        <div class="d-flex flex-row justify-content-between align-items-start">
                            <small id="containerStar" class="d-flex justify-content-between w-33" data-bs-toggle="modal" data-bs-target="#exampleModal"><?= ConvertGradeToStar($mySpot['averageRate']); ?></small>

                            <!-- Only if action is alowed -->
                            <p id="containerLike" class="card-text text-muted" <?= $actionAllowed ? 'onclick="AddLike()"' : '' ?> <?= empty($myLike) ? '' : 'style="color: #ff5454 !important;"' ?>>
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
                <div class="d-flex w-60 h-100 rounded-10">

                    <div id='map' class="w-100 h-100 rounded-10" style="min-height: 700px;"></div>

                </div>

                <!-- hidden lat/lon & id -->
                <input id="mySpotLat" name="mySpotLat" type="hidden" value="<?= $mySpot['lat'] ?>">
                <input id="mySpotLon" name="mySpotLon" type="hidden" value="<?= $mySpot['lon'] ?>">
                <input id="mySpotId" name="mySpotId" type="hidden" value="<?= $mySpot['idSpot'] ?>">
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

                                <!-- Only if action is alowed -->
                                <?php if ($actionAllowed) : ?>
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="SendRate()">Send rate</button>
                                <?php else : ?>
                                    <a href="./signIn.php" type="button" class="btn btn-primary">Send rate</a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- POTENTIAL ERROR -->
            <?= (isset($errorAlert)) ? $errorAlert : null; ?>


            <!-- LOWER BODDY -->
            <div class="d-flex justify-content-between align-items-start w-100 mb-5">

                <!-- COMMENTS -->
                <div class="d-flex flex-column w-60">

                    <?php foreach ($myComments as $key => $c) : ?>

                        <div class="card shadow mb-4">
                            <div class="card-body">

                                <?php if ($adminActionAllowed) : ?>

                                    <div class="d-flex justify-content-between">
                                        <p class="card-text mb-2"><b><?= isset($c['pseudo']) ? $c['pseudo'] : '[deleted user]' ?></b></p>
                                        <a href="../controller/deleteComment.php?idComment=<?= $c['idComment'] ?>&idSpot=<?= $mySpot['idSpot'] ?>" type="button" class="btn btn-danger">Delete</a>
                                    </div>

                                <?php else : ?>

                                    <p class="card-text mb-2"><b><?= isset($c['pseudo']) ? $c['pseudo'] : '[deleted user]' ?></b></p>

                                <?php endif; ?>

                                <p class="card-text mb-0"><?= $c['content'] ?></p>

                                <div class="d-flex justify-content-end">
                                    <p class="card-text text-muted" style="font-size: 0.8rem;">Create the : <?= $c['createdAt'] ?></p>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                    <?php if (empty($myComments)) echo 'Sorry but there are no comments yet. Add one!'; ?>

                </div>

                <!-- ADD COMMENT -->
                <div class="d-flex flex-column align-items-end w-30">
                    <form class="w-100 shadow-lg rounded-30 p-5" action="#" method="POST">

                        <h4><strong>Add Comment </strong></h4>

                        <!-- Content -->
                        <div class="mb-2 mt-2">
                            <label for="addComment_Content" class="form-label text-primary fs-5">Content</label>
                            <textarea id="addComment_Content" name="addComment_Content" class="form-control" rows="3"></textarea>
                        </div>

                        <input id="addComment_IdSpot" name="addComment_IdSpot" type="hidden" value="<?= $mySpot['idSpot'] ?>">

                        <input id="addComment_Submit" name="addComment_Submit" type="submit" class="btn btn-primary mt-4 w-100" value="Add Comment" />
                    </form>
                </div>


            </div>

        </div>
    </div>
</body>

<script src="javascript/mapOneSpot.js"></script>
<script src="javascript/likesVotesManager.js"></script>

</html>