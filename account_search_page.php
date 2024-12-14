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

    <style>
    .result{
        text-align: center;
        margin: 0 auto;
    }

    .spacer {
      height: 15px; /* 好きな高さに調整 */
    }

    #head {
        width: 100%;
        height: 60px;
        display: flex; /* 子要素を水平に配置 */
        align-items: center; /* 子要素を垂直中央に配置 */
        padding-left: 10px; /* 左寄せ時の余白を追加 */
        font-family: Arial, Helvetica, sans-serif;
        font-size: 23px;
        font-weight: bold; /* 文字を太くする */
        background-color: #fbb89c;
        box-sizing: border-box; /* パディングを含むボックスサイズ計算 */
    }

    #head a {
        text-decoration: none; /* 下線を消す */
        color: inherit; /* 親の色を継承 */
    }

    .back_button {
        font-size: 10px; /* 小さめのフォントサイズ */
        padding: 5px 10px; /* ボタンの内側余白 */
        background-color: #f0f0f0; /* 質素な背景色 */
        color: #333; /* 落ち着いた文字色 */
        border: 1px solid #ccc; /* 控えめな枠線 */
        border-radius: 4px; /* 角を少し丸める */
        cursor: pointer; /* マウスオーバー時にポインタ表示 */
        text-align: center; /* テキスト中央揃え */
        display: inline-block; /* インラインブロック要素 */
        box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* 控えめな影 */
        transition: background-color 0.3s ease; /* ホバー時の変化を滑らかに */
    }

    .back_button:hover {
        background-color: #e0e0e0; /* ホバー時に少し暗くなる */
    }

    .title{
        text-align: center;
        font-family: sans-serif;
    }

    .form{
        width: 60%;
        margin: auto;
        background-color: #b4eaff;
    }

    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント検索</title>
</head>
<body>
    
    <table id="head">
        <tr>
            <th>愛媛新聞社 シフト管理システム</th>
        </tr>
    </table>

    
    <div class="spacer"></div>
    <!-- ホームに戻るボタン -->
    <a href="config.php"><button class="back_button">← ホームに戻る</button></a>

    <h2 class="title">編集するアカウントを検索</h2>

    <!-- 検索フォーム -->
    <form action="account_search_page.php" method="POST" class="form">
        <table class="form">
            <tr>
                <th>社員番号</th>
                <th>
                    <input type="text" name="employeenumber" required>
                    <button type="submit" name="search" value="1">検索</button>
                </th>
            </tr>
        </table>    
    </form>

    <?php

    // ページ更新時に社員番号をリセットし、検索結果を非表示にする
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_POST['employeenumber'] = null;
    }

    // 検索結果表示部分
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeenumber'])) {
        // データベース接続情報
        $host = 'localhost';   // データベースホスト
        $dbname = 'j292toku1'; // データベース名
        $username = 'j292toku';    // MySQLのユーザー名
        $dbpassword = '';        // MySQLのパスワード

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
                echo "<h2 class='title'>検索結果</h2>";
                ?>
                <div class="result">
                <?php
                echo "<p>社員番号: " . htmlspecialchars($user['employeenumber'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>名前: " . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>役職: " . htmlspecialchars($user['position'], ENT_QUOTES, 'UTF-8') . "</p>";
                ?>
                </div>
                
                <h2 class="title">アカウント編集</h2>
                <form action="account_edit.php" method="POST">
                    <input type="hidden" name="employeenumber" value="<?= htmlspecialchars($user['employeenumber'], ENT_QUOTES, 'UTF-8') ?>">

                    <table class="form">
                    <tr>
                    <th>名前</th>
                    <th><input type="text" name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" required></th></tr>

                    <tr><th>新しいパスワード</th>
                    <th><input type="password" name="password"></th></tr>

                    <tr><th>役職</th>
                    <th>
                    <select name="position">
                        <option value="admin" <?= ($user['position'] === 'admin') ? 'selected' : '' ?>>管理者</option>
                        <option value="user" <?= ($user['position'] === 'user') ? 'selected' : '' ?>>ユーザー</option>
                    </select>
                    </th></tr>

                    <tr><th>管理者パスワード</th>
                    <th><input type="password" name="admin_password" required></th></tr>

                    <tr><th colspan="2"><button type="submit" name="edit" value="1">修正</button></tr>
                </table>
                </form>

                <br>

                <!-- 削除フォーム -->
                <h2 class="title"> アカウント削除</h2>
                <form action="account_delete.php" method="POST">
                    <input type="hidden" name="employeenumber" value="<?= htmlspecialchars($user['employeenumber'], ENT_QUOTES, 'UTF-8') ?>">
                    <table class="form">
                    <tr><th>管理者パスワード</th>
                    <th><input type="password" name="admin_password_delete" required></th>
                    <tr><th colspan="2"><button type="submit" name="delete" value="1">削除</button></th></tr>
                </table>
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
