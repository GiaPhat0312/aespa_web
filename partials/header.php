<?php

/**
 * Hàm này kiểm tra xem trang hiện tại có nằm trong danh sách các trang được chỉ định không.
 * Nếu có, nó sẽ trả về class 'active'.
 *
 * @param array $pages Mảng chứa tên các file PHP cần kiểm tra.
 * @return string Trả về 'active' hoặc chuỗi rỗng.
 */
function is_active(array $pages): string
{
    $current_page = basename($_SERVER['PHP_SELF']);
    if (in_array($current_page, $pages)) {
        return 'active';
    }
    return '';
}
?>
<header class="main-header">
    <nav class="main-nav">
        <ul>
            <li>
                <a href="index.php" class="<?= is_active(['index.php', 'member.php']) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
                    </svg>
                    <span>Thông Tin</span>
                </a>
            </li>
            <li>
                <a href="discography.php" class="<?= is_active(['discography.php', 'album.php']) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10Z" />
                    </svg>
                    <span>Album Nhạc</span>
                </a>
            </li>
            <li>
                <a href="news.php" class="<?= is_active(['news.php']) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20,22H4A2,2 0 0,1 2,20V4A2,2 0 0,1 4,2H20A2,2 0 0,1 22,4V20A2,2 0 0,1 20,22M11,19H13V17H11V19M11,15H13V10H11V15M4,6V20H20V6H4Z" />
                    </svg>
                    <span>Tin Tức</span>
                </a>
            </li>
            <li>
                <a href="merch.php" class="<?= is_active(['merch.php']) ?>">
                    <svg class="dashboard-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20 6H4V4H20V6M20.32 8H3.68L2 12V20H22V12L20.32 8M18 18H6V12.91L7.09 11H16.91L18 12.91V18M12 13C10.9 13 10 13.9 10 15H11.5C11.5 14.72 11.72 14.5 12 14.5S12.5 14.72 12.5 15H14C14 13.9 13.1 13 12 13Z" />
                    </svg>
                    <span>Bộ sưu tập</span>
                </a>
            </li>
        </ul>
    </nav>
</header>