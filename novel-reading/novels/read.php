<?php
require_once __DIR__ . '/../includes/auth_check.php'; // must be logged in
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];
$novelId = (int)($_GET['novel_id'] ?? 0);
$chapterNum = (int)($_GET['chapter'] ?? 1);

// Get the novel
$stmt = mysqli_prepare($conn, "SELECT * FROM novels WHERE novel_id = ?");
mysqli_stmt_bind_param($stmt, "i", $novelId);
mysqli_stmt_execute($stmt);
$novel = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$novel) {
    header("Location: " . BASE_URL . "novels/browse.php");
    exit();
}

// Payment gate: if novel has a price, the user must own it
if ($novel['price'] > 0) {
    $ownStmt = mysqli_prepare($conn, "SELECT 1 FROM purchases WHERE user_id = ? AND novel_id = ?");
    mysqli_stmt_bind_param($ownStmt, "ii", $userId, $novelId);
    mysqli_stmt_execute($ownStmt);
    $owns = mysqli_stmt_get_result($ownStmt)->num_rows > 0;

    if (!$owns) {
        header("Location: " . BASE_URL . "novels/purchase.php?novel_id=" . $novelId);
        exit();
    }
}

// Get the requested chapter
$chStmt = mysqli_prepare($conn, "SELECT * FROM chapters WHERE novel_id = ? AND chapter_number = ?");
mysqli_stmt_bind_param($chStmt, "ii", $novelId, $chapterNum);
mysqli_stmt_execute($chStmt);
$chapter = mysqli_stmt_get_result($chStmt)->fetch_assoc();

if (!$chapter) {
    header("Location: " . BASE_URL . "novels/details.php?novel_id=" . $novelId);
    exit();
}

// Total chapter count for prev/next navigation
$countResult = mysqli_query($conn, "SELECT MAX(chapter_number) AS max_ch FROM chapters WHERE novel_id = " . $novelId);
$maxChapter = mysqli_fetch_assoc($countResult)['max_ch'];

// Log / update reading progress
$logStmt = mysqli_prepare($conn, "
    INSERT INTO reading_history (user_id, novel_id, chapter_id)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE chapter_id = VALUES(chapter_id), last_read_at = CURRENT_TIMESTAMP
");
mysqli_stmt_bind_param($logStmt, "iii", $userId, $novelId, $chapter['chapter_id']);
mysqli_stmt_execute($logStmt);

$pageTitle = $novel['title'] . " — Chapter " . $chapterNum;
include __DIR__ . '/../includes/header.php';
?>

<section class="reader">
    <div class="reader-top">
        <h1><?php echo htmlspecialchars($novel['title']); ?></h1>
        <h2>Chapter <?php echo (int)$chapter['chapter_number']; ?>: <?php echo htmlspecialchars($chapter['chapter_title']); ?></h2>
    </div>

    <div class="reader-content">
        <?php echo nl2br(htmlspecialchars($chapter['content'])); ?>
    </div>

    <div class="reader-nav">
        <?php if ($chapterNum > 1): ?>
            <a href="read.php?novel_id=<?php echo $novelId; ?>&chapter=<?php echo $chapterNum - 1; ?>" class="btn-secondary">&larr; Previous</a>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>novels/details.php?novel_id=<?php echo $novelId; ?>" class="btn-secondary">Chapter List</a>

        <?php if ($chapterNum < $maxChapter): ?>
            <a href="read.php?novel_id=<?php echo $novelId; ?>&chapter=<?php echo $chapterNum + 1; ?>" class="btn-primary">Next &rarr;</a>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
