$(document).ready(function(){
   
    $("img").click(function() {

        $(this).toggleClass("hover");

    });

    
    $('.close-cross').click(function() {
        let selectedImages;

        selectedImages = $(this).data("id");

        let actualValue = $("#delete-attach").val();

        if(actualValue != ""){
            $("#delete-attach").val(actualValue+','+selectedImages);
        }
        else{
            $("#delete-attach").val(selectedImages);
        }

        $(".image-upload[data-id="+selectedImages+"]").attr("style","display:none !important;");

    })  
    
})