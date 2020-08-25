$.ajaxSetup({ async: false });

function refreshPictureUploadLabel() {
    $('#picture_file').on('change',function(e){
        var fileName = e.target.files[0].name;
        $('.custom-file-label').text(fileName);
    })
}

function pictureUpload() {
    $(document).on('click', '#upload', function(e){
        e.preventDefault();
        var form = document.forms.namedItem('picture');
        //avoid action when no file is selected
        if (document.getElementById('picture_file').files.length === 0) {
            document.getElementById('uploaded_image').innerText = 'Please select a file';
        } else {
            var pictureData = new FormData(form);
            $.ajax({
                url: form.action,
                method:'POST',
                data: pictureData,
                contentType: false,
                processData: false,
                success:function(response) {
                    $('#uploaded_image').html(response);
                    listPicturesAndVideos(figureSlug, true);
                }
            });
        }

        document.getElementById('picture_file').value = '';
    });
}

function pictureDelete() {
    $(document).on('click', 'a[class="picture-delete"]' , function(e){
        e.preventDefault();
        let link = this.getAttribute('data-link');
        $.ajax({
            url: link,
            method:'DELETE',
            success: function () {
                listPicturesAndVideos(figureSlug, true);
            }
        });
    });
}

function pictureChoose() {
    $(document).on('click', 'a[class="picture-choose"]' , function(e){
        e.preventDefault();
        let link = this.getAttribute('data-link');
        $.ajax({
            url: link,
            method:'PUT',
            success: function () {
                listPicturesAndVideos(figureSlug, true);
            }
        });
    });
}

function videoSend() {
    $(document).on('click', '#send', function(e){
        e.preventDefault();
        var form = document.forms.namedItem('video');

        if (document.getElementById("video_url").value === "") {
            $('#video_sent').html("Please give a link");
        } else {
            var videoData = new FormData(form);
            $.ajax({
                url: form.action,
                method: "POST",
                data: videoData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#video_sent').html(response);
                    listPicturesAndVideos(figureSlug, true);
                }
            });
            document.getElementById("video_url").value = '';
        }
    });
}

function displayPicturesAndVideos (pictures, videos, editable) {
    $.each( pictures, function( i, picture ) {
        let linkPath = window.location.protocol+"//"+window.location.host+"/"+picture.webPath;
        let deletePath = window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/pictures/"+picture.id+"/delete";
        let choosePath = window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/pictures/"+picture.id+"/choose";

        let $pictureMarkup =
            '<div style="position: relative;" class="col-3 mx-auto">' +
            '<img src="'+linkPath+'" alt="'+picture.alt+'" height="150" style="display: block;">';
        if (editable === true) {
            $pictureMarkup +=
                '<a title="Choose as display picture" href="#" data-link="'+choosePath+'" class="picture-choose" style="color: black;">' +
                    '<i class="fas fa-pencil-alt" style="position: absolute; bottom:2px; left:20px;"></i>' +
                '</a>'+
                '<a title="Delete this picture" href="#" data-link="'+deletePath+'" class="picture-delete" style="color: black;">' +
                    '<i class="fas fa-trash-alt" style="position: absolute; bottom:2px; left:0;"></i>' +
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
    block.classList.add("row");
    while(block.firstChild) block.removeChild(block.firstChild);

    $.getJSON(window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/videos", function(v) {
        videos = v;
    });
    $.getJSON(window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/pictures", function (p) {
        pictures = p;
    });

    if (Object.keys(pictures).length === 0 && Object.keys(videos).length === 0) {
        $("#displayPicture").html('<img src="'+defaultPicture+'" alt="default" height="500" class=" mx-auto d-block">');
        $('#picturesAndVideos').append("<p class='text-center'>No picture or video yet.</p>");
        return;
    }

    displayPicturesAndVideos(pictures, videos, editable);

    if (Object.keys(pictures).length+Object.keys(videos).length > 3 ) {
        block.removeAttribute('class');
        $('#picturesAndVideos').slick({
            dots: false,
            infinite: false,
            accessibility: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            prevArrow: '<button type="button" class="slick-prev rounded-circle btn btn-danger"><i class="fas fa-arrow-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next rounded-circle btn btn-danger"><i class="fas fa-arrow-right"></i></button>'
        });
    }
}
