<?php

/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   allUsers.php
 * Description  :   It's the view who show all the users.
 */

require_once './navBar.php';
require_once './session.php';


// ---- SECURITY ----

SetSessionLevel(2);

?>

<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <?php require './header.php' ?>

    <title>FitnesSpot ─ All Users</title>
</head>

<body class="h-100 bg-main">

    <!-- NAV -->
    <?= GetNavBar('') ?>

    <!-- MAIN -->
    <div class="d-flex h-90 w-100 flex-column px-6">

        <!-- HEADER -->
        <div class="d-flex justify-content-start mt-5 mb-5">
            <p class="h2"><strong>All Users</strong></p>
        </div>

        <!-- FILTRE & SEARCH -->
        <div>
            <div class="d-flex justify-content-between">

                <!-- Filter -->
                <div class="input-group w-50" onclick="SetFilterPseudo()">
                    Filter Pseudo <i id="arrowFilterPseudo" class="bi bi-arrow-down ms-2"></i>
                </div>

                <!-- Search Bar -->
                <div class="input-group w-50">
                    <input id="searchBar" class="form-control border-0 text-end pe-3 bg-main" type="search" placeholder="Search" aria-label="Search" onkeyup="OnResearch()" />
                    <button type="button" class="btn bg-main" style="width: 75px;" onclick="OnResearch()">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

            </div>

            <hr class="mt-2">
        </div>

        <!-- LIST USER -->
        <div id="containerUsers" class="d-flex flex-column justify-content-around mt-5">

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

<script src="./javascript/paginationUsers.js"></script>

</html>