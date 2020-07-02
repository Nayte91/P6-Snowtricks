var uploadFile = '<input type="file" id="figure" name="figure">';
var pictureId  = 0;
jQuery(document).ready(function() {

    $('#addFile').click(function() {
        $('#imgContainer').append('<input type="file" id="picture'+pictureId+'" name="picture'+pictureId+'">');
        pictureId++;
    });
});

