<?php
    require_once 'config/db.php';
    $pageTitle = "Home";
    include 'includes/header.php';
    $featured = mysqli_query($conn, "SELECT novel_id, title, author, price, cover_image FROM novels ORDER BY created_at DESC LIMIT 6");
?>

<section class="hero">
  <h1>Read novels, at your own pace.</h1>
  <p>Browse free and premium novels, build your library, and track your reading — all in one place.</p>
  <a href="<?php echo BASE_URL; ?>novels/browse.php" class="btn-primary">Start Browsing</a>
</section>

<section class="about">
  <h2>What is Novel Reading?</h2>
  <p>
    Novel Reading is a place to discover stories from independent authors. Some novels are free to read,
    others are premium and unlock after a one-time purchase. Once you're logged in, you can build a wishlist,
    keep a personal library of what you own, and pick up exactly where you left off with reading history.
  </p>
</section>

<section class="featured">
  <h2>Featured Novels</h2>
  <div class="novel-grid">
    <?php while ($novel = mysqli_fetch_assoc($featured)): ?>
    <a class="novel-card"
      href="<?php echo BASE_URL; ?>novels/details.php?novel_id=<?php echo (int)$novel['novel_id']; ?>">
      <div class="novel-cover-placeholder"><?php echo htmlspecialchars(mb_substr($novel['title'], 0, 1)); ?></div>
      <h3><?php echo htmlspecialchars($novel['title']); ?></h3>
      <p class="author">by <?php echo htmlspecialchars($novel['author']); ?></p>
      <p class="price"><?php echo $novel['price'] > 0 ? '$' . number_format($novel['price'], 2) : 'Free'; ?></p>
    </a>
    <?php endwhile; ?>
  </div>
</section>

<?php //include 'includes/footer.php'; ?>