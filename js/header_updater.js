/**
 * Hàm này sẽ tự động chạy mỗi khi Swup tải xong một trang mới.
 * Nhiệm vụ của nó là tìm đúng mục menu và thêm class 'active' vào.
 */
function updateActiveNavlink() {
    const currentPagePath = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.main-nav a');

    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');

        // Xóa class 'active' khỏi tất cả các nút để reset
        link.classList.remove('active');

        // Logic kiểm tra để gán lại class 'active'
        if (
            // Trường hợp 1: Link khớp chính xác với trang hiện tại
            linkPath === currentPagePath ||
            // Trường hợp 2: Trang con 'member.php' làm sáng trang cha 'index.php'
            (currentPagePath === 'member.php' && linkPath === 'index.php') ||
            // Trường hợp 3: Trang con 'album.php' làm sáng trang cha 'discography.php'
            (currentPagePath === 'album.php' && linkPath === 'discography.php') ||
            // BỔ SUNG: Trang con 'post.php' làm sáng trang cha 'news.php'
            (currentPagePath === 'post.php' && linkPath === 'news.php') ||
            // SỬA LỖI: Thêm || và logic cho trang 'merch.php'
            (currentPagePath.startsWith('merch.php') && linkPath === 'merch.php')
        ) {
            link.classList.add('active');
        }
    });
}

// Lắng nghe sự kiện của Swup: 'page:view' nghĩa là "khi trang mới đã hiển thị xong"
document.addEventListener('swup:page:view', updateActiveNavlink);

// Chạy hàm một lần ngay khi trang được tải lần đầu, để đảm bảo đồng bộ
document.addEventListener('DOMContentLoaded', updateActiveNavlink);