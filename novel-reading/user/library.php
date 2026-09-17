<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];

// Library = free novels the user has read + novels they've purchased
$sql = "
    SELECT DISTINCT n.novel_id, n.title, n.author, n.price
    FROM novels n
    LEFT JOIN purchases p ON p.novel_id = n.novel_id AND p.user_id = ?
    LEFT JOIN reading_history h ON h.novel_id = n.novel_id AND h.user_id = ?
    WHERE p.purchase_id IS NOT NULL OR (n.price = 0 AND h.history_id IS NOT NULL)
    ORDER BY n.title ASC
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $userId, $userId);
mysqli_stmt_execute($stmt);
$novels = mysqli_stmt_get_result($stmt);

$pageTitle = "My Library";
include __DIR__ . '/../includes/header.php';
?>

<section class="library-page">
    <h1>My Library</h1>

    <div class="novel-grid">
        <?php if (mysqli_num_rows($novels) === 0): ?>
            <p>Your library is empty. <a href="<?php echo BASE_URL; ?>novels/browse.php">Browse novels</a> to get started.</p>
        <?php endif; ?>
        <?php while ($novel = mysqli_fetch_assoc($novels)): ?>
            <a class="novel-card" href="<?php echo BASE_URL; ?>novels/details.php?novel_id=<?php echo (int)$novel['novel_id']; ?>">
                <div class="novel-cover-placeholder"><?php echo htmlspecialchars(mb_substr($novel['title'], 0, 1)); ?></div>
                <h3><?php echo htmlspecialchars($novel['title']); ?></h3>
                <p class="author">by <?php echo htmlspecialchars($novel['author']); ?></p>
            </a>
        <?php endwhile; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
