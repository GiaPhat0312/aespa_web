document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.getElementById('navToggle');
    const mainNav = document.getElementById('mainNav');

    if (navToggle && mainNav) {
        navToggle.addEventListener('click', () => {
            // Thêm/xóa class 'is-open' cho cả nút và nav
            navToggle.classList.toggle('is-open');
            mainNav.classList.toggle('is-open');
        });
    }

    // Tự động đóng menu khi nhấp vào một link
    // Rất quan trọng cho Swup và trải nghiệm mobile
    const navLinks = mainNav.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (mainNav.classList.contains('is-open')) {
                navToggle.classList.remove('is-open');
                mainNav.classList.remove('is-open');
            }
        });
    });
});