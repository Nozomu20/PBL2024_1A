<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['notice_content'] ?? '';
    
    if (!empty($content)) {
        if (file_put_contents('info.txt', $content) !== false) {
            $message = 'お知らせを更新しました。';
        } else {
            $message = 'お知らせの更新に失敗しました。';
        }
    } else {
        $message = 'お知らせの内容を入力してください。';
    }
    
    header('Location: info.php');
    exit;
}