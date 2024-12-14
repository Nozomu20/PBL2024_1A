<?php
// セッションを開始
session_start();

// フォームが送信された場合
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 社員番号とポジションをセッションに保存
    $employeeNumber = htmlspecialchars($_POST['employee_number']);
    $position = htmlspecialchars($_POST['position']);

    $_SESSION['employeenumber'] = $employeeNumber;
    $_SESSION['position'] = $position;

    // index.php へリダイレクト
    header('Location: config.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
</head>
<body>
    <h1>ログインページ</h1>
    <form action="login.php" method="POST">
        <label for="employee_number">社員番号:</label>
        <input type="text" id="employee_number" name="employee_number" required>
        <br><br>
        <label for="position">ポジション:</label>
        <select id="position" name="position" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <br><br>
        <button type="submit">ログイン</button>
    </form>
</body>
</html>
