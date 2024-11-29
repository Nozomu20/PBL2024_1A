<?php
session_start(); // セッションを開始
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アクセスエラー</title>
</head>
<body>

    <h1>あなたは管理者ではありません</h1>
    <p>管理者権限がないため、アクセスできません。</p>

    <!-- index.phpに遷移するボタン -->
    <form action="index.php" method="get">
        <button type="submit">トップページに戻る</button>
    </form>

</body>
</html>
