<?php
// エラー発生ページ
$source = isset($_GET['source']) ? $_GET['source'] : '';
?>

<?php
session_start(); // セッションを開始
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラー</title>
</head>
<body>
    <h1>エラーが発生しました</h1>

    <p>
        <?php
            switch ($source) {
                case 'create_account':
                    echo "<p>アカウント作成中にエラーが発生しました。もう一度お試しください。</p>";
                    break;
                case 'account_edit':
                    echo "<p>アカウントの編集中にエラーが発生しました。再度確認してください。</p>";
                    break;
                case 'account_delete':
                    echo "<p>アカウント削除中にエラーが発生しました。もう一度お試しください。</p>";
                    break;
                case 'account_user_edit':
                    echo "<p>パスワード変更中にエラーが発生しました。再度お試しください。</p>";
                    break;
                case 'account_edit_pass':
                    echo "<p>管理者パスワードが間違っています。再度お試しください。</p>";
                    break;
                default:
                    echo "<p>不明なエラーが発生しました。もう一度お試しください。</p>";
            }
        ?>
    </p>

    <!-- 戻るボタン -->
    <?php
        switch ($source) {
            case 'create_account':
                echo '<a href="create_account_page.php"><button>アカウント作成ページに戻る</button></a>';
                break;
            case 'account_edit':
                echo '<a href="account_search_page.php"><button>アカウント編集ページに戻る</button></a>';
                break;
            case 'account_delete':
                echo '<a href="account_search_page.php"><button>アカウント削除ページに戻る</button></a>';
                break;
            case 'account_user_edit':
                echo '<a href="account_user_edit_page.php"><button>アカウント修正ページに戻る</button></a>';
                break;
            case 'account_edit_pass':
                echo '<a href="account_search_page.php"><button>アカウント修正ページに戻る</button></a>';
                break;
            default:
                echo '<a href="index.php"><button>ホームに戻る</button></a>';
        }
    ?>
</body>
</html>
