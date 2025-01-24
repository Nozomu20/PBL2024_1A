<?php
    session_start();
    ini_set('display_errors', 1);

    // セッションチェック
    if (!isset($_SESSION['name'])) {
        header('Location: staff_login.php');
        exit();
    }

    // ログインユーザーの名前を取得
    $name = $_SESSION['name'];
    $department = $_SESSION['department'];
    
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./home.css" />
    <title>シフト希望入力</title>
    <script>
        // 月が変更されたときの日数生成
        function updateDays() {
            const month = document.getElementById("month").value;
            const daysInMonth = new Date(2024, month, 0).getDate();  // 月の最終日取得
            const daysContainer = document.getElementById("days");
            daysContainer.innerHTML = '';  // 前の内容をクリア

            // 日数分のチェックボックスを動的生成
            for (let i = 1; i <= daysInMonth; i++) {
                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "days[]";
                checkbox.value = i;
                checkbox.id = `day${i}`;
                checkbox.onclick = checkLimit;

                const label = document.createElement("label");
                label.htmlFor = `day${i}`;
                label.textContent = `${i}日`;

                daysContainer.appendChild(checkbox);
                daysContainer.appendChild(label);
            }
        }

        // 休み希望日の選択上限をチェック
        function checkLimit() {
            const checkboxes = document.querySelectorAll('input[name="days[]"]:checked');
            if (checkboxes.length > 7) {
                alert("休み希望日は最大7日までです。");
                this.checked = false;
            }
        }

        window.onload = updateDays;  // ページ読み込み時に初期化
    </script>
</head>
<body>

<div class="header">
            <a href="./home.php"><h1>愛媛新聞社 シフト管理システム</h1></a>
        </div>
        <button>設定</button>
        <div class="logout">
            <span><?php echo $department;?>部 <?php echo $name;?> さん</span>
            <button onclick="location.href='staff_logout.php'">ログアウト</button>
        </div>


    <h1>シフト希望入力フォーム</h1>
    <form action="inputrequest.php" method="post">
        <p>ログインユーザー: <strong><?php echo $name; ?></strong></p>
        <label for="year">年を選択：</label>
        <select id="year" name="year">
            <?php
            $currentYear = date('Y'); // 現在の年を取得
            $nextYear = $currentYear + 1; // 次の年を計算
            ?>
            <option value="<?= $currentYear ?>"><?= $currentYear ?>年</option>
            <option value="<?= $nextYear ?>"><?= $nextYear ?>年</option>
        </select><br><br>
                
        <label for="month">月を選択：</label>
        <select id="month" name="month" onchange="updateDays()">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?>月</option>
            <?php endfor; ?>
        </select><br><br>

        <div id="days"></div>
        
        <button type="submit">CSV出力</button>
    </form>

    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力データの受け取り
    $year = (int)$_POST['year'];
    $month = (int)$_POST['month'];
    $selectedDays = $_POST['days'] ?? [];

    // 入力チェック
    if (count($selectedDays) > 7) {
        echo "<p class='error'>休み希望は7日までにしてください。</p>";
    } elseif (empty($selectedDays)) {
        echo "<p class='error'>休み希望日を選択してください。</p>";
    } else {
        $filename = './admin/data/req_' . $year . '_' . $month . '.csv';
        $updatedData = [];
        $newData = [$name, implode(", ", $selectedDays)];
        $isUpdated = false;

        // デフォルトデータの定義
        $defaultData = [
            ["名前", "希望日時"],
            ["統括", ""],
            ["副部長A", ""],
            ["副部長B", ""],
            ["副部長C", ""],
            ["副部長D", ""],
            ["部員A", ""],
            ["部員B", ""],
            ["部員C", ""],
            ["臨時・派遣A", ""],
            ["臨時・派遣B", ""],
            ["臨時・派遣C", ""],
        ];

        // CSVファイルが存在しない場合、デフォルトデータを追加
        if (!file_exists($filename)) {
            $file = fopen($filename, 'w');
            foreach ($defaultData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }

        // CSVファイルが存在する場合、既存データを読み込み
        if (file_exists($filename)) {
            $file = fopen($filename, 'r');
            while (($row = fgetcsv($file)) !== false) {
                // 名前と月が一致する場合は更新
                if ($row[0] === $name) {
                    $row[1] = implode(", ", $selectedDays);
                    $isUpdated = true;
                }
                $updatedData[] = $row;
            }
            fclose($file);
        }

        // 更新がなければ新しいデータを追加
        if (!$isUpdated) {
            $updatedData[] = $newData;
        }

        // 更新後のデータをCSVに書き込み
        $file = fopen($filename, 'w');
        foreach ($updatedData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        // メッセージを表示
        if ($isUpdated) {
            echo "<p class='success'>既存のデータを更新しました。</p>";
        } else {
            echo "<p class='success'>新しいデータを保存しました。</p>";
        }
    }
}
    ?>
</body>
</html>
