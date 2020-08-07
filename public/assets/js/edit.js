function pictureUpload() {
    $(document).on('click', '#upload', function(){
        if (document.getElementById("file").files.length === 0) {
            $('#uploaded_image').html("Please select a file");
        } else {
            var picture_data = new FormData();
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("file").files[0]);
            picture_data.append("file", document.getElementById('file').files[0]);
            $.ajax({
                url: window.location.protocol+"//"+window.location.host+"/"+window.location.pathname.split('/')[1]+"/pictures/add",
                method:"POST",
                data: picture_data,
                contentType: false,
                cache: false,
                processData: false,
                success:function(response) {
                    $('#uploaded_image').html(response);
                    listPicturesAndVideos(window.location.pathname.split('/')[1], true);
                }
            });
        }
        document.getElementById("file").value = '';
    });
}

function videoSend() {
    $(document).on('click', '#send', function(){
        if (document.getElementById("url").value === "") {
            $('#video_sent').html("Please give a link");
        } else {
            video_data = document.getElementById("url").value;
            $.ajax({
                url: window.location.protocol + "//" + window.location.host + "/" + window.location.pathname.split('/')[1] + "/videos/add",
                method: "POST",
                data: video_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    $('#video_sent').html(response);
                    listPicturesAndVideos(window.location.pathname.split('/')[1], true);
                }
            });
            document.getElementById("url").value = '';
        }
    });
}

window.onload = function() {
    pictureUpload();
    videoSend();
    listPicturesAndVideos(window.location.pathname.split('/')[1], true);
}