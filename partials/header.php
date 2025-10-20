<?php
/**
 * Hàm này kiểm tra xem trang hiện tại có nằm trong danh sách các trang được chỉ định không.
 * Nếu có, nó sẽ trả về class 'active'.
 *
 * @param array $pages Mảng chứa tên các file PHP cần kiểm tra.
 * @return string Trả về 'active' hoặc chuỗi rỗng.
 */
function is_active(array $pages): string {
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z"/></svg>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="discography.php" class="<?= is_active(['discography.php', 'album.php']) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10Z"/></svg>
                    <span>Discography</span>
                </a>
            </li>
            <li>
                <a href="news.php" class="<?= is_active(['news.php']) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20,22H4A2,2 0 0,1 2,20V4A2,2 0 0,1 4,2H20A2,2 0 0,1 22,4V20A2,2 0 0,1 20,22M11,19H13V17H11V19M11,15H13V10H11V15M4,6V20H20V6H4Z" /></svg>
                    <span>News</span>
                </a>
            </li>
        </ul>
    </nav>
</header>