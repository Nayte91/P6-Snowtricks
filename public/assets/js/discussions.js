function discussionAdd() {
    $(document).on('click', `#add`, function(e){
        e.preventDefault();
        var form = document.forms.namedItem('discussion');

        if (document.getElementById("discussion_content").value === "") {
            $('#discussion_added').html("You can't send empty discussion.");
        } else {
            var discussionData = new FormData(form);
            $.ajax({
                url: form.action,
                method: "POST",
                data: discussionData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#discussion_added').html(response);
                    listDiscussions(figureSlug);
                }
            });
            document.getElementById("discussion_content").value = '';
        }
    });
}

function listDiscussions(figureSlug) {
    let block = document.getElementById('discussions');
    while(block.firstChild) block.removeChild(block.firstChild);
    $.getJSON(window.location.protocol+"//"+window.location.host+"/figures/"+figureSlug+"/discussions", function(discussions) {
        if (Object.keys(discussions).length === 0) {
            $('#discussions').append("<p>There is no discussion currently. Launch one !</p>");
            return;
        }

        $.each( discussions, function( i, discussion ) {
            let $discussionMarkup =
                '<div class="row mb-3">' +
                    '<div class="col-2">' +
                        '<img src="'+defaultUser+'" alt="default" height="75">' +
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