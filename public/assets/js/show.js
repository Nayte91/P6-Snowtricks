window.onload = function() {
    enlargePicture(true, 'a.thumbnail');
    listPicturesAndVideos(figureSlug, false);
    listDiscussions(figureSlug);
    discussionAdd();
    enlargePicture(false, 'a.thumbnail');
}