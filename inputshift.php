<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シフト希望入力</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        label {
            margin-right: 10px;
        }
        #days label {
            display: inline-block;
            margin: 5px;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
    </style>
    <script>
        // 月が変更されたときの日数生成
        function updateDays() {
            const month = document.getElementById("month").value;
            const daysInMonth = new Date(2024, month, 0).getDate();  // 月の最終日取得
            const daysContainer = document.getElementById("days");
            daysContainer.innerHTML = '';  // 前の内容をクリア

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
    <h1>シフト希望入力フォーム</h1>
    <form action="inputshift.php" method="post">
        <label for="name">名前：</label>
        <input type="text" id="name" name="name" required placeholder="例: 山田 太郎"><br><br>

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
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); // XSS防止
        $month = (int)$_POST['month'];
        $selectedDays = $_POST['days'] ?? [];

        // 入力チェック
        if (count($selectedDays) > 7) {
            echo "<p class='error'>休み希望は7日までにしてください。</p>";
        } elseif (empty($selectedDays)) {
            echo "<p class='error'>休み希望日を選択してください。</p>";
        } else {
            // CSVデータの生成
            $csvData = [$name, implode(", ", $selectedDays)];
            $filename = "req{$month}.csv";

            // CSVに保存
            $file = fopen($filename, 'a');
            fputcsv($file, $csvData);
            fclose($file);

            echo "<p class='success'>ファイル '{$filename}' に保存しました。</p>";
        }
    }
    ?>
</body>
</html>
