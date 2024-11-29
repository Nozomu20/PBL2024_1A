<!--account_user_edit.php-->

<?php
session_start(); // セッションを開始

/*
// セッションに'employee_id'が保存されているかを確認
if (isset($_SESSION['employee_id'])) {
    // セッションに保存されているemployee_idを使用
    $employee_id = $_SESSION['employee_id'];
} else {
    // セッションにemployee_idがない場合の処理（例えば、ログインしていない場合）
    header('Location: error.php?source=account_user_edit');
    exit;
}
*/
?>

<?php
// データベース接続情報
$host = 'localhost';   // データベースホスト
$dbname = 'j292toku1'; // データベース名
$username = 'j292toku';    // MySQLのユーザー名（デフォルトの場合）
$dbpassword = '';        // MySQLのパスワード（デフォルトの場合は空）
$test_employeeid = '1093';

try {
    // POSTリクエストのチェック
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ユーザーの入力データを取得
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // 新しいパスワードと確認用パスワードが一致しているかを確認
        if ($new_password !== $confirm_password) {
            // パスワードが一致しない場合、エラーページにリダイレクト
            header('Location: error.php?source=account_user_edit');
            exit();
        }

        // 現在のパスワードが正しいかどうかを確認するためにDBを照会
        session_start();
        $employee_id =  $test_employeeid; //$_SESSION['employee_id']; // ログインユーザーの社員番号を取得（セッション管理前提）

        // データベース接続
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ユーザー情報を取得
        $sql = "SELECT password FROM user_data WHERE employee_id = :employee_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current_password, $user['password'])) {
            // 現在のパスワードが正しい場合、新しいパスワードを更新

            // 新しいパスワードをハッシュ化
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // パスワードの更新SQL
            $update_sql = "UPDATE user_data SET password = :new_password WHERE employee_id = :employee_id";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindParam(':new_password', $hashed_password);
            $update_stmt->bindParam(':employee_id', $employee_id);
            $update_stmt->execute();

            // パスワード変更成功後、success.htmlにリダイレクト
            header('Location: success.php?source=account_user_edit');
            exit();
        } else {
            // 現在のパスワードが間違っていた場合、エラーページにリダイレクト
            header('Location: error.php?source=account_user_edit');
            exit();
        }
    }
} catch (PDOException $e) {
    // エラーが発生した場合、エラーページにリダイレクト
    header('Location: error.php?source=account_user_edit');
    exit();
}
