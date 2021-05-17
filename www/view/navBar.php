<?php

function GetNavBar($activePage)
{
    return '

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow w-100 px-6">
        <div class="container-fluid px-0">
            <a class="navbar-brand w-33" href="./home.php">FitnesSpot</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Link To Other Pages -->
            <div class="collapse navbar-collapse w-66" id="navbarNav">

                <ul class="navbar-nav w-50 d-flex justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link ' . (($activePage == 'home') ? 'active" aria-current="page"' : "\"") . ' href="./home.php">Home</a>
                    </li>
                    <li class="nav-item mx-5">
                        <a class="nav-link ' . (($activePage == 'allSpots') ? 'active" aria-current="page"' : "\"") . '" href="./allSpots.php">Spots</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ' . (($activePage == 'map') ? 'active" aria-current="page"' : "\"") . '" href="./map.php">Maps</a>
                    </li>
                </ul>

                <ul class="navbar-nav w-50 d-flex justify-content-end">
                    <li class="nav-item">
                        <a href="./signUp.php" type="button" class="btn btn-link me-2">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a href="./signIn.php" type="button" class="btn btn-primary">Sign In</a>
                    </li>
                </ul>

            </div>

        </div>
    </nav>';
}
