$.ajaxSetup({ async: false });

function enlargePicture(setIDs, setClickAttr){
    var current_image,
        counter = 0;

    if(setIDs === true){
        $('[data-image-id]').each(function(){
            counter++;
            $(this).attr('data-image-id',counter);
        });
    }

    $(setClickAttr).on('click',function(){
        var $sel = $(this);
        current_image = $sel.data('image-id');
        $('#image-gallery-image').attr('src', $sel.data('image')).attr('alt', $sel.data('alt'));
        disableButtons(counter, $sel.data('image-id'));
    });
}

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
                    listPicturesAndVideos(figurePath, true);
                }
            });
        }
        console.log(document.getElementById('picture_file_label'));
        document.getElementById('picture_file_label').innerHTML = '';
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
                listPicturesAndVideos(figurePath, true);
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
                listPicturesAndVideos(figurePath, true);
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
                    listPicturesAndVideos(figurePath, true);
                }
            });
            document.getElementById("video_url").value = '';
        }
    });
}

function videoDelete() {
    $(document).on('click', 'a[class="video-delete"]' , function(e){
        e.preventDefault();
        let link = this.getAttribute('data-link');
        $.ajax({
            url: link,
            method:'DELETE',
            success: function () {
                listPicturesAndVideos(figurePath, true);
            }
        });
    });
}

function displayPicturesAndVideos (pictures, videos, editable) {
    $.each( pictures, function( i, picture ) {
        let pictureLinkPath = window.location.protocol+"//"+window.location.host+"/"+picture.webPath;
        let pictureDeletePath = figurePath+"/pictures/"+picture.id+"/delete";
        let pictureChoosePath = figurePath+"/pictures/"+picture.id+"/choose";

        let $pictureMarkup =
            '<div style="position: relative;" class="col-3 mx-auto">' +
                '<a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-image="'+pictureLinkPath+'" data-alt="'+picture.alt+'" data-target="#image-gallery">' +
                    '<img src="'+pictureLinkPath+'" alt="'+picture.alt+'" height="150" style="display: block;">' +
                '</a>';
        if (editable) {
            $pictureMarkup +=
                '<div class="position-absolute" style="bottom:2px; left:20px;">' +
                    '<a title="Choose as display picture" href="#" data-link="'+pictureChoosePath+'" class="picture-choose" style="color: black;">' +
                        '<i class="fas fa-pencil-alt mx-1"></i>' +
                    '</a>'+
                    '<a title="Delete this picture" href="#" data-link="'+pictureDeletePath+'" class="picture-delete" style="color: black;">' +
                        '<i class="fas fa-trash-alt"></i>' +
                    '</a>' +
                '</div>';
        }
        $pictureMarkup += '</div>';
        $('#picturesAndVideos').append($pictureMarkup);
        if (picture.isDisplayPicture) {
            $("#displayPicture").html('<img src="'+pictureLinkPath+'" alt="default" class="img-fluid mx-auto d-block">');
            hasDisplayPicture = true;
        }
    });
    $.each( videos, function( i, video ) {
        let videoDeletePath = figurePath+"/videos/"+video.id+"/delete";
        let $videoMarkup =
            '<div style="position: relative;" class="col-3 mx-auto">' +
                '<iframe height="150" src="'+video.url+'" allowfullscreen>' +
                '</iframe>';
        if (editable) {
            $videoMarkup +=
                '<a title="Delete this video" href="#" data-link="'+videoDeletePath+'" class="video-delete" style="color: black;">' +
                    '<i class="fas fa-trash-alt" style="position: absolute; bottom:0; left:0;"></i>' +
                '</a>';
        }
        $videoMarkup += '</div>';
        $('#picturesAndVideos').append($videoMarkup);
    });
}

function listPicturesAndVideos(figurePath, editable) {
    let pictures, videos;
    let videosPath = figurePath+"/videos";
    let picturePath = figurePath+"/pictures";
    let block = document.getElementById('picturesAndVideos');

    block.removeAttribute('class');
    while(block.firstChild) block.removeChild(block.firstChild);

    $.getJSON(videosPath, function(v) {
        videos = v;
    });
    $.getJSON(picturePath, function (p) {
        pictures = p;
    });

    if (Object.keys(pictures).length === 0 && Object.keys(videos).length === 0) {
        $("#displayPicture").html('<img src="'+defaultPicture+'" alt="default" class="mx-auto d-block" style="object-fit: cover">');
        $('#picturesAndVideos').append("No picture nor video yet.");
        return;
    }
    
    block.classList.add("row");
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
