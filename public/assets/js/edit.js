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
                    listPicturesAndVideos(figureid, true);
                }
            });
        }

        document.getElementById('picture_file').value = '';
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
                    listPicturesAndVideos(figureid, true);
                }
            });
            document.getElementById("video_url").value = '';
        }
    });
}

window.onload = function() {
    pictureUpload();
    videoSend();
    listPicturesAndVideos(figureSlug, true);
}