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
            <a href="addSpot.php"><button type="button" class="btn btn-primary align-middle" style="width: 150px; font-weight: 600;">Add Spot</button></a>
        </div>

        <!-- FILTRE & SEARCH -->
        <div>
            <div class="d-flex justify-content-between">

                <!-- Filter -->
                <div class="input-group w-50">
                    <!-- Only Popular -->
                </div>

                <!-- Search Bar -->
                <div class="input-group w-50">
                    <input id="searchBar" class="form-control border-0 text-end pe-3 bg-main" type="search" placeholder="Search" aria-label="Search" onkeyup="OnResearch()" />
                    <button type="button" class="btn bg-main" style="width: 75px;" onclick="OnResearch()">
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

        <!-- PAGINATION -->
        <nav class="d-flex justify-content-center" aria-label="Page navigation">
            <ul id="containerButtons" class="pagination shadow mb-5">
                <li id="pageButtonPrevious" class="page-item not-disabled" onclick="PreviousPage()">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <li id="pageMinButton" class="page-item" onclick="SetPage(document.getElementById('pageMinButton').firstChild.innerHTML)"><a class="page-link" href="#">0</a></li>

                <li id="pageSeparator1" class="page-item disabled"><a class="page-link" href="#">...</a></li>

                <li id="pageButton1" class="page-item" onclick="SetPage(document.getElementById('pageButton1').firstChild.innerHTML)"><a class="page-link" href="#">0</a></li>
                <li id="pageButton2" class="page-item" onclick="SetPage(document.getElementById('pageButton2').firstChild.innerHTML)"><a class="page-link" href="#">0</a></li>
                <li id="pageButton3" class="page-item" onclick="SetPage(document.getElementById('pageButton3').firstChild.innerHTML)"><a class="page-link" href="#">0</a></li>

                <li id="pageSeparator2" class="page-item disabled"><a class="page-link" href="#">...</a></li>

                <li id="pageMaxButton" class="page-item" onclick="SetPage(document.getElementById('pageMaxButton').firstChild.innerHTML)"><a class="page-link" href="#">0</a></li>

                <li id="pageButtonNext" class="page-item not-disabled" onclick="NextPage()">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</body>

<script src="./javascript/paginationSpots.js"></script>

</html>