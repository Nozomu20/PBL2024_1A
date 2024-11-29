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

<?php
// データベース接続情報
$host = 'localhost';   // データベースホスト
$dbname = 'j292toku1'; // データベース名
$username = 'j292toku';    // MySQLのユーザー名（デフォルトの場合）
$dbpassword = '';        // MySQLのパスワード（デフォルトの場合は空）

// フォームから送信されたデータを取得
$name = $_POST['name'];
$employee_id = $_POST['employee_id'];
$password = $_POST['password'];
$role = $_POST['role'];

// パスワードをハッシュ化（セキュリティのため）
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // MySQLに接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = "INSERT INTO user_data (name, employee_id, password, role) VALUES (:name, :employee_id, :password, :role)";
    
    // SQLの実行
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':employee_id', $employee_id);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    
    // 実行
    $stmt->execute();

    // 成功した場合、success.htmlにリダイレクト（クエリパラメータで送信元を指定）
    header('Location: success.php?source=create_account&name=' . urlencode($name) . '&employee_id=' . urlencode($employee_id) . '&role=' . urlencode($role));
    exit();
} catch (PDOException $e) {
    // エラーが発生した場合、error.htmlにリダイレクト（クエリパラメータで送信元を指定）
    header('Location: error.php?source=create_account');
    exit();
}
