function refreshPictureUploadLabel() {
    $('#picture_file').on('change',function(e){
        //get the file name
        var fileName = e.target.files[0].name;
        //replace the "Choose a file" label
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

window.onload = function() {
    refreshPictureUploadLabel();
    pictureUpload();
    pictureDelete();
    videoSend();
    listPicturesAndVideos(figureSlug, true);
}