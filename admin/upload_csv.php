<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    // 保存先ディレクトリ
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // アップロードされたファイルの保存
    $uploaded_file = $upload_dir . basename($_FILES['csv_file']['name']);

    if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $uploaded_file)) {
        // 成功時に編集画面へリダイレクト
        header('Location: edit_csv.php?file=' . urlencode($uploaded_file));
        exit;
    } else {
        // エラー処理
        die('ファイルのアップロードに失敗しました。');
    }
} else {
    die('不正なリクエストです。');
}
