<?php
  session_start();
  $username = "Meet";
  $user_id = 10;
  $_SESSION['user_id'] = $user_id;
  $_SESSION['username'] = $username;
  echo "Login successful";
?>
<!-- ====================================== -->
<!-- ====================================== -->
<!-- ====================================== -->
<!-- ====================================== -->
<!-- ====================================== -->
<!-- ====================================== -->
<!-- ====================================== -->
<form class="login form">
  <h2>Welco=me Back</h2>
  <div class="inputbox">
    <i class="fa-solid fa-envelope"></i>
    <input type="email" placeholder="Email" name="email" autocomplete="email" required />
  </div>

  <div class="inputbox">
    <i class="fa-solid fa-lock"></i>
    <input type="password" placeholder="Password" name="password" autocomplete="current-password" required />
  </div>

  <button type="submit" name="login">Login</button>
</form>
<hr>
<form class="register form">
  <h2>Create Account</h2>

  <div class="inputbox">
    <i class="fa-solid fa-user"></i>
    <input type="text" placeholder="Full Name" autocomplete="name" required />
  </div>

  <div class="inputbox">
    <i class="fa-solid fa-envelope"></i>
    <input type="email" placeholder="Email" autocomplete="email" required />
  </div>

  <div class="inputbox">
    <i class="fa-solid fa-lock"></i>
    <input type="password" placeholder="Password" autocomplete="new-password" required />
  </div>

  <button type="submit" name="register">Register</button>
</form>