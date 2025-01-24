<?php
session_start(); // セッションを開始

// セッションに'role'が保存されているかを確認
if (isset($_SESSION['position']) && $_SESSION['position'] === 'admin') {
    // 'admin'の場合はページを表示
    // セッションに'employeenumber'が保存されているかを確認
    if (isset($_SESSION['employeenumber'])) {
        // セッションに保存されているemployeenumberを使用
        $admin_employeenumber = $_SESSION['employeenumber'];    
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
        $employeenumber = $_POST['employeenumber'];
        $admin_password = $_POST['admin_password_delete'];

        // データベースから管理者パスワードを取得する処理
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 管理者のパスワードを取得
        $sql = "SELECT password FROM members WHERE employeenumber = :admin_employeenumber";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':admin_employeenumber', $admin_employeenumber, PDO::PARAM_INT);
        $stmt->execute();
        $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin_data || !password_verify($admin_password, $admin_data['password'])) {
            // 管理者パスワードが間違っている場合
            header('Location: error.php?source=account_edit');
            exit();
        }

        // ユーザーアカウントの削除
        $sql = "DELETE FROM members WHERE employeenumber = :employeenumber";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employeenumber', $employeenumber, PDO::PARAM_INT);
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
