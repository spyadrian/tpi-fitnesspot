<?php
require_once './navBar.php';

$submit = filter_input(INPUT_POST, 'signIn_Submit', FILTER_DEFAULT);
if (isset($submit)) {
    require '../controller/signIn.php';
}

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

    <title>FitnesSpot ─ Sign In</title>
</head>

<body class="h-100">

    <!-- NAV -->
    <?= GetNavBar('') ?>

    <!-- MAIN -->
    <div class="d-flex flex-column h-90 w-100 px-6 bg-main">

        <!-- HEADER -->
        <div class="d-flex justify-content-start mt-5 mb-5">
            <p class="h2"><strong>Sign In</strong></p>
        </div>

        <!-- POTENTIAL ERROR -->
        <?= (isset($errorAlert)) ? $errorAlert : null; ?>

        <!-- BODY  -->
        <div class="d-flex align-items-center justify-content-center h-100 w-100">

            <!-- Form -->
            <form class="w-50 shadow-lg rounded-30 p-5 minw-350" action="#" method="POST">

                <!-- Email -->
                <div class="mb-3">
                    <label for="signIn_Email" class="form-label text-primary fs-5">Email </label>
                    <input id="signIn_Email" name="signIn_Email" type="email" class="form-control" value='<?= isset($email) ? $email : '' ?>' require>
                </div>

                <!-- Pssword -->
                <div class="mb-3">
                    <label for="signIn_Password" class="form-label text-primary fs-5">Password </label>
                    <input id="signIn_Password" name="signIn_Password" type="password" class="form-control" require>
                </div>

                <input id="signIn_Submit" name="signIn_Submit" type="submit" class="btn btn-primary mt-4 w-100" value="Sign In" />
            </form>

        </div>
    </div>
</body>

</html>