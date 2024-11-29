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
$test_admin_pass = '1093'; // 仮の管理者パスワード

try {
    // POSTリクエストのチェック
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ユーザーの入力データを取得
        $employee_id = $_POST['employee_id'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $admin_password = $_POST['admin_password'];

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
        if ($admin_password !== $test_admin_password ) { //check_admin_password
            // 管理者パスワードが間違っている場合
            echo "管理者パスワードが間違っています。";
            header('Location: error.php?source=account_edit');
        }

        // 新しいパスワードをハッシュ化（もし入力されていた場合）
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            // パスワードが空の場合、変更しない
            $hashed_password = null;
        }

        // データベース接続
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ユーザー情報の更新
        $sql = "UPDATE user_data SET name = :name, role = :role";
        if ($hashed_password) {
            $sql .= ", password = :password";
        }
        $sql .= " WHERE employee_id = :employee_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        if ($hashed_password) {
            $stmt->bindParam(':password', $hashed_password);
        }
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();

        // 編集が成功した場合、成功ページへリダイレクト
        header('Location: success.php?source=account_edit');
        exit();
    }
} catch (PDOException $e) {
    // エラーが発生した場合、エラーページにリダイレクト
    header('Location: error.php?source=account_edit');
    exit();
}
?>
