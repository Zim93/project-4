$(document).ready(function(){
    $(function() {

        $("img").click(function() {

            $(this).toggleClass("hover");

        });

        
        $('#delet-image').click(function() {
            let selectedImages = [];

            $("img.hover").each(function() {
            selectedImages.push($(this).attr("id"));
            });

            let house= $('#images').data('house');;
            let data={images:selectedImages} ;
            let currentURL = window.location.origin;

            $.ajax({
                url: currentURL + "/attachement",
                method: "post",
                data: data
            })
            .done(function(response) {
                $('#images').html(response);
            });

            $("img").removeClass("hover");
        })
    
    
        
    });
})