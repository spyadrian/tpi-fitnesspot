<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   signUp.php
 * Description  :   It's the view of the part that allow the user to creat an account. 
 */


require_once './navBar.php';


// ---- SUBMIT ----

$submit = filter_input(INPUT_POST, 'signUp_Submit', FILTER_DEFAULT);
if (isset($submit)) {
    require '../controller/signUp.php';
}


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

    <title>FitnesSpot ─ Sign Up</title>
</head>

<body class="h-100">

    <!-- NAV -->
    <?= GetNavBar('') ?>

    <!-- MAIN -->
    <div class="d-flex flex-column h-90 w-100 px-6 bg-main">

        <!-- HEADER -->
        <div class="d-flex justify-content-start mt-5 mb-5">
            <p class="h2"><strong>Sign Up</strong></p>
        </div>

        <!-- POTENTIAL ERROR -->
        <?= (isset($errorAlert)) ? $errorAlert : null; ?>

        <!-- BODY  -->
        <div class="d-flex align-items-center justify-content-center h-100 w-100">

            <!-- Form -->
            <form class="w-50 shadow-lg rounded-30 p-5 minw-350" action="#" method="POST">

                <!-- Pseudo -->
                <div class="mb-3">
                    <label for="signUp_Pseudo" class="form-label text-primary fs-5">Pseudo </label>
                    <input id="signUp_Pseudo" name="signUp_Pseudo" type="text" class="form-control" value='<?= isset($pseudo) ? $pseudo : '' ?>' require>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="signUp_Email" class="form-label text-primary fs-5">Email </label>
                    <input id="signUp_Email" name="signUp_Email" type="email" class="form-control" value='<?= isset($email) ? $email : '' ?>' require>
                </div>

                <!-- Pssword -->
                <div class="mb-3">
                    <label for="signUp_Password" class="form-label text-primary fs-5">Password </label>
                    <input id="signUp_Password" name="signUp_Password" type="password" class="form-control" require>
                </div>

                <!-- RepeatPassword -->
                <div class="mb-3">
                    <label for="signUp_RepeatPassword" class="form-label text-primary fs-5">Confirm Password </label>
                    <input id="signUp_RepeatPassword" name="signUp_RepeatPassword" type="password" class="form-control" require>
                </div>

                <!-- ? MAYBE AN IMAGE AVATAR -->

                <input id="signUp_Submit" name="signUp_Submit" type="submit" class="btn btn-primary mt-4 w-100" value="Sign Up" />
            </form>

        </div>
    </div>
</body>

</html>