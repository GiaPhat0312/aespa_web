// Lắng nghe sự kiện 'page:view' của Swup, sự kiện này được kích hoạt
// ngay sau khi nội dung trang mới được đưa vào và sẵn sàng để xem.
document.addEventListener('swup:page:view', () => {
    // Lấy tên file của trang mới mà Swup vừa tải
    const currentPagePath = window.location.pathname.split('/').pop();
    
    // Lấy tất cả các nút trên thanh điều hướng
    const navLinks = document.querySelectorAll('.main-nav a');

    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');

        // Xóa class 'active' khỏi tất cả các nút để reset
        link.classList.remove('active');

        // Logic kiểm tra để gán lại class 'active'
        if (
            // Trường hợp 1: Link khớp chính xác với trang hiện tại
            linkPath === currentPagePath ||
            // Trường hợp 2: Trang hiện tại là 'member.php' và link là 'index.php'
            (currentPagePath === 'member.php' && linkPath === 'index.php') ||
            // Trường hợp 3: Trang hiện tại là 'album.php' và link là 'discography.php'
            (currentPagePath === 'album.php' && linkPath === 'discography.php')
        ) {
            // Thêm class 'active' vào đúng nút
            link.classList.add('active');
        }
    });
});