var pageScrollPosition = 0;

function openModal(modal) {
    modal = typeof modal === 'string' ? document.getElementById(modal) : modal;
    pageScrollPosition = window.scrollY;
    document.body.parentNode.classList.add('fixed');
    document.body.scrollTop = pageScrollPosition;
    modal.classList.add('active');
}

function closeModal(modal) {
    modal = typeof modal === 'string' ? document.getElementById(modal) : modal;
    modal.classList.remove('active');
    document.body.parentNode.classList.remove('fixed');
    window.scrollTo(0, pageScrollPosition);
}

var $modals = $('.modal');

$modals.click(function () {
    closeModal(this);
});

$modals.find('.close-btn').click(function () {
    closeModal(this.closest('.modal'));
});

$modals.find('.window').click(function (e) {
    e.stopPropagation();
});

$('.modal-opener').click(function () {
    openModal(this.dataset.modalId);
});