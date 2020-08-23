window.onload = function() {
    refreshPictureUploadLabel();
    pictureUpload();
    pictureDelete();
    pictureChoose();
    videoSend();
    listPicturesAndVideos(figureSlug, true);
    linkModal();
}