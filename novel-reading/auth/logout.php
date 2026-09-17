<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/constants.php';

$_SESSION = [];
session_unset();
session_destroy();

header("Location: " . BASE_URL . "index.php");
exit();
?>
