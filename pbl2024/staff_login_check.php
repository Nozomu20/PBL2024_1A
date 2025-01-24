<?php
    ini_set('display_errors', "On");

    try {
        // POSTデータを取得
        $employeenumber = $_POST['employeenumber'];
        $pass = $_POST['pass'];

        // HTMLエスケープ処理
        $employeenumber = htmlspecialchars($employeenumber, ENT_QUOTES, 'UTF-8');
        $pass = htmlspecialchars($pass, ENT_QUOTES, 'UTF-8');
        
        // パスワードのハッシュ化（必要に応じて利用）
        //$pass = md5($pass);

        // データベース接続情報
        $dsn = 'mysql:dbname=pbl2024;host=localhost;charset=utf8';
        $user = 'kk';
        $password = 'koblab';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQLクエリ準備（`position`カラムはログインに利用しないので取得しない）
        $sql = 'SELECT name, position, department FROM members WHERE employeenumber=? AND password=?';
        $stmt = $dbh->prepare($sql);

        // バインドするデータを配列に格納
        $data[] = $employeenumber;
        $data[] = $pass;

        // SQL実行
        $stmt->execute($data);

        // データベース接続を閉じる
        $dbh = null;

        // 結果取得
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        // 結果の判定
        if ($rec == false) {
            print 'スタッフコードかパスワードが間違っています<br/>';
            print '<a href="staff_login.html">戻る</a>';
        } else {
            // セッション開始とデータ格納
            session_start();
            $_SESSION['login'] = 1;
            $_SESSION['employeenumber'] = $employeenumber;
            $_SESSION['name'] = $rec['name'];
            $_SESSION['position'] = $rec['position'];
            $_SESSION['department'] = $rec['department'];
            header('Location:home.php');
            exit();
        }
    } catch (PDOException $e) {
        // データベース接続やSQL実行で発生したエラーをキャッチ
        print 'データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '<br/>';
        print '<a href="staff_login.html">戻る</a>';
    } catch (Exception $e) {
        // その他のエラーをキャッチ
        print 'エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '<br/>';
        print '<a href="staff_login.html">戻る</a>';
    }
?>
