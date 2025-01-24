<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false)
{
    print 'ログインされていません<br/>';
    print '<a href="staff_login.html">ログイン画面へ</a>';
    exit();
}
else
{
    print $_SESSION['staff_name'];
    print 'さんログイン中<br/>';
    print '<br/>';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PBL演習</title>
</head>
<body>

<?php

try
{
    $staff_code=$_GET['staffcode'];

    $dsn='mysql:dbname=pbl;host=localhost;charset=utf8';
    $user='root';
    $password='';
    $dbh=new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $sql='SELECT name FROM pbl_staff WHERE code=?';
    $stmt=$dbh->prepare($sql);
    $data[]=$staff_code;
    $stmt->execute($data);

    $rec=$stmt->fetch(PDO::FETCH_ASSOC);
    $staff_name=$rec['name'];

    $dbh=null;

}
catch(Exception $e)
{
    print 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}

?>

スタッフ削除<br/>
<br/>
スタッフコード<br/>
<?php print $staff_code;?>
<br/>
スタッフ名<br/>
<?php print $staff_name;?>
<br/>
このスタッフを削除してもよろしいですか?<br/>
<br/>
<form method="post" action="staff_delete_done.php">
<input type="hidden" name="code" value="<?php print $staff_code;?>">
<input type="button" onclick="history.back()" value="戻る">
<input type="submit" value="OK">
</form>

</body>
<html>