<?php
// process_shift_data.php
function processShiftData($name, $month, $selectedDays) {
    $filename = "data/req{$month}.csv"; // データフォルダに保存
    $updatedData = [];
    $newData = [$name, implode(", ", $selectedDays)];
    $isUpdated = false;

    // ファイルが存在する場合、既存データを読み込み
    if (file_exists($filename)) {
        $file = fopen($filename, 'r');
        while (($row = fgetcsv($file)) !== false) {
            if ($row[0] === $name) {
                $updatedData[] = $newData; // 同じ名前のデータを更新
                $isUpdated = true;
            } else {
                $updatedData[] = $row; // 他のデータはそのまま
            }
        }
        fclose($file);
    }

    // 同じ名前のデータがない場合は新規追加
    if (!$isUpdated) {
        $updatedData[] = $newData;
    }

    // 更新後のデータをファイルに書き込み
    $file = fopen($filename, 'w'); // 上書き
    foreach ($updatedData as $row) {
        fputcsv($file, $row);
    }
    fclose($file);

    return $isUpdated ? "既存のデータを更新しました。" : "新しいデータを追加しました。";
}
?>
