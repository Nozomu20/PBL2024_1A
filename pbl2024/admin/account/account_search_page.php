<!-- account_search.php -->

<?php

session_start(); // セッションを開始


// セッションに'position'が保存されているかを確認
if (isset($_SESSION['position']) && $_SESSION['position'] === 'admin') {
    // 'admin'の場合はページを表示
} else {
    // 'admin'でない場合、user_error.phpにリダイレクト
    header('Location: user_error.php');
    exit; // リダイレクト後に処理を停止
}

/*
if (isset($_SESSION['position']) && $_SESSION['position'] === 'admin') {
    // 'admin'の場合はページを表示
    // セッションに'employeenumber'が保存されているかを確認
    if (isset($_SESSION['employeenumber'])) {
        // セッションに保存されているemployeenumberを使用
        $employeenumber = $_SESSION['employeenumber'];    
    } else {
        // employeenumberがない場合の処理
        header('Location: error.php?source=account_edit');
        exit;
    }
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
    <link rel="stylesheet" type="text/css" href="../../home.css" />
    <title>アカウント検索</title>
</head>
<body>
    <div class="header">
            <a href="../../home.php"><h1>愛媛新聞社 シフト管理システム</h1></a>
    </div>
    <button>設定</button>
    <div class="logout">
        <span><?php echo $name;?> さん</span>
        <button onclick="location.href='staff_logout.php'">ログアウト</button>
    </div>  
    <h1>アカウント検索</h1>

    <!-- ホームに戻るボタン -->
    <a href="../../home.php"><button>ホームに戻る</button></a>

    <p>編集または削除するアカウントを検索してください。</p>

    <!-- 検索フォーム -->
    <form action="account_search_page.php" method="POST">
        <label for="employeenumber">社員番号:</label>
        <input type="text" name="employeenumber" id="employeenumber" required>
        <button type="submit" name="search" value="1">検索</button>
    </form>

    <?php
    // 検索結果表示部分
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeenumber'])) {
        // データベース接続情報
        $host = 'localhost';   // データベースホスト
        $dbname = 'pbl2024'; // データベース名
        $username = 'kk';    // MySQLのユーザー名
        $dbpassword = 'koblab';        // MySQLのパスワード

        $employeenumber = $_POST['employeenumber'];

        try {
            // データベース接続
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 社員情報を取得
            $sql = "SELECT * FROM members WHERE employeenumber = :employeenumber";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':employeenumber', $employeenumber);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // 社員情報が見つかった場合
                echo "<h2>検索結果</h2>";
                echo "<p>社員番号: " . htmlspecialchars($user['employeenumber'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>名前: " . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>役職: " . htmlspecialchars($user['position'], ENT_QUOTES, 'UTF-8') . "</p>";

                // 編集フォーム
                ?>
                <h2>アカウント編集</h2>
                <form action="account_edit.php" method="POST">
                    <input type="hidden" name="employeenumber" value="<?= htmlspecialchars($user['employeenumber'], ENT_QUOTES, 'UTF-8') ?>">

                    <label for="name">名前:</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" required><br>

                    <label for="password">新しいパスワード:</label>
                    <input type="password" name="password" id="password"><br>

                    <label for="position">役職:</label>
                    <select name="position" id="position">
                        <option value="admin" <?= ($user['position'] === 'admin') ? 'selected' : '' ?>>管理者</option>
                        <option value="user" <?= ($user['position'] === 'user') ? 'selected' : '' ?>>ユーザー</option>
                    </select><br>

                    <label for="admin_password">管理者パスワード:</label>
                    <input type="password" name="admin_password" id="admin_password" required><br>

                    <button type="submit" name="edit" value="1">修正</button>
                </form>

                <br>

                <!-- 削除フォーム -->
                <h2>アカウント削除</h2>
                <form action="account_delete.php" method="POST">
                    <input type="hidden" name="employeenumber" value="<?= htmlspecialchars($user['employeenumber'], ENT_QUOTES, 'UTF-8') ?>">
                    <label for="admin_password_delete">管理者パスワード:</label>
                    <input type="password" name="admin_password_delete" id="admin_password_delete" required><br>
                    <button type="submit" name="delete" value="1">削除</button>
                </form>
                <?php
            } else {
                // 社員情報が見つからない場合
                echo "<p>社員番号 {$employeenumber} の情報は見つかりませんでした。</p>";
            }
        } catch (PDOException $e) {
            // エラー処理
            echo "<p>エラーが発生しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
    ?>
</body>
</html>
