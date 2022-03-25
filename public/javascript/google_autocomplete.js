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

    lat=place.geometry.location.toJSON().lat;
    lng=place.geometry.location.toJSON().lng;

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
    $("#house_street_number").val(street_number);
    if(street_sub!==undefined){
        $("#house_street_sub_number").val(street_sub);
    }
    $("#house_street_label").val(street_label);
    $("#house_postal_code").val(postcode);
    $("#house_city_name").val(city);
    $("#house_country").val(country);
    $("#house_lat").val(lat);
    $("#house_lng").val(lng);
}

    $('#house_full_address').keyup(function(){
        if($(this).val() == ''){
            $("#house_street_number").val('');
            $("#house_street_sub_number").val('');           
            $("#house_street_label").val('');
            $("#house_postal_code").val('');
            $("#house_city_name").val('');
            $("#house_country").val('');
            $("#house_lat").val('');
            $("#house_lng").val('');
        }
    });

google.maps.event.addDomListener(window, 'load', initialize);