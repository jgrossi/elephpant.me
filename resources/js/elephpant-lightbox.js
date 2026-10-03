document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-elephpant-lightbox]');

    if (!trigger) {
        return;
    }

    document.getElementById('elephpant-lightbox-image').src = trigger.dataset.image;
    document.getElementById('elephpant-lightbox-caption').textContent = trigger.dataset.alt;

    document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: 'elephpant-image' } }));
});
