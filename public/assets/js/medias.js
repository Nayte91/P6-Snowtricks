function listPictures($id, editable) {
    $.getJSON(window.location.protocol+"//"+window.location.host+"/"+$id+"/pictures", function($pictures) {
        $('#picturesAndVideos').empty();

        if (Object.keys($pictures).length === 0 ) {
            $('#picturesAndVideos').append("<p class='text-center'>No picture or video yet.</p>");
            return;
        }

        $.each( $pictures, function( i, $picture ) {
            console.log($picture);
            var path = window.location.protocol+"//"+window.location.host+"/"+$picture.webPath;
            $('#picturesAndVideos').append("<img src='"+path+"' alt='"+$picture.alt+"' height=auto class='col-3'>");
        });

        if (Object.keys($pictures).length > 4 ) {
            $('.demo').slick({
                dots: false,
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 4,
                prevArrow: '<button type="button" class="slick-prev rounded-circle btn btn-danger"><i class="fas fa-arrow-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next rounded-circle btn btn-danger"><i class="fas fa-arrow-right"></i></button>'
            });
        }
    });
}
