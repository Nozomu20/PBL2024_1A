<?php
session_start();

/*
try {
    $dbh = new PDO('mysql:host=localhost;dbname=j024ishi4;charset=utf8', 'j024ishi', '');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print 'データベース接続エラー: ' . $e->getMessage();
    exit();
}
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeenumber = $_POST['employeenumber'] ?? '';
    $password = $_POST['pass'] ?? '';
    
    // 入力チェック
    if (empty($employeenumber) || empty($password)) {
        print '社員番号とパスワードを入力してください。<br/>';
        print '<a href="master_login.php">戻る</a>';
        exit();
    }

    try {
        // データベース接続
        $dbh = new PDO('mysql:host=localhost;dbname=j024ishi4;charset=utf8', 'j024ishi', '');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 管理者であるかを確認するSQL
        $sql = 'SELECT name, position FROM members WHERE employeenumber = :employeenumber AND password = :password AND position = "admin"';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':employeenumber', $employeenumber, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // 管理者ログイン成功
            $_SESSION['login'] = true;
            $_SESSION['name'] = $result['name'];
            $_SESSION['position'] = $result['position'];
            header('Location: master_list.php'); // 管理者専用ページ
            exit();
        } else {
            // ログイン失敗
            print '社員番号またはパスワードが正しくありません。<br/>';
            print '<a href="master_login.php">戻る</a>';
            exit();
        }

    } catch (PDOException $e) {
        print 'データベースエラー: ' . $e->getMessage();
        exit();
    }
} else {
    // 不正なアクセス
    header('Location: master_login.php');
    exit();
}
?>
