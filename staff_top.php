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

トップページ<br/>
<br/>
<a href="staff_list.php">スタッフ管理</a><br/>
<br/>
<a href="staff_logout.php">ログアウト</a><br/>
<br/>
<a href="master_login.php">管理者ページ</a><br/>

</body>
<html>