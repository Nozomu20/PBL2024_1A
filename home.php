<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シフト管理システム</title>
    <link rel="stylesheet" type="text/css" href="./home.css" />

    <script>
        function resetTextarea() {
            document.getElementById("noticeTextarea").value = "";
        }

        function handleFormSubmit(event) {
            const textarea = document.getElementById("noticeTextarea");
        }
    </script>

</head>
<body>
    <div class="header">
        <h1>愛媛新聞社 シフト管理システム</h1>
    </div>
    <button>設定</button>
    <div class="logout">
        <span>〇〇 〇〇 さん</span>
        <button>ログアウト</button>
    </div>


    <?php
    $filename = 'info.txt';
    $current_content = '';
    if (file_exists($filename)) {
        $current_content = file_get_contents($filename);
    }
    ?>
    
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
                        <td>
                            <?php include 'info_print.php'; ?>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="buttons">
            <button>リクエスト提出</button>
            <button onclick="location.href='./pbl2.html'">シフト表示</button>
        </div>
    </div>
</body>
</html>