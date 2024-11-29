<!-- account_search.php -->

<?php
session_start(); // セッションを開始

/*
// セッションに'role'が保存されているかを確認
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // 'admin'の場合はページを表示
} else {
    // 'admin'でない場合、user_error.phpにリダイレクト
    header('Location: user_error.php');
    exit; // リダイレクト後に処理を停止
}
*/
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント検索</title>
</head>
<body>
    <h1>アカウント検索</h1>

    <!-- ホームに戻るボタン -->
    <a href="index.html"><button>ホームに戻る</button></a>

    <p>編集または削除するアカウントを検索してください。</p>

    <!-- 検索フォーム -->
    <form action="account_search_page.php" method="POST">
        <label for="employee_id">社員番号:</label>
        <input type="text" name="employee_id" id="employee_id" required>
        <button type="submit" name="search" value="1">検索</button>
    </form>

    <?php
    // 検索結果表示部分
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
        // データベース接続情報
        $host = 'localhost';   // データベースホスト
        $dbname = 'j292toku1'; // データベース名
        $username = 'j292toku';    // MySQLのユーザー名
        $dbpassword = '';        // MySQLのパスワード

        $employee_id = $_POST['employee_id'];

        try {
            // データベース接続
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 社員情報を取得
            $sql = "SELECT * FROM user_data WHERE employee_id = :employee_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':employee_id', $employee_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // 社員情報が見つかった場合
                echo "<h2>検索結果</h2>";
                echo "<p>社員番号: " . htmlspecialchars($user['employee_id'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>名前: " . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>役職: " . htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') . "</p>";

                // 編集フォーム
                ?>
                <h2>アカウント編集</h2>
                <form action="account_edit.php" method="POST">
                    <input type="hidden" name="employee_id" value="<?= htmlspecialchars($user['employee_id'], ENT_QUOTES, 'UTF-8') ?>">

                    <label for="name">名前:</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" required><br>

                    <label for="password">新しいパスワード:</label>
                    <input type="password" name="password" id="password"><br>

                    <label for="role">役職:</label>
                    <select name="role" id="role">
                        <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>管理者</option>
                        <option value="user" <?= ($user['role'] === 'user') ? 'selected' : '' ?>>ユーザー</option>
                    </select><br>

                    <label for="admin_password">管理者パスワード:</label>
                    <input type="password" name="admin_password" id="admin_password" required><br>

                    <button type="submit" name="edit" value="1">修正</button>
                </form>

                <br>

                <!-- 削除フォーム -->
                <h2>アカウント削除</h2>
                <form action="account_delete.php" method="POST">
                    <input type="hidden" name="employee_id" value="<?= htmlspecialchars($user['employee_id'], ENT_QUOTES, 'UTF-8') ?>">
                    <label for="admin_password_delete">管理者パスワード:</label>
                    <input type="password" name="admin_password_delete" id="admin_password_delete" required><br>
                    <button type="submit" name="delete" value="1">削除</button>
                </form>
                <?php
            } else {
                // 社員情報が見つからない場合
                echo "<p>社員番号 {$employee_id} の情報は見つかりませんでした。</p>";
            }
        } catch (PDOException $e) {
            // エラー処理
            echo "<p>エラーが発生しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
    ?>
</body>
</html>
