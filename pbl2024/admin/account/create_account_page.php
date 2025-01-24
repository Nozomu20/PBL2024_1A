<!--仮のページです-->

<?php
session_start(); // セッションを開始


// セッションに'role'が保存されているかを確認
if (isset($_SESSION['position']) && $_SESSION['position'] === 'admin') {
    // 'admin'の場合はページを表示
} else {
    // 'admin'でない場合、user_error.phpにリダイレクト
    header('Location: user_error.php');
    exit; // リダイレクト後に処理を停止
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント作成ページ</title>
</head>
<body>
    <h1>アカウント作成ページ</h1>
    <p>ここではアカウントを作成します。</p>

    <!-- ユーザー情報を入力するフォーム -->
    <form action="create_account.php" method="POST">
        <label for="name">名前:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="employee_id">社員番号:</label><br>
        <input type="text" id="employee_id" name="employeenumber" required><br><br>

        <label for="password">パスワード:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">ロール:</label><br>
        <select id="role" name="position" required>
            <option value="admin">管理者</option>
            <option value="user">ユーザー</option>
        </select><br><br>

        <button type="submit">アカウント作成</button>
    </form>

    <br>
    <a href="index.php">ホームに戻る</a>
</body>
</html>
