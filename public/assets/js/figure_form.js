var $collectionHolder;

// setup an "add a picture" link
var $addPictureButton = $('<button type="button" class="btn add_picture_link"><i class="fas fa-plus"></i> Add a picture</button>');
var $newLinkLi = $('<div></div>').append($addPictureButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of pictures
    $collectionHolder = $('#upload-form');

    // add the "add a picture" anchor and li to the pictures ul
    $collectionHolder.before($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addPictureButton.on('click', function(e) {
        // add a new picture form (see next code block)
        addPictureForm($collectionHolder, $newLinkLi);
    });
});

function addPictureForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;

    // You need this only if you didn't set 'label' => false in your pictures field in FigureType
    // Replace '__name__label__' in the prototype's HTML to instead be a number based on how many items we have
    //newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an div, after the "Add a picture" link div
    $newFormLi = $('<div></div>').append(newForm);
    $newLinkLi.append($newFormLi);
}

$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

/**
 * Simple (ugly) code to handle the comment vote up/down
 */
var $container = $('.custom-file');
$container.find('input').on('click', function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);

    var myFormData = new FormData();
    myFormData.append('pictureFile', pictureInput.files[0]);
    $.ajax({
        url: window.location.protocol+"//"+window.location.host+"/"+window.location.pathname.split('/')[1]+"/pictures/create",
        method: 'POST',
        processData: false, // important
        contentType: false, // important
        dataType : 'json',
        data: myFormData
    }).then(function(response) {
        console.log("toto");
    });
});