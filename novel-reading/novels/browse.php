<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = mysqli_prepare($conn, "SELECT novel_id, title, author, price, description FROM novels WHERE title LIKE ? OR author LIKE ? ORDER BY created_at DESC");
    $likeSearch = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ss", $likeSearch, $likeSearch);
    mysqli_stmt_execute($stmt);
    $novels = mysqli_stmt_get_result($stmt);
} else {
    $novels = mysqli_query($conn, "SELECT novel_id, title, author, price, description FROM novels ORDER BY created_at DESC");
}

$pageTitle = "Browse Novels";
include __DIR__ . '/../includes/header.php';
?>

<section class="browse-page">
    <h1>Browse Novels</h1>

    <form method="GET" action="" class="search-form">
        <input type="text" name="search" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn-primary">Search</button>
    </form>

    <div class="novel-grid">
        <?php if (mysqli_num_rows($novels) === 0): ?>
            <p>No novels found.</p>
        <?php endif; ?>
        <?php while ($novel = mysqli_fetch_assoc($novels)): ?>
            <a class="novel-card" href="details.php?novel_id=<?php echo (int)$novel['novel_id']; ?>">
                <div class="novel-cover-placeholder"><?php echo htmlspecialchars(mb_substr($novel['title'], 0, 1)); ?></div>
                <h3><?php echo htmlspecialchars($novel['title']); ?></h3>
                <p class="author">by <?php echo htmlspecialchars($novel['author']); ?></p>
                <p class="excerpt"><?php echo htmlspecialchars(mb_substr($novel['description'], 0, 80)); ?>...</p>
                <p class="price"><?php echo $novel['price'] > 0 ? '$' . number_format($novel['price'], 2) : 'Free'; ?></p>
            </a>
        <?php endwhile; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
