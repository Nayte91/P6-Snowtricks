function listPictures($id, editable) {
    $.getJSON(window.location.protocol+"//"+window.location.host+"/"+$id+"/pictures", function($pictures) {
        $('#picturesAndVideos').empty();

        if (Object.keys($pictures).length === 0 ) {
            $('#picturesAndVideos').append("<p class='text-center'>No picture or video yet.</p>");
        }

        $.each( $pictures, function( i, $picture ) {
            console.log($picture);
            var path = window.location.protocol+"//"+window.location.host+"/"+$picture.webPath;
            $('#picturesAndVideos').append("<img src='"+path+"' alt='"+$picture.alt+"' height=auto class='col-2'>");
        });
    });
}
