<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT username, email, created_at FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();

// Quick stats
$ownedCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM purchases WHERE user_id = $userId"))['c'];
$wishCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM wishlist WHERE user_id = $userId"))['c'];
$readCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM reading_history WHERE user_id = $userId"))['c'];

$successMsg = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = trim($_POST['email'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';

    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($errors)) {
        if ($newPassword !== '') {
            if (strlen($newPassword) < 6) {
                $errors[] = "New password must be at least 6 characters.";
            } else {
                $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $upd = mysqli_prepare($conn, "UPDATE users SET email = ?, password = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($upd, "ssi", $newEmail, $hashed, $userId);
                mysqli_stmt_execute($upd);
            }
        } else {
            $upd = mysqli_prepare($conn, "UPDATE users SET email = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($upd, "si", $newEmail, $userId);
            mysqli_stmt_execute($upd);
        }

        if (empty($errors)) {
            $successMsg = "Profile updated successfully.";
            $user['email'] = $newEmail;
        }
    }
}

$pageTitle = "My Profile";
include __DIR__ . '/../includes/header.php';
?>

<section class="profile-page">
    <h1>My Profile</h1>

    <div class="profile-stats">
        <div class="stat"><span class="num"><?php echo $ownedCount; ?></span><span>Novels Owned</span></div>
        <div class="stat"><span class="num"><?php echo $wishCount; ?></span><span>Wishlist</span></div>
        <div class="stat"><span class="num"><?php echo $readCount; ?></span><span>In Progress</span></div>
    </div>

    <?php if ($successMsg): ?><div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div><?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="profile-form">
        <label>Username</label>
        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="new_password">New Password <small>(leave blank to keep current)</small></label>
        <input type="password" id="new_password" name="new_password">

        <p class="note">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>

        <button type="submit" class="btn-primary">Save Changes</button>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
