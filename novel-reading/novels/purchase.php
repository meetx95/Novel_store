<?php
require_once __DIR__ . '/../includes/auth_check.php'; // must be logged in
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['user_id'];
$novelId = (int)($_GET['novel_id'] ?? $_POST['novel_id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM novels WHERE novel_id = ?");
mysqli_stmt_bind_param($stmt, "i", $novelId);
mysqli_stmt_execute($stmt);
$novel = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$novel) {
    header("Location: " . BASE_URL . "novels/browse.php");
    exit();
}

// Already free or already owned? no need to purchase
$ownStmt = mysqli_prepare($conn, "SELECT 1 FROM purchases WHERE user_id = ? AND novel_id = ?");
mysqli_stmt_bind_param($ownStmt, "ii", $userId, $novelId);
mysqli_stmt_execute($ownStmt);
$owns = mysqli_stmt_get_result($ownStmt)->num_rows > 0;

if ($novel['price'] == 0 || $owns) {
    header("Location: " . BASE_URL . "novels/read.php?novel_id=" . $novelId . "&chapter=1");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mock payment step — plug in a real payment gateway here (e.g. Stripe/Razorpay) later.
    // For this mini project, "confirming" the form simulates a successful payment.
    $cardNumber = trim($_POST['card_number'] ?? '');

    if (strlen(preg_replace('/\s+/', '', $cardNumber)) < 12) {
        $error = "Please enter a valid card number.";
    } else {
        $insertStmt = mysqli_prepare($conn, "INSERT INTO purchases (user_id, novel_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($insertStmt, "ii", $userId, $novelId);
        mysqli_stmt_execute($insertStmt);

        header("Location: " . BASE_URL . "novels/read.php?novel_id=" . $novelId . "&chapter=1");
        exit();
    }
}

$pageTitle = "Checkout — " . $novel['title'];
include __DIR__ . '/../includes/header.php';
?>

<section class="checkout">
    <h1>Checkout</h1>
    <div class="checkout-summary">
        <h2><?php echo htmlspecialchars($novel['title']); ?></h2>
        <p>by <?php echo htmlspecialchars($novel['author']); ?></p>
        <p class="price">Total: $<?php echo number_format($novel['price'], 2); ?></p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="checkout-form">
        <input type="hidden" name="novel_id" value="<?php echo $novelId; ?>">

        <label for="card_number">Card Number</label>
        <input type="text" id="card_number" name="card_number" placeholder="4242 4242 4242 4242" required>

        <div class="form-row">
            <div>
                <label for="expiry">Expiry</label>
                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
            </div>
            <div>
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" required>
            </div>
        </div>

        <button type="submit" class="btn-primary">Pay $<?php echo number_format($novel['price'], 2); ?></button>
        <p class="note">This is a mock payment form for demo purposes — no real transaction occurs.</p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
