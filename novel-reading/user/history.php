<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "
    SELECT n.novel_id, n.title, n.author, c.chapter_number, c.chapter_title, h.last_read_at
    FROM reading_history h
    JOIN novels n ON n.novel_id = h.novel_id
    JOIN chapters c ON c.chapter_id = h.chapter_id
    WHERE h.user_id = ?
    ORDER BY h.last_read_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$history = mysqli_stmt_get_result($stmt);

$pageTitle = "Reading History";
include __DIR__ . '/../includes/header.php';
?>

<section class="history-page">
    <h1>Reading History</h1>

    <div class="history-list">
        <?php if (mysqli_num_rows($history) === 0): ?>
            <p>You haven't started reading anything yet. <a href="<?php echo BASE_URL; ?>novels/browse.php">Browse novels</a> to begin.</p>
        <?php endif; ?>
        <?php while ($row = mysqli_fetch_assoc($history)): ?>
            <div class="history-item">
                <div>
                    <h3><a href="<?php echo BASE_URL; ?>novels/details.php?novel_id=<?php echo (int)$row['novel_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                    <p>by <?php echo htmlspecialchars($row['author']); ?></p>
                    <p class="progress">Last read: Chapter <?php echo (int)$row['chapter_number']; ?> — <?php echo htmlspecialchars($row['chapter_title']); ?></p>
                    <p class="timestamp"><?php echo date('M j, Y g:i A', strtotime($row['last_read_at'])); ?></p>
                </div>
                <a class="btn-primary" href="<?php echo BASE_URL; ?>novels/read.php?novel_id=<?php echo (int)$row['novel_id']; ?>&chapter=<?php echo (int)$row['chapter_number']; ?>">Continue</a>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
