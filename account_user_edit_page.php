<!--仮のページです-->

<?php
session_start(); // セッションを開始
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード変更</title>
</head>

<body>
    <h1>パスワード変更</h1>

    <!-- 現在のパスワード、新しいパスワード、新しいパスワード確認のフォーム -->
    <form action="account_user_edit.php" method="POST">
        <label for="current_password">現在のパスワード:</label>
        <input type="password" name="current_password" id="current_password" required><br>

        <label for="new_password">新しいパスワード:</label>
        <input type="password" name="new_password" id="new_password" required><br>

        <label for="confirm_password">新しいパスワード確認:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br>

        <button type="submit">パスワード変更</button>
    </form>

    <br>
    <a href="index.php"><button>ホームに戻る</button></a>
</body>

</html>