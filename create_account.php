<?php
session_start(); // セッションを開始


// セッションに'role'が保存されているかを確認
if (isset($_SESSION['position']) && $_SESSION['position'] === 'admin') {
    // 'admin'の場合はページを表示
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
$username = 'j292toku';    // MySQLのユーザー名（デフォルトの場合）
$dbpassword = '';        // MySQLのパスワード（デフォルトの場合は空）

// フォームから送信されたデータを取得
$name = $_POST['name'];
$employeenumber = $_POST['employeenumber'];
$password = $_POST['password'];
$position = $_POST['position'];

// パスワードをハッシュ化（セキュリティのため）
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 作成日時を取得（現在の日時を使用）
$created = date('Y-m-d H:i:s');

try {
    // MySQLに接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = "INSERT INTO members (name, employeenumber, password, position, created) VALUES (:name, :employeenumber, :password, :position, :created)";
    
    // SQLの実行
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':employeenumber', $employeenumber);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':position', $position);
    $stmt->bindParam(':created', $created);
    
    // 実行
    $stmt->execute();

    // 成功した場合、success.htmlにリダイレクト（クエリパラメータで送信元を指定）
    header('Location: success.php?source=create_account&name=' . urlencode($name) . '&employeenumber=' . urlencode($employeenumber) . '&position=' . urlencode($position));
    exit();
} catch (PDOException $e) {
    // エラーが発生した場合、error.htmlにリダイレクト（クエリパラメータで送信元を指定）
    header('Location: error.php?source=create_account');
    exit();
}
?>
