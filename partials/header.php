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
            <li>
                <a href="lore.php" class="<?= is_active(['lore.php']) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <title>auto-fix</title>
                        <path d="M7.5,5.6L5,7L6.4,4.5L5,2L7.5,3.4L10,2L8.6,4.5L10,7L7.5,5.6M19.5,15.4L22,14L20.6,16.5L22,19L19.5,17.6L17,19L18.4,16.5L17,14L19.5,15.4M22,2L20.6,4.5L22,7L19.5,5.6L17,7L18.4,4.5L17,2L19.5,3.4L22,2M13.34,12.78L15.78,10.34L13.66,8.22L11.22,10.66L13.34,12.78M14.37,7.29L16.71,9.63C17.1,10 17.1,10.65 16.71,11.04L5.04,22.71C4.65,23.1 4,23.1 3.63,22.71L1.29,20.37C0.9,20 0.9,19.35 1.29,18.96L12.96,7.29C13.35,6.9 14,6.9 14.37,7.29Z" />
                    </svg>
                    </svg>
                    <span>Lore</span>
                </a>
            </li>
        </ul>
    </nav>
</header>