<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   oneUser.php
 * Description  :   It's the view who show the information of one specifique user.
 */

require_once './navBar.php';
require_once './session.php';


// ---- SECURITY ----

$idUser = filter_input(INPUT_GET, 'idUser', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);

if (!isset($_SESSION['userId']) || ($_SESSION['userId'] != $idUser && $_SESSION['userPermission'] != '2')) {
    header('location: ./home.php');
    exit;
}


// ---- SUBMIT ----

$submit = filter_input(INPUT_POST, 'updateUser_Submit', FILTER_DEFAULT);

if (isset($submit) && $submit == 'Delete') {
    require '../controller/deleteUser.php';

    $submit = 'Disconnect';
}

if (isset($submit) && $submit == 'Disconnect') {
    session_destroy();
    $_SESSION = array();

    header('location: ./home.php');
    exit;
}

if (isset($submit) && $submit == 'Save') {
    require '../controller/updateUser.php';
}

require_once '../controller/getOneUser.php';


// ---- ERROR ----

$errorAlert;

if (isset($error)) {
    $errorAlert = '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">' . $error
        . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
}

?>

<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <?php require './header.php' ?>

    <title>FitnesSpot ─ One User</title>
</head>

<body class="h-100 bg-main">

    <!-- NAV -->
    <?= GetNavBar('') ?>

    <!-- MAIN -->
    <div class="d-flex flex-column h-90 w-100 px-6">

        <!-- HEADER -->
        <div class="d-flex justify-content-start align-items-end mt-5 mb-5">
            <p class="h2 me-3"><strong><?= $myUser['pseudo'] ?></strong></p>
            <p class="h2 text-muted text-end" style="font-size: 1rem; line-height: 1.5;"><?= $myUser['permission'] ?></p>
        </div>

        <!-- POTENTIAL ERROR -->
        <?= (isset($errorAlert)) ? $errorAlert : null; ?>

        <!-- BODY  -->
        <div class="d-flex justify-content-center align-items-center flex-wrap h-100 w-100 mb-5">

            <!-- INFO USER -->
            <div class="card rounded-10 shadow w-60">
                <div class="card-body">

                    <form action="#" method="POST">

                        <!-- Pseudo -->
                        <div class="mb-3">
                            <label for="updateUser_Pseudo" class="form-label text-primary fs-5">Pseudo </label>
                            <input id="updateUser_Pseudo" name="updateUser_Pseudo" type="text" class="form-control" value='<?= $myUser['pseudo'] ?>' require>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="updateUser_Email" class="form-label text-primary fs-5">Email </label>
                            <input id="updateUser_Email" name="updateUser_Email" type="email" class="form-control" value='<?= $myUser['email'] ?>' require>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="updateUser_Password" class="form-label text-primary fs-5">Password </label>
                            <input id="updateUser_Password" name="updateUser_Password" type="password" class="form-control" require>
                        </div>


                        <!-- ? MAYBE AN IMAGE AVATAR -->

                        <div class="d-flex justify-content-end w-100 mt-4">
                            <input id="updateUser_Submit" name="updateUser_Submit" type="submit" class="btn btn-dark w-20 me-5" value="Delete" />
                            <input id="updateUser_Submit" name="updateUser_Submit" type="submit" class="btn btn-danger w-20 me-5" value="Disconnect" />
                            <input id="updateUser_Submit" name="updateUser_Submit" type="submit" class="btn btn-primary w-20" value="Save" />
                        </div>
                    </form>

                </div>
            </div>

        </div>

    </div>
</body>


</html>