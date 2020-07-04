var $collectionHolder;

var prototype = $('div.pictures').data('prototype');
var $button = $('button.add_picture_link');

jQuery(document).ready(function() {
    $collectionHolder = $('div.pictures');
    $button.on('click', function() {

        // add a new tag form (see next code block)
        addTagForm($collectionHolder);
    });
});

function addTagForm($collectionHolder) {
    // Get the data-prototype explained earlier
    //var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);


    var $newFormMarkups = $('<div></div>').append(newForm);
    $button.after($newFormMarkups);
}
