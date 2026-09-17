<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

$novelId = (int)($_GET['novel_id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM novels WHERE novel_id = ?");
mysqli_stmt_bind_param($stmt, "i", $novelId);
mysqli_stmt_execute($stmt);
$novel = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$novel) {
    header("Location: " . BASE_URL . "novels/browse.php");
    exit();
}

$stmt2 = mysqli_prepare($conn, "SELECT chapter_id, chapter_number, chapter_title FROM chapters WHERE novel_id = ? ORDER BY chapter_number ASC");
mysqli_stmt_bind_param($stmt2, "i", $novelId);
mysqli_stmt_execute($stmt2);
$chapters = mysqli_stmt_get_result($stmt2);

// Check ownership / wishlist status if logged in
$owned = false;
$wishlisted = false;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    $ownStmt = mysqli_prepare($conn, "SELECT 1 FROM purchases WHERE user_id = ? AND novel_id = ?");
    mysqli_stmt_bind_param($ownStmt, "ii", $uid, $novelId);
    mysqli_stmt_execute($ownStmt);
    $owned = mysqli_stmt_get_result($ownStmt)->num_rows > 0;

    $wishStmt = mysqli_prepare($conn, "SELECT 1 FROM wishlist WHERE user_id = ? AND novel_id = ?");
    mysqli_stmt_bind_param($wishStmt, "ii", $uid, $novelId);
    mysqli_stmt_execute($wishStmt);
    $wishlisted = mysqli_stmt_get_result($wishStmt)->num_rows > 0;
}

$pageTitle = $novel['title'];
include __DIR__ . '/../includes/header.php';
?>

<section class="novel-details">
    <div class="details-top">
        <div class="novel-cover-placeholder large"><?php echo htmlspecialchars(mb_substr($novel['title'], 0, 1)); ?></div>
        <div class="details-info">
            <h1><?php echo htmlspecialchars($novel['title']); ?></h1>
            <p class="author">by <?php echo htmlspecialchars($novel['author']); ?></p>
            <p class="price"><?php echo $novel['price'] > 0 ? '$' . number_format($novel['price'], 2) : 'Free'; ?></p>
            <p class="description"><?php echo nl2br(htmlspecialchars($novel['description'])); ?></p>

            <div class="action-buttons">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>auth/login.php" class="btn-primary">Login to Read</a>
                <?php elseif ($novel['price'] > 0 && !$owned): ?>
                    <a href="purchase.php?novel_id=<?php echo $novelId; ?>" class="btn-primary">Buy for $<?php echo number_format($novel['price'], 2); ?></a>
                <?php else: ?>
                    <a href="read.php?novel_id=<?php echo $novelId; ?>&chapter=1" class="btn-primary">Start Reading</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>user/wishlist.php" style="display:inline;">
                        <input type="hidden" name="novel_id" value="<?php echo $novelId; ?>">
                        <input type="hidden" name="action" value="<?php echo $wishlisted ? 'remove' : 'add'; ?>">
                        <button type="submit" class="btn-secondary"><?php echo $wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'; ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="chapter-list">
        <h2>Chapters</h2>
        <ul>
            <?php while ($chapter = mysqli_fetch_assoc($chapters)): ?>
                <li>
                    <?php if (isset($_SESSION['user_id']) && ($novel['price'] == 0 || $owned)): ?>
                        <a href="read.php?novel_id=<?php echo $novelId; ?>&chapter=<?php echo $chapter['chapter_number']; ?>">
                            Chapter <?php echo (int)$chapter['chapter_number']; ?>: <?php echo htmlspecialchars($chapter['chapter_title']); ?>
                        </a>
                    <?php else: ?>
                        <span class="locked">🔒 Chapter <?php echo (int)$chapter['chapter_number']; ?>: <?php echo htmlspecialchars($chapter['chapter_title']); ?></span>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
