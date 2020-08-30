window.onload = function() {
    refreshPictureUploadLabel();
    pictureUpload();
    pictureDelete();
    pictureChoose();
    videoSend();
    videoDelete();
    listPicturesAndVideos(figureSlug, true);
    linkModal();
}