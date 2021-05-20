<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   map.php
 * Description  :   It's the view where a map is implement and show spots as marker.
 */

require_once './navBar.php';


// ---- ERROR ----

$error = filter_input(INPUT_GET, "error", FILTER_DEFAULT, FILTER_SANITIZE_STRING);
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

    <title>FitnesSpot ─ Map</title>
</head>

<body class="h-100">

    <!-- NAV -->
    <?= GetNavBar('map') ?>

    <!-- MAIN -->
    <div class="h-90 w-100 bg-main">

        <!-- MAP -->
        <div id='map' class="p-2 w-100 h-100"></div>

    </div>
</body>

<script src="javascript/mapHome.js"></script>

</html>