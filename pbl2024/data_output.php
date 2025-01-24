<html>
    <head>
    <link rel="stylesheet" type="text/css" href="./home.css" />
    </head>
    <body>
    <div class="header">
            <a href="./home.php"><h1>愛媛新聞社 シフト管理システム</h1></a>
    </div>
    <button>設定</button>
    <div class="logout">
        <span><?php echo $name;?> さん</span>
        <button onclick="location.href='staff_logout.php'">ログアウト</button>
    </div>  
    </body>
</html>
<?php
// CSVファイルのパス
$csvFile = './admin/data/digi_st_result_year2024_month7_20241129_003106.csv';
echo $csvFile;
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
