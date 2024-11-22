<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>お知らせ管理システム</title>
    <style>
        .notice-box {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
        }

        .notice-content {
            margin-bottom: 20px;
        }

        .notice-editor {
            border: 1px solid #ccc;
            padding: 20px;
        }

        textarea {
            width: 100%;
            height: 200px;
            font-size: 16px;
        }

        .button-group {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        button {
            padding: 5px 15px;
        }
    </style>
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
    <?php
    $filename = 'info.txt';
    $current_content = '';
    if (file_exists($filename)) {
        $current_content = file_get_contents($filename);
    }
    ?>
    <div class="notice-box">
        <h2>現在のお知らせ</h2>
        <div class="notice-content">
            <?php include 'info_print.php'; ?>
        </div>
    </div>

    <div class="notice-editor">
        <h2>お知らせ編集</h2>
        <form action="info_write.php" method="post" onsubmit="handleFormSubmit(event)">
            <textarea name="notice_content" id="noticeTextarea" placeholder="お知らせの内容を入力してください"><?php echo htmlspecialchars($current_content, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <div class="button-group">
                <button type="submit">更新する</button>
                <button type="button" onclick="resetTextarea()">リセット</button>
            </div>
        </form>
    </div>
</body>

</html>
