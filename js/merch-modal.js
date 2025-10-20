document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('merchModal');
    if (!modal) return;

    const modalCloseBtn = document.getElementById('modalClose');
    const modalImage = document.getElementById('modalImage');
    const modalName = document.getElementById('modalName');
    const modalCategory = document.getElementById('modalCategory');
    const modalRelease = document.getElementById('modalRelease');
    const modalDescription = document.getElementById('modalDescription');
    const merchCards = document.querySelectorAll('.merch-card');

    const openModal = (card) => {
        // Lấy dữ liệu từ data attributes của card
        modalImage.src = card.dataset.image;
        modalImage.alt = card.dataset.name;
        modalName.textContent = card.dataset.name;
        modalCategory.textContent = card.dataset.category;
        modalRelease.textContent = card.dataset.release;
        modalDescription.textContent = card.dataset.description;
        
        // Hiển thị modal
        modal.classList.add('is-visible');
    };

    const closeModal = () => {
        modal.classList.remove('is-visible');
    };

    // Gán sự kiện click cho từng thẻ vật phẩm
    merchCards.forEach(card => {
        card.addEventListener('click', () => openModal(card));
    });

    // Gán sự kiện click cho nút đóng, lớp phủ và phím Esc
    modalCloseBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-visible')) {
            closeModal();
        }
    });
});