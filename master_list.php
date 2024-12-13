<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false)
{
    print 'ログインされていません<br/>';
    print '<a href="master_login.php">ログイン画面へ</a>';
    exit();
}
else
{
    print '管理者ログイン中<br/>';
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
    $dsn='mysql:dbname=j024ishi4;host=localhost;charset=utf8';
    $user='j024ishi';
    $password='';
    $dbh=new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $sql='SELECT employeenumber,name FROM members WHERE 1';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();

    $dbh=null;

    print 'スタッフ一覧<br/><br/>';

    print '<form method="post" action="staff_branch.php">';
    while(true)
    {
        $rec=$stmt->fetch(PDO::FETCH_ASSOC);
        if($rec==false)
        {
            break;
        }
        print '<input type="radio" name="employeenumber" value="'.$rec['employeenumber'].'">';
        print $rec['name'];
        print '<br/>';
    }
    print '<input type="submit" name="disp" value="参照">';
    print '<input type="submit" name="add" value="追加">';
    print '<input type="submit" name="edit" value="修正">';
    print '<input type="submit" name="delete" value="削除">';
    print '</form>';
}
catch(Exception $e)
{
    print 'ただいま障害により大変ご迷惑をお掛けしております';
    exit();
}

?>

<br/>
<a href="staff_top.php">トップメニューへ</a><br/>

</body>
<html>
