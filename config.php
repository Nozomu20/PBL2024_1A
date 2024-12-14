<!--仮のページです-->
<!--config.phpに変更-->

<?php
session_start(); // セッションを開始
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定</title>
    <style>
        /* 全体のスタイル */

        #head {
            width: 100%;
            height: 60px;
            text-align: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 20px;
            background-color: #fbb89c;
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
    <table id="head">
        <tr>
            <th>愛媛新聞社 シフト管理システム</th>
        </tr>
    </table>

    <div class="button-container">
        <h2>管理者用</h2>

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
