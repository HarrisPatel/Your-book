<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="bottom-bar">
    <a href="feed.php?ld=false" class="nav-item <?php echo ($currentPage == 'feed.php') ? 'active' : ''; ?>">
        <span>Feed</span>
    </a>
    <a href="search.php" class="nav-item <?php echo ($currentPage == 'search.php') ? 'active' : ''; ?>">
        <span>Search</span>
    </a>
</div>
