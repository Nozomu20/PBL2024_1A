<?php
// CSVファイルのパス
$csvFile = 'data.csv';

// ファイルが存在するか確認
if (file_exists($csvFile)) {
    // ファイルを開く
    $file = fopen($csvFile, 'r');

    // テーブルの開始タグ
    echo "<table border='1'>";

    // CSVの各行を読み込み、テーブルの行として出力
    while (($row = fgetcsv($file)) !== false) {
        echo "<tr>";
        foreach ($row as $cell) {
            if ($cell=="公休") {
                echo "<td><font color='56f3a0'>" . htmlspecialchars($cell) . "</font></td>";
            }
            else if ($cell=="特殊休") {
                echo "<td><font color='f356a0'>" . htmlspecialchars($cell) . "</font></td>";
            }
            else {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
        }
        echo "</tr>";
    }

    // テーブルの終了タグ
    echo "</table>";

    // ファイルを閉じる
    fclose($file);
} else {
    echo "CSVファイルが見つかりません。";
}
?>
