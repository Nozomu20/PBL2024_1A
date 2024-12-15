<?php
// auth_check.php
session_start();
if (!isset($_SESSION['name'])) {
    echo "<p class='error'>ログインしてください。</p>";
    exit;
}
$loggedInName = htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8'); // XSS対策
?>
