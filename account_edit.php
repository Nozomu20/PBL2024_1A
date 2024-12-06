<?php
session_start(); // セッションを開始

// セッションに'position'が保存されているかを確認
if (isset($_SESSION['position']) && $_SESSION['position'] === 'admin') {
    // 'admin'の場合はページを表示
    // セッションに'employeenumber'が保存されているかを確認
    if (isset($_SESSION['employeenumber'])) {
        // セッションに保存されているemployeenumberを使用
        $ad_employeenumber = $_SESSION['employeenumber'];    
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
$test_admin_pass = '1093'; // 仮の管理者パスワード

try {
    // POSTリクエストのチェック
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ユーザーの入力データを取得
        $employeenumber = $_POST['employeenumber'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $position = $_POST['position'];
        $admin_password = $_POST['admin_password'];

        // データベースから管理者パスワードを取得する処理
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT password FROM members WHERE employeenumber = $ad_employeenumber";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin_data) {
            // 該当する管理者が見つからない場合
            header('Location: error.php?source=account_edit');
            exit();
        }

        if (!password_verify($admin_password, $admin_data['password'])) {
            header('Location: error.php?source=account_edit_pass');
            exit();
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
        $sql = "UPDATE members SET name = :name, position = :position";
        if ($hashed_password) {
            $sql .= ", password = :password";
        }
        $sql .= " WHERE employeenumber = :employeenumber";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':position', $position);
        if ($hashed_password) {
            $stmt->bindParam(':password', $hashed_password);
        }
        $stmt->bindParam(':employeenumber', $employeenumber);
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
