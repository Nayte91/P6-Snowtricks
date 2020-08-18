$.ajaxSetup({ async: false });

function displayPicturesAndVideos (pictures, videos, editable) {
    $.each( pictures, function( i, picture ) {
        let linkPath = window.location.protocol+"//"+window.location.host+"/"+picture.webPath;
        let deletePath = window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/pictures/"+picture.id+"/delete";
        let choosePath = window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/pictures/"+picture.id+"/choose";

        let $pictureMarkup =
            '<div style="position: relative;">' +
            '<img src="'+linkPath+'" alt="'+picture.alt+'" height="150" style="display: block;">';
        if (editable === true) {
            $pictureMarkup +=
                '<a title="Choose as display picture" href="#" data-link="'+choosePath+'" class="picture-choose" style="color: black;">' +
                    '<i class="fas fa-pencil-alt" style="position: absolute; bottom:2; left:20;"></i>' +
                '</a>'+
                '<a title="Delete this picture" href="#" data-link="'+deletePath+'" class="picture-delete" style="color: black;">' +
                    '<i class="fas fa-trash-alt" style="position: absolute; bottom:2; left:0;"></i>' +
                '</a>';
        }
        $pictureMarkup += '</div>';
        $('#picturesAndVideos').append($pictureMarkup);
        if (picture.isDisplayPicture) {
            $("#displayPicture").html('<img src="'+window.location.protocol+"//"+window.location.host+"/"+picture.webPath+'" alt="default" class="img-fluid mx-auto d-block">');
            hasDisplayPicture = true;
        }
    });
    $.each( videos, function( i, video ) {
        $('#picturesAndVideos').append('<iframe height="150" src="'+video.url+'" allowfullscreen></iframe>');
    });
}

function listPicturesAndVideos(figureSlug, editable) {
    var pictures, videos;
    var block = document.getElementById('picturesAndVideos');
    block.removeAttribute('class');
    while(block.firstChild) block.removeChild(block.firstChild);

    $.getJSON(window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/videos", function(v) {
        videos = v;
    });
    $.getJSON(window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/pictures", function (p) {
        pictures = p;
    });

    if (Object.keys(pictures).length === 0 && Object.keys(videos).length === 0) {
        $("#displayPicture").html('<img src="'+defaultPicture+'" alt="default" class="img-fluid mx-auto d-block">');
        $('#picturesAndVideos').append("<p class='text-center'>No picture or video yet.</p>");
        return;
    }

    displayPicturesAndVideos(pictures, videos, editable);

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
