<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/library.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = "Please enter both username and password.";
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "SELECT user_id, username, password FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: " . BASE_URL . "user/library.php");
            exit();
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}

$pageTitle = "Login";
include __DIR__ . '/../includes/header.php';
?>

<section class="auth-form">
    <h1>Welcome Back</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">Username or Email</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="btn-primary">Login</button>
    </form>

    <p>Don't have an account? <a href="<?php echo BASE_URL; ?>auth/register.php">Register here</a></p>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
