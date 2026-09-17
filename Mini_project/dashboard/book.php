<?php
  // $json = file_get_contents("./books/book25.json");
  $json = file_get_contents("../Assets/Book/book25.json");
  $book = json_decode($json, true);
  $chapterIndex = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 0;
  if (!isset($book['chapters'][$chapterIndex])) {
    $chapterIndex = 0;
  }
  $chapter = $book['chapters'][$chapterIndex];
?>
<html>

<head>
  <title>
    <?php 
      echo $book['title']; 
      echo $chapter['title']; 
    ?>
  </title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="./Design/main-root.css">
  <link rel="stylesheet" href="./Design/design.css">

</head>

<body>
  <div class="main">

    <div class="reader">

      <!-- left sidebar -->
      <aside class="reader-sidebar">
        <h3>Chapters</h3>
        <?php foreach ($book['chapters'] as $index => $item) { ?>
        <a href="?chapter=<?php echo $index; ?>"
          class="chapter-item<?php echo ($index == $chapterIndex) ? 'active' : ''; ?>">
          <span class="chapter-number">
            Chapter <?php echo $item['chapter_number']; ?>
          </span>
          <span class="chapter-title">
            <?php 
              echo ($item['title']); 
            ?>
          </span>
        </a>
        <?php } ?>
      </aside>

      <main class="reader-content">
        <div class="reader-topbar">
          <div class="reader-topbar-left">
            <button class="reader-btn">
              <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
          </div>

          <div class="reader-topbar-right">
            <button class="reader-btn">
              <i class="fa fa-bookmark" aria-hidden="true"></i>
            </button>
            <a href="./index.php" class="reader-btn">
              <i class="fa fa-home" aria-hidden="true"></i>
            </a>
          </div>
        </div>

        <header class="reader-header">
          <h1 class="book-title">
            <?php 
              echo $book['title']; 
            ?>
          </h1>
          <div class="reader-divider"></div>
          <h2 class="chapter-title">
            Chapter
            <?php 
              echo $chapter['chapter_number'] . " : " . $chapter['title']; 
            ?>
          </h2>
        </header>

        <article class="book-content">
          <?php 
          foreach ($chapter['content'] as $paragraph) { 
          ?>
          <p>
            <?php echo ($paragraph); ?>
          </p>
          <?php } ?>
        </article>


        <!-- pre / nect -->
        <div class="reader-footer">
          <?php 
            if ($chapterIndex > 0) { 
          ?>
          <a href="?chapter=
            <?php echo $chapterIndex - 1; ?>" class="reader-nav-btn">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>Previous Chapter
          </a>
          <?php 
            } 
            else { 
          ?>

          <span></span>
          <?php } ?>

          <?php if ($chapterIndex < count($book['chapters']) - 1) { ?>
          <a href="?chapter=<?php echo $chapterIndex + 1; ?>" class="reader-nav-btn">
            Next Chapter<i class="fa fa-chevron-right" aria-hidden="true"></i>
          </a>
          <?php } ?>

        </div>
      </main>
    </div>
  </div>

</body>

</html>
<style>
.reader {
  width: 100%;
  height: 100%;
  display: flex;
  overflow: hidden;
  background: var(--bg);
  border-radius: var(--radius-md);
}

.reader-sidebar {
  width: var(--sidebar-width);
  height: 100%;
  flex-shrink: 0;
  padding: 24px 14px;
  background: var(--surface);
  border-right: var(--border);
  overflow-y: auto;
}

.reader-sidebar::-webkit-scrollbar {
  width: 5px;
}

.reader-sidebar::-webkit-scrollbar-thumb {
  background: var(--border-dark);
  border-radius: var(--radius-pill);
}

.reader-sidebar h3 {
  padding: 0 10px;
  margin-bottom: 18px;
  font-family: var(--font-heading);
  font-size: var(--fs-lg);
  font-weight: 900;
  color: var(--primary-dark);
}

.chapter-item {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 3px;
  padding: 12px 14px;
  margin-bottom: 6px;
  border: 1px solid transparent;
  border-radius: var(--radius-sm);
  background: transparent;
  transition: var(--tra);
  text-decoration: none;
}

.chapter-item:hover {
  background: var(--surface-2);
  border-color: var(--border-light);
  color: var(--primary);
  transform: translateX(3px);
}

