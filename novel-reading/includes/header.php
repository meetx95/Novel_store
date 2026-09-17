<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../config/constants.php';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Novel Reading' : 'Novel Reading'; ?></title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>

<body>
  <header class="site-header">
    <div class="header-inner">
      <a href="<?php echo BASE_URL; ?>index.php" class="logo">Novel Reading</a>
      <nav class="main-nav">
        <a href="<?php echo BASE_URL; ?>novels/browse.php">Browse</a>
        <?php if ($isLoggedIn): ?>
        <a href="<?php echo BASE_URL; ?>user/library.php">My Library</a>
        <a href="<?php echo BASE_URL; ?>user/wishlist.php">Wishlist</a>
        <a href="<?php echo BASE_URL; ?>user/history.php">History</a>
        <a href="<?php echo BASE_URL; ?>user/profile.php">Profile</a>
        <a href="<?php echo BASE_URL; ?>auth/logout.php" class="btn-logout">Logout</a>
        <?php else: ?>
        <a href="<?php echo BASE_URL; ?>auth/login.php">Login</a>
        <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn-primary">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="site-main">