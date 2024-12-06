<!--仮のページです-->

<?php
session_start(); // セッションを開始
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホームページ</title>
    <style>
        /* 全体のスタイル */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff; /* 白背景 */
            color: #333; /* 黒文字 */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
        }

        h1 {
            color: #000; /* 見出しは黒 */
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        p {
            color: #555; /* 少し薄い黒 */
            font-size: 1.1em;
            margin-bottom: 30px;
        }

        /* ボタンスタイル */
        form {
            margin-bottom: 15px;
        }

        button {
            background-color: #fff; /* ボタン背景白 */
            color: #000; /* ボタン文字黒 */
            padding: 12px 25px;
            font-size: 1.1em;
            border: 2px solid #000; /* ボタンの枠線 */
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #000; /* ホバー時に黒背景 */
            color: #fff; /* ホバー時に文字白 */
        }

        /* ボタンの配置 */
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ホームページ内のリンク */
        a {
            color: #000; /* リンクの色黒 */
            text-decoration: none;
            font-size: 1.1em;
            margin-top: 30px;
            display: inline-block;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>仮ページ</h1>

    <div class="button-container">
        <h2>管理者側</h2>

        <!-- ボタン1: 管理者用（アカウント作成ページへ）-->
        <form action="create_account_page.php" method="get">
            <button type="submit">アカウント作成ページ</button>
        </form>

        <!-- ボタン2: 管理者用（アカウント修正ページへ）-->
        <form action="account_search_page.php" method="get">
            <button type="submit">アカウント修正ページ</button>
        </form>

        <h2>ユーザー側</h2>

        <!-- ボタン3: ユーザー用（自分のアカウント修正ページへ）-->
        <form action="account_user_edit_page.php" method="get">
            <button type="submit">アカウント修正ページ</button>
        </form>
    </div>


</body>
</html>
