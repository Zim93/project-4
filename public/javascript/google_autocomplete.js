function initialize() {
    input = document.getElementById('house_full_address');
    autocomplete = new google.maps.places.Autocomplete(input,
    {
        componentRestrictions: {'country':['FR']},
        fields: ["address_components", "geometry"],
        types: ["address"],
    });
    
    autocomplete.addListener("place_changed", fillInAddress)
}
function fillInAddress(){
    const place = autocomplete.getPlace();
    for(component of place.address_components){
        componentType = component.types[0];
        switch(componentType){
            case "street_number":{
                street = component.long_name;
                [street_number, street_sub] = street.match(/\D+|\d+/g);
                break;
            }
            case "route":{
                street_label = component.long_name;
                break;
            }
            case "postal_code":{
                postcode= component.long_name;
                break
            }
            case "locality":{
                city = component.long_name;
                break;
            }
            case "country":{
                country = component.long_name;
                break;
            }
        
        }
    }
    document.querySelector("#house_street_number").value=street_number;
    if(street_sub!==undefined){
        document.querySelector("#house_street_sub_number").value=street_sub;
    }
    document.querySelector("#house_street_label").value=street_label;
    document.querySelector("#house_postal_code").value=postcode;
    document.querySelector("#house_city_name").value=city;
    document.querySelector("#house_country").value=country;
}




google.maps.event.addDomListener(window, 'load', initialize);