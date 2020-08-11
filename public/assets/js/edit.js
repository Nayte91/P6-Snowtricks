function ckEditor(blockId) {
    ClassicEditor
        .create( document.querySelector( '#'+blockId ), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'mediaEmbed' ],
            title: {
                placeholder: 'My custom placeholder for the title'
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' }
                ]
            }
        } )
        .catch( error => {
            console.log( error );
        } );
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
                    listPicturesAndVideos(figureid, true);
                }
            });
        }
        //some cleanup of the input file field, to chain the tests
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
    //ckEditor('figure_description');
    pictureUpload();
    videoSend();
    listPicturesAndVideos(figureid, true);
}