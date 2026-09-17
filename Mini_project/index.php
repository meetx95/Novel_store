<html>

<head>
  <title>NOVELSTORE</title>
  <?php include_once 'include/Links.php'; ?>
</head>
<style>
.main {
  display: block;
}
</style>

<body>
  <div class="main">
    <header class="navbar">

      <div class="text-logo">
        <span>NOVELSTORE</span>
      </div>

      <div class="login_register">
        <a href="Login_register.php" class="btn btn-login">Login</a>
        <a href="Login_register.php" class="btn btn-register">Register</a>
      </div>

    </header>

    <section class="info">

      <div class="info-content">

        <h1>Discover Your <span>Next Story</span></h1>
        <p class="info-description">
          Read your favorite Novel, Books, Manga and Manhwa all in one place. Find stories
          that match your mood.
        </p>

        <div class="info-buttons">
          <a href="Login_register.php" class="primary-btn">Start Reading →</a>
          <a href="./dashboard/Library.php" class="secondary-btn">Explore Books</a>
        </div>

      </div>

      <div class="info-books">
        <div class="book book-1"><i class="fas fa-book book-icon"></i><img src="Assets/Cover/coverH.jpg" alt=""></div>
        <div class="book book-2"><i class="fas fa-book-open book-icon"></i><img src="Assets/Cover/coverF.jpg" alt="">
        </div>
        <div class="book book-3"><i class="fas fa-bookmark book-icon"></i><img src="Assets/Cover/coverD.jpg" alt="">
        </div>
      </div>

    </section>

    <section class="about">

      <div class="section-label">ABOUT NOVELSTORE</div>
      <h2>A Place Where <span>Stories Live.</span></h2>
      <p class="section-description">
        NOVELSTORE is an online reading platform designed for people who love discovering and reading stories. Browse
        books, save favorites, track your reading and enjoy your personal digital library.
      </p>

    </section>

  </div>
</body>

</html>