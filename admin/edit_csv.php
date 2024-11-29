<?php
// ファイルパラメータの確認
if (!isset($_GET['file'])) {
    die('CSVファイルが指定されていません。');
}

$file = $_GET['file'];

if (!file_exists($file)) {
    die('指定されたCSVファイルが見つかりません。');
}

// CSVの読み込み
$data = [];
if (($handle = fopen($file, 'r')) !== false) {
    // $escape パラメータを指定することで警告を回避
    while (($row = fgetcsv($handle, 1000, ',', '"', '\\')) !== false) {
        $data[] = $row;
    }
    fclose($handle);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>CSV編集</title>
</head>
<body>
    <h1>CSV編集</h1>
    <form action="save_csv.php" method="post">
        <input type="hidden" name="file" value="<?php echo htmlspecialchars($file); ?>">
        <table border="1">
            <?php foreach ($data as $rowIndex => $row):
                echo "<tr>";
                    foreach ($row as $colIndex => $col) {
                        if ($rowIndex > 0) { // 1行目以外を編集可能
                            echo "<td>";
                            // 役職列（1列目）はテキストボックスで表示（readonly）
                            if ($colIndex == 0) {
                                echo "<input type='text' name='data[$rowIndex][$colIndex]' value='" . htmlspecialchars($col) . "' readonly>";
                            } else {
                                // その他の列はselectボックスとして表示
                                echo "<select name='data[$rowIndex][$colIndex]'>";
                                $options = [
                                    "イ", "ロ", "ハ", "ニ", "ホ", "ヘ", "ト", "チ", "A", "B", "C", "D",
                                    "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "P", "Q", "R",
                                    "S", "T", "U", "W", "Y", "Z", "育短915", "ハ短", "公休", "特殊休暇",
                                    "出張", "リフレッシュ休暇", "慰労休暇", "看護休暇", "介護休暇",
                                    "年休", "産休", "育児休業", "疾病休職", "宿直", "半宿直"
                                ];
                                foreach ($options as $option) {
                                    $selected = ($col == $option) ? "selected" : "";
                                    echo "<option value='$option' $selected>$option</option>";
                                }
                                echo "</select>";
                            }
                            echo "</td>";
                        } else {
                            echo "<td>$col</td>"; // 1行目（ヘッダー行）は編集不可
                        }
                    }
                    echo "</tr>";
             endforeach; ?>
        </table>
        <button type="submit">保存</button>
    </form>
</body>
</html>
