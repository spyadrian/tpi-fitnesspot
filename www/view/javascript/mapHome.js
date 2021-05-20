/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   mapHome.js
 * Description  :   It's where all the functioning of the implement of the map of home are.
 */

mapboxgl.accessToken = MAPBOX_ACCESSTOKEN;

// ---  FILEDS  ---

let spotsMarkers = [];
let spotsIdAlreadyShow = [];

let lngUSer = 0;
let latUser = 0;

let getUserLocaliation = false;

// Map
let map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/streets-v11",
    center: [6.1028, 46.2111],
    zoom: 10,
});

// Add geolocate control to the map.
let geolocate = new mapboxgl.GeolocateControl({
    positionOptions: {
        enableHighAccuracy: true,
        watchPosition: true,
    },
});
map.addControl(geolocate);

// ---  EVENTS  ---

$("document").ready(GetSpots());

/**
 * @summary     When the user give is position we save his coordinates.
 */
geolocate.on("geolocate", function (position) {
    getUserLocaliation = true;
    latUser = position.coords.latitude;
    lngUSer = position.coords.longitude;
});

/**
 * @summary     Event, when the user stops moving the map
 */
map.on("moveend", function () {
    GetSpots();
});

// ---  FUNCTS  ---

/**
 * @summary     Get the list of all the spot
 */
function GetSpots() {
    let bboxScreen = map.getBounds();

    let latMin = bboxScreen._sw.lat;
    let latMax = bboxScreen._ne.lat;

    let lonMin = bboxScreen._sw.lng;
    let lonMax = bboxScreen._ne.lng;

    $.ajax({
        url: "../controller/getSpotsAsMarkers.php",
        type: "POST",
        data: {
            latMin: latMin,
            latMax: latMax,
            lonMin: lonMin,
            lonMax: lonMax,
        },
        success: function (result) {
            let spots = JSON.parse(result);
            ShowSpots(spots);
        },
    });
}

/**
 * @summary     Show all the spots in the map.
 * @param       {object[]}    spots   List of all spot represented by an object
 */
function ShowSpots(spots) {
    for (const spot of spots) {
        // if already show
        if (spotsIdAlreadyShow.includes(spot.idSpot)) break;

        // create div marker
        let divMarker = document.createElement("div");
        divMarker.classList.add(["d-flex", "flex-column"]);
        divMarker.innerHTML = '<i class="bi bi-geo-alt-fill" style="font-size: 1.5rem;"></i>';

        spotsMarkers.push({
            id: spot.idSpot,
            markerComponent: new mapboxgl.Marker(divMarker)
                .setLngLat([spot.lon, spot.lat])
                .setPopup(new mapboxgl.Popup({ offset: 25, closeOnClick: true, closeOnMove: true, focusAfterOpen: true }).setHTML(GetHTMLPopUp(spot)))
                .addTo(map),
        });

        spotsIdAlreadyShow.push(spot.idSpot);
    }

    DeleteUselessMarker();
}

/**
 * @summary     Delete markers that are not visible on the map
 */
function DeleteUselessMarker() {
    let bboxScreen = map.getBounds();

    let latMin = bboxScreen._sw.lat;
    let latMax = bboxScreen._ne.lat;

    let lngMin = bboxScreen._sw.lng;
    let lngMax = bboxScreen._ne.lng;

    for (const marker of spotsMarkers) {
        if (
            marker["markerComponent"]._lngLat.lat > latMax ||
            marker["markerComponent"]._lngLat.lat < latMin ||
            marker["markerComponent"]._lngLat.lng > lngMax ||
            marker["markerComponent"]._lngLat.lng < lngMin
        ) {
            marker["markerComponent"].remove();

            let index = spotsIdAlreadyShow.indexOf(marker["id"]);
            spotsIdAlreadyShow.splice(index, 1);
        }
    }
}

/**
 * @summary     Create the HTML of the popup, with the right information.
 * @param       {object}    spot    An object spot, with all his properties.
 * @returns     {string}    the HTML of the popup in string.
 */
function GetHTMLPopUp(spot) {
    let HTML = "";
    HTML += '<div class="d-flex flex-column justify-content-center align-items-center">';
    HTML += '<h6 class="text-wrapper-225 mb-0"><strong>' + spot.name + "</strong></h6>";
    HTML += '<small class="text-muted mb-4 text-center">' + spot.address + "</small><br>";
    HTML +=
        '<a href="oneSpot.php?idSpot=' +
        spot.idSpot +
        '" type="button" class="btn btn-primary btn-sm w-50" style="min-width: 100px">More details</a>';
    HTML += "</div>";

    return HTML;
}
