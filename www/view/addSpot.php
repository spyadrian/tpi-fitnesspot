<?php
require_once './navBar.php';

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

    <title>FitnesSpot ─ Add Spot</title>
</head>

<body class="h-100">

    <!-- NAV -->
    <?= GetNavBar('allSpots') ?>

    <!-- MAIN -->
    <div class="d-flex flex-column h-90 w-100 px-6 bg-main">

        <!-- HEADER -->
        <div class="d-flex justify-content-start mt-5 mb-5">
            <p class="h2"><strong>Add Spot</strong></p>
        </div>

        <!-- POTENTIAL ERROR -->
        <?= (isset($errorAlert)) ? $errorAlert : null; ?>

        <!-- BODY  -->
        <div class="d-flex justify-content-center align-items-center flex-wrap h-90 w-100">

            <!-- Form -->
            <div class="d-flex flex-column align-items-end justify-content-end mr-lg-30 w-40 minw-350">
                <form class="w-50 shadow-lg rounded-30 p-5 minw-350" action="../controller/createSpot.php" method="POST" enctype="multipart/form-data">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="addSpot_Name" class="form-label text-primary fs-5">Name</label>
                        <input id="addSpot_Name" name="addSpot_Name" type="text" class="form-control">
                    </div>

                    <!-- Detail -->
                    <div class="mb-3">
                        <label for="addSpot_Detail" class="form-label text-primary fs-5">Detail</label>
                        <textarea id="addSpot_Detail" name="addSpot_Detail" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Image -->
                    <div class="mb-3">
                        <label for="addSpot_File" class="form-label text-primary fs-5">Images</label>
                        <input id="addSpot_File" name="addSpot_File[]" type="file" class="form-control" accept="image/*" multiple>
                    </div>

                    <!-- Data Lng / Lat -->
                    <input type="hidden" id="addSpot_Lat" name="addSpot_Lat" value="-1">
                    <input type="hidden" id="addSpot_Lng" name="addSpot_Lon" value="-1">

                    <input type="submit" class="btn btn-primary mt-4 w-100" value="Add Spot" />
                </form>
            </div>

            <!-- Map -->
            <div class="d-flex flex-column justify-content-start w-40 h-50 ml-lg-30 minw-350">
                <div id='map' class="w-50 h-100 minw-350"></div>

                <!-- Text Lng / Lat -->
                <div class="w-50 d-flex justify-content-around">
                    <p class="fs-6 text-muted" id="str_lat">Lat : 0</p>
                    <p class="fs-6 text-muted" id="str_lng">Lng : 0</p>
                </div>
            </div>

        </div>
    </div>
</body>

<script src="javascript/mapAddSpot.js"></script>

</html>