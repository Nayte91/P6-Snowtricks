import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import './medias';

$(document).ready(() => {

    pictureUpload();
    pictureDelete();
    pictureChoose();
    videoSend();
    videoDelete();
    listPicturesAndVideos(figureSlug, true);

    ClassicEditor
        .create( document.querySelector( '#figure_description' ) )
        .then( editor => {
            console.log( editor );
        } )
        .catch( error => {
            console.error( error );
        } );
});