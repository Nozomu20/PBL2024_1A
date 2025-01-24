<?php
// メッセージ表示の処理
$source = isset($_GET['source']) ? $_GET['source'] : '';
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8') : '';
$employeenumber = isset($_GET['employeenumber']) ? htmlspecialchars($_GET['employeenumber'], ENT_QUOTES, 'UTF-8') : '';
$position = isset($_GET['position']) ? htmlspecialchars($_GET['position'], ENT_QUOTES, 'UTF-8') : '';
?>

<?php
session_start(); // セッションを開始
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成功</title>
</head>
<body>
    <h1>処理が成功しました</h1>

    <p>
        <?php
            switch ($source) {
                case 'create_account':
                    echo "<p>新しいアカウントが正常に作成されました。</p>";
                    echo "<p>アカウント情報:</p>";
                    echo "<ul>";
                    echo "<li><strong>名前:</strong> $name</li>";
                    echo "<li><strong>社員番号:</strong> $employeenumber</li>";
                    echo "<li><strong>役職:</strong> $position</li>";
                    echo "</ul>";
                    break;
                case 'account_edit':
                    echo "<p>アカウント情報が正常に更新されました。</p>";
                    break;
                case 'account_delete':
                    echo "<p>アカウントが正常に削除されました。</p>";
                    break;
                case 'account_user_edit':
                    echo "<p>パスワードが正常に変更されました。</p>";
                    break;
                default:
                    echo "<p>処理が正常に完了しました。</p>";
            }
        ?>
    </p>

    <!-- ホームに戻るボタン -->
    <a href="index.php"><button>ホームに戻る</button></a>
</body>
</html>
