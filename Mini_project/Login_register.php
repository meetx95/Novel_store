<html>

<head>
  <title>Login & Register</title>
  <?php include_once 'include/Links.php'; ?>
  <link rel="stylesheet" href="./Assets/Design/auth.css">
</head>

<body>
  <div class="main">
    <div class="page">
      <center>
        <div class="container">
          <input type="radio" name="form" id="login" checked />
          <input type="radio" name="form" id="register" />

          <div class="switch">
            <label for="login">Login</label>
            <label for="register">Register</label>
            <div class="slider"></div>
          </div>

          <div class="forms">
            <div class="forms-track">

              <form class="login form">
                <h2>Welcome Back</h2>
                <div class="inputbox">
                  <i class="fa-solid fa-envelope"></i>
                  <input type="email" placeholder="Email" name="email" autocomplete="email" required />
                </div>

                <div class="inputbox">
                  <i class="fa-solid fa-lock"></i>
                  <input type="password" placeholder="Password" name="password" autocomplete="current-password"
                    required />
                </div>

                <!-- <button type="submit" name="login"> -->
                <button>
                  <a href="Dashboard.php">Login</a>
                </button>

                <p>Forgot Password?</p>
              </form>

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

                <button type="submit">Register</button>
              </form>

            </div>
          </div>

        </div>
      </center>
    </div>

  </div>
</body>

</html>
<?php
  // session_start();
  // $email = "";
  // $password = "";
  // if ($_POST['email'] == $email && $_POST['password'] == $password) {
  //   $_SESSION['user'] = $email;
  //   echo "Login Successful";
  // }
  // else {
  //   echo "Invalid Login";
  // }
?>