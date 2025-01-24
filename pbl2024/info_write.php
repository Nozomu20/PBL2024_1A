<?php
$filename = './info.txt';

// POSTリクエストを受け取り処理を分岐
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        // 更新処理
        $notice_content = trim($_POST['notice_content'] ?? '');
        if (!empty($notice_content)) {
            $date = date('Y-m-d');
            $line = PHP_EOL . $date . ',' . $notice_content;
            file_put_contents($filename, $line, FILE_APPEND);
            echo "1";
        }
    } elseif ($action === 'reset') {
        // リセット処理
        file_put_contents($filename, ''); // ファイルを空にする
        echo "2";
    }
}

// リダイレクトしてリロード
//header('Location: info.php');
exit;
?>