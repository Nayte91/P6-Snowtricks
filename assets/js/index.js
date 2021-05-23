window.onload = function() {
    linkModal();
}

const deleteButtons = Array.from(document.getElementsByClassName('delete-button'));
const arrowDown = document.getElementById('arrow-down');

deleteButtons.map(deleteButton => {
    const slug = deleteButton.getAttribute('data-slug');
    deleteButton.addEventListener('click', (event) => {
    });
});