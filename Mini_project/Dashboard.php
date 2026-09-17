<?php
  error_reporting(E_ERROR | E_PARSE);
  ini_set('display_errors', 1);
  $page = $_GET['page'] ?? 'home';
  function active($name) {
      global $page;
      return $page === $name ? 'active' : '';
  }
  $page = $_GET['page'] ?? 'home'; 
?>
<html>

<head>
  <title>Document</title>
  <?php include_once 'include/Links.php'; ?>
</head>

<body>
  <div class="main">

    <!-- Sidebar -->
    <div class="menubar">

      <div class="sidebar-logo">
        <i class="fa-solid fa-book"></i>
      </div>

      <div class="menu">

        <a href="" class="logo menu-btn" data-page="Home">
          <i class="fa-solid fa-house"></i>
          <span class="tooltip">Home</span>
        </a>

        <a href="#" class="logo menu-btn" data-page="History">
          <i class="fa-solid fa-clock-rotate-left"></i>
          <span class="tooltip">History</span>
        </a>

        <a href="#" class="logo menu-btn" data-page="Wishlist">
          <i class="fa-solid fa-heart"></i>
          <span class="tooltip">Wishlist</span>
        </a>

        <a href="#" class="logo menu-btn" data-page="Library">
          <i class="fa-solid fa-book-open"></i>
          <span class="tooltip">Library</span>
        </a>

        <div class="bottom-menu">
          <a href="#" class="logo menu-btn" data-page="User">
            <i class="fa-solid fa-User"></i>
            <span class="tooltip">User</span>
          </a>
        </div>

      </div>
    </div>

    <!-- Content -->
    <div class="dashboard-content" id="content">
      <?php include_once 'Library.php'; ?>
    </div>

  </div>
</body>
<script>
const buttons = document.querySelectorAll(".menu-btn");
const content = document.getElementById("content");
buttons.forEach(button => {
  button.addEventListener("click", function(e) {
    e.preventDefault();
    buttons.forEach(btn => {
      btn.classList.remove("active");
    });
    this.classList.add("active");
    const page = this.dataset.page;

    // fetch("dashboard/" + page + ".php")
    fetch(page + ".php")
      .then(response => response.text())
      .then(data => {
        content.innerHTML = data;
      })
      .catch(error => {
        content.innerHTML = `        
          <p>Could not load ${page}.</p>
        `;
        console.log(error);
      });
  });
});
</script>

</html>