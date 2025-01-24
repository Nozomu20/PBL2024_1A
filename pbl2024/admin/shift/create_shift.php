<html>
<head>
  <meta charset="UTF-8">
  <title>シフト社の勤務表作成ツール</title>
  <link rel="stylesheet" type="text/css" href="../../home.css" />
</head>
<body>
        <div class="header">
            <a href="../../home.php"><h1>愛媛新聞社 シフト管理システム</h1></a>
    </div>
    <button>設定</button>
    <div class="logout">
        <span><?php echo $name;?> さん</span>
        <button onclick="location.href='staff_logout.php'">ログアウト</button>
    </div>  

<h2>シフト作成フォーム</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
<div>
  <dt><span>年を入力：</span></dt>
  <dd><input type="text" name="year" size="4" placeholder="YYYY" /></dd>

  <dt><span>月を入力：</span></dt>
  <dd><input type="text" name="month" size="2" placeholder="MM" /></dd>

  <dt><span>部署選択：</span></dt>
  <dd>
    <select name="department">
        <option value="デジタル報道部配信班">部署A</option>
        <option value="システム部ローテ業務">部署B</option>
        <option value="新聞編集部整理班">部署C</option>
    </select>
  </dd>
</div>
<div>
  <button type="submit">作成開始</button>
</div>

</form>
</div>
</body>
</html>