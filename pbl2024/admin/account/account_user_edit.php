<?php
session_start(); // セッションを開始

// セッションに'employeenumber'が保存されているか確認
if (isset($_SESSION['employeenumber'])) {
    // セッションに保存されている'employeenumber'を使用
    $log_employeenumber = $_SESSION['employeenumber'];
} else {
    // セッションに'employeenumber'がない場合、エラーページにリダイレクト
    header('Location: error.php?source=account_user_edit');
    exit();
}

?>

<?php
// データベース接続情報
$host = 'localhost';   // データベースホスト
$dbname = 'j292toku1'; // データベース名
$username = 'j292toku'; // MySQLのユーザー名
$dbpassword = ''; // MySQLのパスワード

try {
    // POSTリクエストのチェック
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ユーザーの入力データを取得
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // 新しいパスワードと確認用パスワードが一致しているか確認
        if ($new_password !== $confirm_password) {
            header('Location: error.php?source=account_user_edit');
            exit();
        }

        // データベース接続
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ユーザー情報を取得
        $sql = "SELECT password FROM members WHERE employeenumber = :log_employeenumber";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':log_employeenumber', $log_employeenumber, PDO::PARAM_INT); // 正しいパラメータ型
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password'])) {
            // 現在のパスワードが正しい場合、新しいパスワードを更新

            // 新しいパスワードをハッシュ化
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // パスワード更新SQL
            $update_sql = "UPDATE members SET password = :new_password WHERE employeenumber = :log_employeenumber";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindParam(':new_password', $hashed_password);
            $update_stmt->bindParam(':log_employeenumber', $log_employeenumber, PDO::PARAM_INT);
            $update_stmt->execute();

            // 成功ページにリダイレクト
            header('Location: success.php?source=account_user_edit');
            exit();
        } else {
            // 現在のパスワードが間違っている場合
            header('Location: error.php?source=account_user_edit');
            exit();
        }
    }
} catch (PDOException $e) {
    // データベースエラー発生時
    header('Location: error.php?source=account_user_edit');
    exit();
}
?>
