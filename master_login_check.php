<?php

$master_code=$_POST['codem'];
$master_pass=$_POST['passm'];

$master_code=htmlspecialchars($master_code,ENT_QUOTES,'UTF-8');
$master_pass=htmlspecialchars($master_pass,ENT_QUOTES,'UTF-8');
    
if($master_code!=1||$master_pass!=8931)
{
    print 'スタッフコードかパスワードが間違っています<br/>';
    print '<a href="staff_login.html">戻る</a>';
}
else
{
    header('Location:staff_list.php');
    exit();
}


?>

</body>
<html>