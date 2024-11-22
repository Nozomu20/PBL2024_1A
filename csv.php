<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// フォルダパスを絶対パスで指定（例：/home/username/public_html/PBL_1A/）
$folderPath = '/home/j364hira/public_html/PBL_1A/';
$outputFile = '/home/j364hira/public_html/PBL_1A/output.csv';

$output = fopen($outputFile, 'w');
if ($output === false) {
    die("出力ファイルを開けませんでした。パスを確認してください: $outputFile");
}

// ディレクトリ内のCSVファイルをすべて取得
$files = glob($folderPath . '*.csv');
if ($files === false || count($files) === 0) {
    die("指定されたフォルダ内にCSVファイルが見つかりませんでした: $folderPath");
}

$headerWritten = false; // ヘッダーを一度だけ書くためのフラグ

foreach ($files as $file) {
    echo "処理中のファイル: $file\n"; // ファイル名を表示

    // CSVファイルを開く（読み込みモード）
    $handle = fopen($file, 'r');
    if ($handle === false) {
        echo "ファイルを開けませんでした: $file\n";
        continue;
    }

    // ヘッダー行を読み込み
    $header = fgetcsv($handle);
    if (!$headerWritten && $header !== false) {
        fputcsv($output, $header);
        $headerWritten = true;
    }

    // データ行を読み込んで出力ファイルに書き込む
    while (($data = fgetcsv($handle)) !== false) {
        fputcsv($output, $data);
    }

    fclose($handle);
}

// 出力ファイルを閉じる
fclose($output);

echo "結合できたよ: $outputFile\n";

?>