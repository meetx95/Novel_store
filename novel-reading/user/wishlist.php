<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];

// Handle add/remove actions posted from details.php or this page
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novelId = (int)($_POST['novel_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'add' && $novelId > 0) {
        $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO wishlist (user_id, novel_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ii", $userId, $novelId);
        mysqli_stmt_execute($stmt);
    } elseif ($action === 'remove' && $novelId > 0) {
        $stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE user_id = ? AND novel_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $userId, $novelId);
        mysqli_stmt_execute($stmt);
    }

    // Redirect back to wherever the user came from
    $redirectTo = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'user/wishlist.php');
    header("Location: " . $redirectTo);
    exit();
}

$stmt = mysqli_prepare($conn, "
    SELECT n.novel_id, n.title, n.author, n.price
    FROM wishlist w
    JOIN novels n ON n.novel_id = w.novel_id
    WHERE w.user_id = ?
    ORDER BY w.added_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$novels = mysqli_stmt_get_result($stmt);

$pageTitle = "My Wishlist";
include __DIR__ . '/../includes/header.php';
?>

<section class="wishlist-page">
    <h1>My Wishlist</h1>

    <div class="novel-grid">
        <?php if (mysqli_num_rows($novels) === 0): ?>
            <p>Your wishlist is empty. <a href="<?php echo BASE_URL; ?>novels/browse.php">Browse novels</a> to add some.</p>
        <?php endif; ?>
        <?php while ($novel = mysqli_fetch_assoc($novels)): ?>
            <div class="novel-card">
                <a href="<?php echo BASE_URL; ?>novels/details.php?novel_id=<?php echo (int)$novel['novel_id']; ?>">
                    <div class="novel-cover-placeholder"><?php echo htmlspecialchars(mb_substr($novel['title'], 0, 1)); ?></div>
                    <h3><?php echo htmlspecialchars($novel['title']); ?></h3>
                    <p class="author">by <?php echo htmlspecialchars($novel['author']); ?></p>
                    <p class="price"><?php echo $novel['price'] > 0 ? '$' . number_format($novel['price'], 2) : 'Free'; ?></p>
                </a>
                <form method="POST" action="">
                    <input type="hidden" name="novel_id" value="<?php echo (int)$novel['novel_id']; ?>">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit" class="btn-secondary">Remove</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