.chapter-item.active {
  background: var(--accent);
  border-color: var(--secondary);
  color: var(--primary-dark);
  box-shadow: var(--shadow-sm);
}

.chapter-item.active::before {
  content: "";
  position: absolute;
  left: -1px;
  top: 50%;
  width: 4px;
  height: 35px;
  transform: translateY(-50%);
  background: var(--primary);
  border-radius: 0 var(--radius-pill) var(--radius-pill) 0;
}

.chapter-number {
  font-size: var(--fs-xs);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--text-muted);
}

.chapter-item.active .chapter-number {
  color: var(--primary);
}


.chapter-title {
  font-size: var(--fs-sm);
  font-weight: 700;
  line-height: 1.4;
  color: var(--text);
}

.chapter-item:hover .chapter-title {
  color: var(--primary);
}

.chapter-item.active .chapter-title {
  color: var(--primary-dark);
}

.reader-content {
  position: relative;
  flex: 1;
  min-width: 0;
  height: 100%;
  display: flex;
  flex-direction: column;
  background: var(--bg-light);
  overflow-y: auto;
}

.reader-content::-webkit-scrollbar {
  width: 6px;
}

.reader-content::-webkit-scrollbar-thumb {
  background: var(--border-dark);
  border-radius: var(--radius-pill);
}

.reader-topbar {
  position: sticky;
  top: 0;
  z-index: 20;
  width: 100%;
  min-height: 60px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 20px;
  background: rgba(255, 253, 249, .90);
  border-bottom: 1px solid var(--border-light);
  backdrop-filter: blur(12px);
}

.reader-topbar-left,
.reader-topbar-right {
  display: flex;
  align-items: center;
  gap: 8px;
}

.reader-btn {
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  border: 1px solid var(--border-light);
  border-radius: var(--radius-sm);
  background: var(--surface);
  color: var(--text-secondary);
  box-shadow: var(--shadow-sm);
  font-size: var(--fs-md);
  transition: var(--tra-fast);
}

.reader-btn:hover {
  background: var(--surface-2);
  border-color: var(--border-dark);
  color: var(--primary);
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.reader-btn:active {
  transform: scale(.94);
}

.reader-btn i {
  color: inherit;
}

.reader-header {
  width: min(850px, 90%);
  margin: 60px auto 45px;
  text-align: center;
}

.book-title {
  margin-bottom: 18px;
  font-family: var(--font-heading);
  font-size: var(--fs-3xl);
  font-weight: 900;
  line-height: 1.15;
  color: var(--primary-dark);
}

.reader-divider {
  width: 65px;
  height: 4px;
  margin: 0 auto 20px;
  background: var(--gradient-primary);
  border-radius: var(--radius-pill);
}

.reader-header .chapter-title {
  font-family: var(--font-heading);
  font-weight: 700;
  line-height: 1.4;
  color: var(--text-secondary);
}

.book-content {
  width: min(850px, 90%);
  margin: 0 auto;
  padding-bottom: 30px;
  font-family: Georgia, "Times New Roman", serif;
  font-size: 19px;
  line-height: 2;
  color: var(--text);
}

.book-content p {
  margin-bottom: 28px;
  text-align: justify;
  letter-spacing: .1px;
}

.book-content p:first-of-type::first-letter {
  float: left;
  margin-right: 9px;
  font-size: 60px;
  font-weight: bold;
  line-height: 48px;
  color: var(--primary);
}

.book-content p::selection {
  background: var(--accent);
  color: var(--primary-dark);
}

.reader-footer {
  width: min(850px, 90%);
  margin: 30px auto 70px;
  padding-top: 25px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid var(--border-color);
}

.reader-nav-btn {
  min-width: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 9px;
  padding: 12px 18px;
  border: 1px solid var(--border-light);
  border-radius: var(--radius-sm);
  background: var(--surface);
  color: var(--text-secondary);
  box-shadow: var(--shadow-sm);
  font-size: var(--fs-sm);
  font-weight: 700;
  text-decoration: none;
  transition: var(--tra);
}

.reader-nav-btn:hover {
  background: var(--surface-2);
  border-color: var(--border-dark);
  color: var(--primary);
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.reader-nav-btn i {
  color: inherit;
}

.reader-nav-btn:active {
  transform: scale(.97);
}

.reader-footer>span {
  width: 180px;
}
</style>