<?php
// ファイルパラメータの確認
if (!isset($_POST['file'])) {
    die('CSVファイルが指定されていません。');
}

$file = $_POST['file'];

if (!file_exists($file)) {
    die('指定されたCSVファイルが見つかりません。');
}

// 編集されたデータを受け取る
$data = $_POST['data'];

// ファイルの読み込み
$rows = [];
$header = null;  // ヘッダー行を格納
if (($handle = fopen($file, 'r')) !== false) {
    // ヘッダー行を読み込む
    $header = fgetcsv($handle, 1000, ',', '"', '\\');
    
    // 残りのデータを読み込む
    while (($row = fgetcsv($handle, 1000, ',', '"', '\\')) !== false) {
        $rows[] = $row;
    }
    fclose($handle);
}

// 役職列を保持し、他のカラムを編集
foreach ($data as $key => $row) {
    if (isset($rows[$key])) {
        // 役職列（インデックス0）はそのまま、その他の列を更新
        $row[0] = $rows[$key][0];  // 役職列は変更せず、そのままコピー
    }
}

// ファイルの書き込み（役職列を含む）
if (($handle = fopen($file, 'w')) !== false) {
    // ヘッダー行を書き込む
    fputcsv($handle, $header, ',', '"', '\\');
    
    // 編集されたデータを保存（役職列も含めて）
    foreach ($data as $row) {
        fputcsv($handle, $row, ',', '"', '\\'); // $escapeパラメータを指定
    }
    fclose($handle);
    echo 'CSVファイルが正常に保存されました。';
} else {
    echo 'ファイルを開けませんでした。';
}
?>
