$(document).ready(function(){
   
    $('.close-cross').click(function() {
        console.log('ok');
        $("#delete-avatar").val('true');
     
        $(".image-upload").attr("style","display:none !important;");

    })  
    
})