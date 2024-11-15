<?php
$filename = 'info.txt';
if (file_exists($filename)) {
    $content = file_get_contents($filename);
    echo nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
} else {
    echo "お知らせはありません。";
}
?>