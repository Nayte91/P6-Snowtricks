function discussionAdd() {
    $(document).on('click', `#add`, function(e){
        e.preventDefault();
        let form = document.forms.namedItem('discussion');

        if (document.getElementById("discussion_content").value === "") {
            $('#discussion_added').html("You can't send empty discussion.");
        } else {
            let discussionData = new FormData(form);
            $.ajax({
                url: form.action,
                method: "POST",
                data: discussionData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#discussion_added').html(response);
                    listDiscussions(figurePath);
                }
            });
            document.getElementById("discussion_content").value = '';
        }
    });
}

function listDiscussions(figurePath) {
    let block = document.getElementById('discussions');
    let discussionsPath = figurePath+"/discussions";

    while(block.firstChild) block.removeChild(block.firstChild);

    $.getJSON(discussionsPath, function(discussions) {
        if (Object.keys(discussions).length === 0) {
            $('#discussions').append("<p>There is no discussion currently. Launch one !</p>");
            return;
        }

        $.each( discussions, function( i, discussion ) {
            let $discussionMarkup =
                '<div class="row mb-3">' +
                    '<div class="col-2">' +
                        '<img src="https://api.adorable.io/avatars/75/'+discussion.author.username+'.png" alt="'+discussion.author.username+'" height="75" style="border-radius: 50%;">' +
                    '</div>' +
                    '<div class="col-10 border border-dark">' +
                        '<p class="text-left">'+discussion.content.italics()+'</p>' +
                        '<span class="float-right">'+discussion.author.username.bold()+'</span>' +
                    '</div>' +
                '</div>';
            $('#discussions').append($discussionMarkup);
        })
    });
}

$(document).ready(() => {
    listDiscussions(figurePath);
    discussionAdd();
});