$(document).ready(function(){
    $(document).on('change', '#file', function(){
        var form_data = new FormData();
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("file").files[0]);
        form_data.append("file", document.getElementById('file').files[0]);
        $.ajax({
            url: window.location.protocol+"//"+window.location.host+"/"+window.location.pathname.split('/')[1]+"/pictures/add",
            method:"POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success:function() {
                $('#uploaded_image').html("Upload Success !");
            }
        });
    });
});

window.onload = function() {
    listPictures(window.location.pathname.split('/')[1], true);
}