// File này gần như giống hệt merch-modal.js
// nhưng được tùy chỉnh lại một chút cho trang member.php
document.addEventListener('DOMContentLoaded', () => {
    // Vẫn dùng ID 'merchModal' như chúng ta đã định nghĩa trong HTML
    const modal = document.getElementById('merchModal'); 
    if (!modal) return;

    // Sửa lại ID nút đóng để tránh xung đột
    const modalCloseBtn = document.getElementById('modalClosePhoto'); 
    const modalImage = document.getElementById('modalImage');
    const modalName = document.getElementById('modalName');
    
    // Sửa lại: Lắng nghe .merch-card
    const photoCards = document.querySelectorAll('.merch-card'); 

    const openModal = (card) => {
        modalImage.src = card.dataset.image;
        modalImage.alt = card.dataset.name;
        modalName.textContent = card.dataset.name;
        
        modal.classList.add('is-visible');
    };

    const closeModal = () => {
        modal.classList.remove('is-visible');
        modalImage.src = ""; // Xóa ảnh để dừng load
    };

    photoCards.forEach(card => {
        card.addEventListener('click', () => openModal(card));
    });

    modalCloseBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-visible')) {
            closeModal();
        }
    });
});