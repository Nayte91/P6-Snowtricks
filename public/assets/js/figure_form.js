var $collectionHolder;

// setup an "add a picture" link
var $addPictureButton = $('<button type="button" class="btn add_picture_link"><i class="fas fa-plus"></i> Add a picture</button>');
var $newLinkLi = $('<div></div>').append($addPictureButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of pictures
    $collectionHolder = $('ul.pictures');

    // add the "add a picture" anchor and li to the pictures ul
    $collectionHolder.append($newLinkLi);

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