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
        /*
        //ファイルに書き込み
        function writeScoreToFile(score) {
            // XMLHttpRequestを使用してPHPスクリプトにデータを送信
            let xhr = new XMLHttpRequest();
            // encodeURIComponent で特殊な記号を処理
            xhr.open("GET", `./info_write.php?=${encodeURIComponent(current_content)}`, true);
            xhr.send();
        }*/
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
    // home.php
    $filename = 'info.txt';
    $announcements = [];

    if (file_exists($filename)) {
        $file = file($filename);
        foreach ($file as $line) {
            $line = trim($line);
            if (!empty($line)) {
                list($date, $content) = explode(',', $line);
                
                $announcements[] = [
                    'date' => htmlspecialchars($date, ENT_QUOTES, 'UTF-8'),
                    'content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8')
                ];
            }
        }
    }
    ?>

    <div class="content">
        <div class="announcement">
            <h2>お知らせ</h2>
            <table>
                <thead>
                    <tr>
                        <th>配信日時</th>
                        <th>件名</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($announcements)): ?>
                        <?php foreach ($announcements as $announcement): ?>
                            <tr>
                                <td><?php echo $announcement['date']; ?></td>
                                <td><?php echo $announcement['content']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">お知らせはありません。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="buttons">
            <button>リクエスト提出</button>
            <button onclick="location.href='./pbl2.html'">シフト表示</button>
        </div>
    </div>
    <div class="notice-editor">
        <h2>お知らせ編集</h2>
        <form action="info_write.php" method="post" id="noticeForm">
            <textarea name="notice_content" id="noticeTextarea" placeholder="お知らせの内容を入力してください"><?php echo htmlspecialchars($current_content ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            <div class="buttons">
                <button type="submit" name="action" value="update">更新する</button>
                <button type="submit" name="action" value="reset">リセット</button>
            </div>
</form>
    </div>
</body>
</html>