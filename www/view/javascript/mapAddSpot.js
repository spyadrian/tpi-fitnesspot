/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   mapAddSpot.js
 * Description  :   It's where all the functioning of the map from the form addSpot work.
 */

mapboxgl.accessToken = MAPBOX_ACCESSTOKEN;

// ----  FILEDS  ----

let marker = null;

let marker_Lng = 0;
let marker_Lat = 0;

// Map
let map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/streets-v11",
    center: [6.1028, 46.2111],
    zoom: 10,
});

// Add geolocate control to the map.
map.addControl(
    new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true,
        },
        trackUserLocation: true,
    })
);

// ----  EVENTS  ----

// On Click Event, add Marker
map.on("click", function (e) {
    if (marker != null) marker.remove();

    marker_Lng = e.lngLat.lng;
    marker_Lat = e.lngLat.lat;

    marker = new mapboxgl.Marker({
        draggable: true,
    })
        .setLngLat([marker_Lng, marker_Lat])
        .addTo(map)
        .on("dragend", OnDragEnd);

    refreshDataInForm(marker_Lng, marker_Lat);
});

// ----  FUNCTS  ----

/**
 * @summary     Refresh the input type hidden in the form of the view.
 *              It also refresh the text, below the map.
 * @param   {number}    lng     The longitude of the marker.
 * @param   {number}    lat     The latitude of the marker.
 */
function refreshDataInForm(lng, lat) {
    let numberAfterDecimal = 5;
    let multiplicator = Math.pow(10, numberAfterDecimal);

    // refresh data lng / lat
    document.getElementById("addSpot_Lng").value = lng;
    document.getElementById("addSpot_Lat").value = lat;

    // refresh text lng / lat
    document.getElementById("str_lng").innerText = "Lng : " + Math.round(lng * multiplicator) / multiplicator;
    document.getElementById("str_lat").innerText = "Lat : " + Math.round(lat * multiplicator) / multiplicator;
}

/**
 * @summary     The Function to bind to the event, "dragend" of the marker.
 */
function OnDragEnd() {
    marker_Lng = marker.getLngLat().lng;
    marker_Lat = marker.getLngLat().lat;

    refreshDataInForm(marker_Lng, marker_Lat);
}
