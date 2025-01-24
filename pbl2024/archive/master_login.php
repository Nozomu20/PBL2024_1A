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

スタッフログイン<br/>
<br/>
<form method="post" action="master_login_check.php">
管理者コード<br/>
<input type="text" name="codem"><br/>
パスワード<br/>
<input type="password" name="passm"><br/>
<br/>
<input type="submit" value="ログイン">
</form>

<br/>
<a href="staff_top.php">トップメニューへ</a><br/>

</body>
<html>