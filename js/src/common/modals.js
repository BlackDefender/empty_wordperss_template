function openModal(modal) {
    modal = typeof modal === 'string' ? document.getElementById(modal) : modal;
    pageScrollState.fix();
    modal.classList.add('active');
}

function closeModal(modal) {
    modal = typeof modal === 'string' ? document.getElementById(modal) : modal;
    modal.classList.remove('active');
    pageScrollState.unfix();
}

Array.prototype.slice.call(document.querySelectorAll('.modal')).forEach(function (modal) {
    modal.addEventListener('click', function () {
        closeModal(modal);
    });
    modal.querySelector('.close-btn').addEventListener('click', function () {
        closeModal(modal);
    });
    modal.querySelector('.window').addEventListener('click', function (e) {
        e.stopPropagation();
    });
});

Array.prototype.slice.call(document.querySelectorAll('.modal')).forEach(function (modalOpener) {
    modalOpener.addEventListener('click', function () {
        openModal(this.dataset.modalId);
    });
});