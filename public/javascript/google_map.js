//Affichage du google map
function getHouses(){
all=[];
//Récupération des données de l'habitat
$('.for_map').each(function(){
    lat= $($(this).find('.pos')[0]).val();
    lng= $($(this).find('.pos')[1]).val();
    price=$(this).find('.price-pop');
    id = $(this).data('id');
    all.push([parseFloat(lat),parseFloat(lng),price[0],id])
})
const center = { lat: all[0][0], lng: all[0][1] }
}


function initMap(center) {
  getHouses();
  if(typeof center == 'undefined'){
    center = { lat: all[0][0], lng: all[0][1] };
    var mapOptions = {
      zoom: 12,
      center: center,
    }
  } else{
    var mapOptions = {
      zoom: 12,
      center: center,
    }
  }

  

  //Initialisation de la map
  var map = new google.maps.Map(document.getElementById("map"), mapOptions);
  //Image du marqueur
  const image = "../font/house-solid.svg";
  
  //Class du popup de l'affichage du prix
  class Popup extends google.maps.OverlayView {
    position;
    containerDiv;
    constructor(position, content) {
      super();
      this.position = position;
      content.classList.add("popup-bubble");

      // This zero-height div is positioned at the bottom of the bubble.
      const bubbleAnchor = document.createElement("div");

      bubbleAnchor.classList.add("popup-bubble-anchor");
      bubbleAnchor.appendChild(content);
      // This zero-height div is positioned at the bottom of the tip.
      this.containerDiv = document.createElement("div");
      this.containerDiv.classList.add("popup-container");
      this.containerDiv.appendChild(bubbleAnchor);
      // Optionally stop clicks, etc., from bubbling up to the map.
      Popup.preventMapHitsAndGesturesFrom(this.containerDiv);
    }
    /** Called when the popup is added to the map. */
    onAdd() {
      this.getPanes().floatPane.appendChild(this.containerDiv);
    }
    /** Called when the popup is removed from the map. */
    onRemove() {
      if (this.containerDiv.parentElement) {
        this.containerDiv.parentElement.removeChild(this.containerDiv);
      }
    }
    /** Called each frame when the popup needs to draw itself. */
    draw() {
      const divPosition = this.getProjection().fromLatLngToDivPixel(
        this.position
      );
      // Hide the popup when it is far out of view.
      const display =
        Math.abs(divPosition.x) < 4000 && Math.abs(divPosition.y) < 4000
          ? "block"
          : "none";

      if (display === "block") {
        this.containerDiv.style.left = divPosition.x + "px";
        this.containerDiv.style.top = divPosition.y-25 + "px";
      }

      if (this.containerDiv.style.display !== display) {
        this.containerDiv.style.display = display;
      }
    }
  }

  //Affichage du marqueur et du prix de chaque habitat
  for (let i = 0; i < all.length; i++) {
    const place=all[i];

    const marker = new google.maps.Marker({
      position: { lat: place[0], lng: place[1] },
      icon: image,
    });

    marker.setMap(map);

    //Centrer la map sur l'habitat cliqué dans la map
    marker.addListener("click", () => {
      map.setZoom(13);
      map.setCenter(marker.getPosition());
      $('html, body').animate({
        scrollTop: $('.house-card[data-id="'+place[3]+'"').offset().top
      });
    });

     //Centrer la map sur l'habitat cliqué dans la liste des habitats affichées
    $('.house-card[data-id="'+place[3]+'"').on("click", () => {
      map.setZoom(13);
      map.setCenter(marker.getPosition());
    });

    var myLatlng = new google.maps.LatLng(place[0],place[1]);
    let popup = new Popup(
      myLatlng,
      place[2]
    );
    popup.setMap(map);
  }


}