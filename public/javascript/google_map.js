let map;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 48.52, lng: 2.19 },
    zoom: 5,
  });
}
