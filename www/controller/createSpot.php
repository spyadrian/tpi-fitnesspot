<?php

require_once "../model/Spot.php";
require_once "../model/Media.php";
require_once "../model/User.php";


// ---- CONST ----

$MAX_SIZE_FILE = 10000000; // 10'000'000 octets
$MAX_SIZE_POST = 100000000; // 100'000'000 octets

$NO_IMAGE = 'no-image-found.jpg';
$REP_IMAGE = '../asset/image/';


// ---- FIELDS ---- 

$spot = new Spot();
$media = new Media();
$user = new User();

$name = filter_input(INPUT_POST, 'addSpot_Name', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$detail = filter_input(INPUT_POST, 'addSpot_Detail', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
$lat = filter_input(INPUT_POST, "addSpot_Lat", FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_FLOAT);
$lon = filter_input(INPUT_POST, "addSpot_Lon", FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_FLOAT);

$myAdmin = null;
$files = null;

if (isset($_FILES['addSpot_File'])) {
    $files = reArrayFiles($_FILES['addSpot_File']);

    if ($files[0]['name'] == null)
        $files = array();
}


// ---- FILTRE DATA ----

$error = null;
$spot_size = null;

foreach ($files as $f) {
    $spot_size += $f['size'];

    if (is_numeric(strpos($f['type'], "image")) == false) {
        $error = "One of your files isn't an image but is an : " . $f["type"];
        break;
    }

    if ($f['size'] >= $MAX_SIZE_FILE) {
        $error = "One of your files exceeds the size limit of " . $MAX_SIZE_FILE . " bytes.";
        break;
    }
}

if ($spot_size >= $MAX_SIZE_POST)
    $error = "Your files exceeds the size limit of " . $MAX_SIZE_POST . " bytes.";

if ($lon == -1 && $lat == -1)
    $error = "You need to set the position of the spot.";

if (empty($files))
    $error = "You need to set an image for the spot.";

// return to spot with the error
if ($error != null) {
    header("location: ../view/addSpot.php?error=" . $error);
    exit;
}


// ---- CREATE POST & IMG ----

// get address 
$resultAPI = CallGeocodingAPI($lat, $lon);
$address = $resultAPI['features'][0]['place_name'];

$db = DBConnection::getConnection();
$db->BeginTransaction();

try {
    $idSpot = $spot->AddSpot(1, $name, $detail, $address, $lat, $lon);

    if (is_numeric($idSpot)) {

        foreach ($files as $f) {
            $fileExtention = pathinfo($f['name'], PATHINFO_EXTENSION);
            $filename = uniqid("", true) . '.' . $fileExtention;

            $idMedia = $media->AddMedia($idSpot, $filename, $f['type'], $f['size']);

            if (is_numeric($idMedia)) {

                move_uploaded_file($f['tmp_name'], $REP_IMAGE . $filename);
            }
        }
    }

    $db->commit();
} catch (PDOException $e) {
    $db->rollBack();
    $result = '<pre>Erreur : ' . $e->getMessage() . '</pre>';
}


// ---- SEND MAIL ----

$myAdmin = $user->GetUserAdmin();
mail($myAdmin[0]['email'], "A Spot has been added !", "Hi, \n A new Spot is up, with the id : " . $idSpot);


// ---- Return to Home ----

header("location: ../view/allSpots.php");
exit;


// ---- Fonct ----

function CallGeocodingAPI($lat, $lon)
{
    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' . $lon . ',' . $lat . '.json?language=fr&types=address&limit=1&access_token=' . MAPBOX_ACCESSTOKEN;

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url); // Set the url
    curl_setopt($curl, CURLOPT_HTTPGET, true); // set the type of the call to GET
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // set to true to get the result with the exect 
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json'
    ));

    $result = curl_exec($curl); // Execute

    curl_close($curl); // Closing

    return json_decode($result, true);
}

// credit : php.net
function reArrayFiles($file_spot)
{
    $file_ary = array();
    $file_count = count($file_spot['name']);
    $file_keys = array_keys($file_spot);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_spot[$key][$i];
        }
    }

    return $file_ary;
}
