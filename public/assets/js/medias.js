$.ajaxSetup({ async: false });

function displayPicturesAndVideos (pictures, videos, editable) {
    $.each( pictures, function( i, picture ) {
        var path = window.location.protocol+"//"+window.location.host+"/"+picture.webPath;
        $('#picturesAndVideos').append('<div><img src="'+path+'" alt="'+picture.alt+'" height="150" class=""></div>');
    });
    $.each( videos, function( i, video ) {
        $('#picturesAndVideos').append('<iframe height="150" src="'+video.url+'" frameborder="0" allowfullscreen></iframe>');
    });
}

function listPicturesAndVideos($id, editable) {
    var pictures, videos;
    $.getJSON(window.location.protocol+"//"+window.location.host+"/"+$id+"/videos", function(v) {
        videos = v;
    });
    $.getJSON(window.location.protocol+"//"+window.location.host+"/"+$id+"/pictures", function (p) {
        pictures = p;
    });

    var block = document.getElementById('picturesAndVideos');
    block.removeAttribute('class');
    while(block.firstChild) block.removeChild(block.firstChild);

    if (Object.keys(pictures).length === 0 && Object.keys(videos).length === 0) {
        $('#picturesAndVideos').append("<p class='text-center'>No picture or video yet.</p>");
        return;
    }

    displayPicturesAndVideos(pictures, videos, true);

    if (Object.keys(pictures).length+Object.keys(videos).length > 4 ) {
        $('#picturesAndVideos').slick({
            dots: false,
            infinite: false,
            accessibility: false,
            slidesToShow: 4,
            slidesToScroll: 4,
            prevArrow: '<button type="button" class="slick-prev rounded-circle btn btn-danger"><i class="fas fa-arrow-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next rounded-circle btn btn-danger"><i class="fas fa-arrow-right"></i></button>'
        });
    }
}
