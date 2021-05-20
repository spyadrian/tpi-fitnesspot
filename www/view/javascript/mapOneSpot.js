/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   mapOneSpot.js
 * Description  :   It's where all the functioning of the implement of the map of oneSpot are.
 */

mapboxgl.accessToken = MAPBOX_ACCESSTOKEN;

// ---  FILEDS  ---

let lngUSer = 0;
let latUser = 0;

// Map
let map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/streets-v11",
    center: [6.1028, 46.2111],
    zoom: 10,
});

// ---  EVENTS  ---

$("document").ready(ShowOneSpot());

// ---  FUNCTS  ---

/**
 * @summary     Get the list of all the spot
 */
function ShowOneSpot() {
    let lat = document.getElementById("mySpotLat").value;
    let lon = document.getElementById("mySpotLon").value;

    let divMarker = document.createElement("div");
    divMarker.innerHTML = '<i class="bi bi-geo-alt-fill" style="font-size: 2.5rem;"></i>';

    new mapboxgl.Marker(divMarker).setLngLat([lon, lat]).addTo(map);

    map.flyTo({
        center: [lon, lat],
        zoom: 15,
        essential: true,
    });
}
