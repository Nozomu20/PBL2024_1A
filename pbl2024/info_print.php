<?php
// info_print.php
$filename = './info.txt';
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

// JSONで出力（AJAXリクエストに対応）
header('Content-Type: application/json');
echo json_encode($announcements);
?>