<?php
$f = file_get_contents("　　　　　.txt");// \n<END>\nで一塊　headerとbodyを改行で分けて、header内は 送信時刻,宛先,送り主をカンマ区切り　　　のファイル.txt
$item = explode("\n<END>\n", $f);
for ($i = 0; $i < count($item) - 1; $i++) {
    list($header, $body) = explode("\n", $item[$i], 2);
    list($date, $for, $host) = explode(',', $header);
    echo "$date";
    echo htmlspecialchars($for, $host) . "\n";
    echo htmlspecialchars($body) . "\n\n";
}
?>