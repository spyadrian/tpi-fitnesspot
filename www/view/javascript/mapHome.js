mapboxgl.accessToken = MAPBOX_ACCESSTOKEN;

// ---  FILEDS  ---

let spotsMarkers = [];
let spotsIdAlreadyShow = [];

let routeSource = [];
let routeLayer = [];

let infoParcours = "";

let lstPopups = [];

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
            console.log(spots);
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
        divMarker.innerHTML = '<i class="bi bi-geo-alt-fill" style="font-size: 1.5rem;"></i>';

        divMarker.onclick = function () {
            if (getUserLocaliation) GenerateItinerary(lngUSer, latUser, parseFloat(spot.lon), parseFloat(spot.lat));
        };

        // divMarker.onmouseenter = function () {
        //     let html = spot.name != "" ? spot.name : "Spot Nameless";

        //     lstPopups.push(
        //         new mapboxgl.Popup({ closeOnClick: true })
        //             .setLngLat([parseFloat(spot.lon), parseFloat(spot.lat)])
        //             .setHTML(html)
        //             .addTo(map)
        //     );
        // };

        spotsMarkers.push({
            id: spot.idSpot,
            markerComponent: new mapboxgl.Marker(divMarker).setLngLat([spot.lon, spot.lat]).addTo(map),
        });

        spotsIdAlreadyShow.push(spot.idSpot);
    }

    // for (let i = 0; i < toilets.length; i++) {
    //     if (toiletsIdAlreadyShow.includes(toilets[i]["id"]) == false) {
    //         toiletsIdAlreadyShow.push(toilets[i]["id"]);

    //         let divMarker = document.createElement("div");
    //         divMarker.className = "spotToilet";

    //         divMarker.onclick = function () {
    //             if (getUserLocaliation) {
    //                 GenerateItinerary(lngUSer, latUser, parseFloat(toilets[i]["lng"]), parseFloat(toilets[i]["lat"]));
    //                 document.getElementById("course").style.display = "block";
    //             }
    //         };

    //         divMarker.onmouseenter = function () {
    //             let html = toilets[i]["name"] != "" ? toilets[i]["name"] + "<br>" : "";
    //             html += toilets[i]["wheelChair"] == "0" ? "" : '<i class="fa fa-wheelchair fa-lg" aria-hidden="true"></i>';

    //             if (html != "") {
    //                 lstPopups.push(
    //                     new mapboxgl.Popup({ closeOnClick: true })
    //                         .setLngLat([parseFloat(toilets[i]["lng"]), parseFloat(toilets[i]["lat"])])
    //                         .setHTML(html)
    //                         .addTo(map)
    //                 );
    //             }
    //         };

    //         divMarker.onmouseleave = function () {
    //             for (let i = 0; i < lstPopups.length; i++) {
    //                 lstPopups[i].remove();
    //             }
    //         };

    //         toiletsMarkers.push({
    //             id: toilets[i]["id"],
    //             markerComponent: new mapboxgl.Marker(divMarker).setLngLat([toilets[i]["lng"], toilets[i]["lat"]]).addTo(map),
    //         });
    //     }
    // }

    //DeleteUselessMarker();
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
 * @summary     Generate an itinerary from a start and a end position given.
 * @param       {number}    startPointLng   Start point longitude
 * @param       {number}    startPointLat   Start point latitude
 * @param       {number}    finishPointLng  End point longitude
 * @param       {number}    finishPointLat  End point latitude
 */
function GenerateItinerary(startPointLng, startPointLat, finishPointLng, finishPointLat) {
    infoParcours = "";

    fetch(
        "https://api.mapbox.com/directions/v5/mapbox/walking/" +
            startPointLng +
            "," +
            startPointLat +
            ";" +
            finishPointLng +
            "," +
            finishPointLat +
            "?alternatives=true&language=en&geometries=geojson&steps=true&access_token=" +
            MAPBOX_ACCESSTOKEN
    )
        .then((response) => response.json())
        .then((res) => {
            routeSource = {
                type: "geojson",
                data: {
                    type: "Feature",
                    properties: {},
                    geometry: {
                        type: "LineString",
                        coordinates: res.routes[0].geometry.coordinates,
                    },
                },
            };

            routeLayer = {
                id: "route",
                type: "line",
                source: "route",
                layout: {
                    "line-join": "round",
                    "line-cap": "round",
                },
                paint: {
                    "line-color": "#2D1E2F",
                    "line-width": 8,
                },
            };

            if (map.getSource("route") == null) {
                map.addSource("route", routeSource);
                map.addLayer(routeLayer);
            } else {
                map.getSource("route").setData({
                    type: "Feature",
                    properties: {},
                    geometry: {
                        type: "LineString",
                        coordinates: res.routes[0].geometry.coordinates,
                    },
                });
            }
        });
}
