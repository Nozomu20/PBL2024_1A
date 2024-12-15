<?php
include 'auth_check.php'; // 新しいファイル名に変更
include 'process_shift_data.php'; // データ処理ロジックを読み込み

$message = ''; // 結果メッセージ初期化

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = (int)$_POST['month'];
    $selectedDays = $_POST['days'] ?? [];

    // 入力チェック
    if (count($selectedDays) > 7) {
        $message = "<p class='error'>休み希望は7日までにしてください。</p>";
    } elseif (empty($selectedDays)) {
        $message = "<p class='error'>休み希望日を選択してください。</p>";
    } else {
        $message = "<p class='success'>" . processShiftData($loggedInName, $month, $selectedDays) . "</p>";
    }
}
?>
