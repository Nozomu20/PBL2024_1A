<!DOCTYPE html>
<html long="ja">
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
    </style>
</head>
<body>
    <div class="notice-box">
        <h2>現在のお知らせ</h2>
        <div class="notice-content">
            <?php include 'info_print.php'; ?>
        </div>
    </div>
    
    <div class="notice-editor">
        <h2>お知らせ編集</h2>
        <form action="info_write.php" method="post">
            <textarea name="notice_content" placeholder="お知らせの内容を入力してください"></textarea>
            <button type="submit">更新する</button>
        </form>
    </div>
</body>
</html>