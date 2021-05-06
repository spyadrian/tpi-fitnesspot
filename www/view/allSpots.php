<?php
require_once './navBar.php';
?>

<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <?php require './header.php' ?>

    <title>FitnesSpot ─ All Spots</title>
</head>

<body class="h-100">

    <!-- NAV -->
    <?= GetNavBar('allSpots') ?>

    <!-- MAIN -->
    <div class="d-flex h-90 w-100 flex-column px-6 bg-main">

        <!-- HEADER -->
        <div class="d-flex justify-content-between mt-5 mb-5">
            <p class="h2"><strong>All Spots</strong></p>
            <button type="button" class="btn btn-primary" style="width: 150px; font-weight: 600;">Add Spot</button>
        </div>

        <!-- FILTRE & SEARCH -->
        <div>
            <div class="d-flex justify-content-between">

                <!-- Filter -->
                <div class="input-group w-50">
                    Some Filter Todo
                </div>

                <!-- Search Bar -->
                <div class="input-group w-50">
                    <input id="searchBar" class="form-control border-0 text-end pe-3 bg-main" type="search" placeholder="Search" aria-label="Search" onkeyup="GetSpotsFiltered()" />
                    <button type="button" class="btn bg-main" style="width: 75px;" onclick="GetSpotsFiltered()">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

            </div>

            <!-- ? Width a bit bigger -->
            <hr class="mt-2">
        </div>

        <!-- LIST SPOTS -->
        <div id="containerSpots" class="d-flex flex-column justify-content-around mt-5">

            <!-- Here will be print all cards -->

        </div>

    </div>
</body>

<script>
    $('document').ready(GetSpotsFiltered());

    function GetSpotsFiltered() {

        filter = document.getElementById('searchBar').value;
        limit = 6;

        $.ajax({
            url: "../controller/getSpotsFiltered.php",
            type: "POST",
            data: {
                filter: filter,
                limit: limit,
            },
            success: function(result) {
                console.log("OK !");

                document.getElementById('containerSpots').innerHTML = result;
            }
        });
    }
</script>

</html>