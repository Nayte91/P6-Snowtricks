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
            beforeSend:function(){
                $('#uploaded_image').html("<label class='text-success'>Image Uploading...</label>");
            },
            success:function(data)
            {
                $('#uploaded_image').html(data);
                console.log(data);
            }
        });
    });
});
