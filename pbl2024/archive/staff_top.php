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
    $name=$_SESSION['name'];
    $position=$_SESSION['position'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シフト管理システム</title>
    <link rel="stylesheet" type="text/css" href="home.css" />
</head>
<body>
    <div class="header">
        <h1>愛媛新聞社 シフト管理システム</h1>
    </div>
    <button onclick="location.href='staff_list.php'">スタッフリスト</button>
    <?php
    if($position=='admin')
    {?>
        <button onclick="location.href='master_list.php'">管理者ページ</button>
    <?php
    }
    ?>
    <div class="logout">
        <span>
          <?php
           print $name;
           print 'さんログイン中';
          ?>
        </span>
        <button onclick="location.href='staff_logout.php'">ログアウト</button>
    </div>
    <div class="content">
        <div class="announcement">
            <h2>お知らせ</h2>
            <table>
                <thead>
                    <tr>
                        <th>件名</th>
                        <th>配信日時</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="buttons">
            <button onclick="location.href='inputshift.php'">リクエスト提出</button>
            <button onclick="location.href='./pbl2.html'">シフト表示</button>
        </div>
    </div>
</body>
</html>
