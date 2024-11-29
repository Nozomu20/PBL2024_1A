<?php
session_start(); // セッションを開始

/*
// セッションに'role'が保存されているかを確認
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // 'admin'の場合はページを表示
    // セッションに'employee_id'が保存されているかを確認
    if (isset($_SESSION['employee_id'])) {
        // セッションに保存されているemployee_idを使用
        $employee_id = $_SESSION['employee_id'];    
    } else {
        // employee_idがない場合の処理
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

<?php
// データベース接続情報
$host = 'localhost';   // データベースホスト
$dbname = 'j292toku1'; // データベース名
$username = 'j292toku';    // MySQLのユーザー名
$dbpassword = '';        // MySQLのパスワード
$test_admin_password = '1093'; // 仮の管理者パスワード

try {
    // POSTリクエストのチェック
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ユーザーの入力データを取得
        $employee_id = $_POST['employee_id'];
        $admin_password = $_POST['admin_password_delete'];

        /*
        // データベースから管理者パスワードを取得する処理
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT password FROM user_data WHERE employee_id = 1";  // 仮のID、適切な条件に変更
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin_password !== $admin_data['password']) {
            echo "管理者パスワードが間違っています。";
            header('Location: error.php?source=account_edit');
            exit();
        }
        */

        // 管理者パスワードの確認
        if ($admin_password !== $test_admin_password) {
            // 管理者パスワードが間違っている場合
            echo "管理者パスワードが間違っています。";
            header('Location: error.php?source=account_edit');
        }

        // データベース接続
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ユーザーアカウントの削除
        $sql = "DELETE FROM user_data WHERE employee_id = :employee_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();

        // 削除が成功した場合、成功ページへリダイレクト
        header('Location: success.php?source=account_delete');
        exit();
    }
} catch (PDOException $e) {
    // エラーが発生した場合、エラーページにリダイレクト
    header('Location: error.php?source=account_delete');
    exit();
}
?>
