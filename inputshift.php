<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <?php
    session_start();

    // セッションチェック
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: master_login.php');
        exit();
    }

    // ログインユーザーの名前を取得
    $userName = htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8');
    ?>

    <h1>シフト希望入力フォーム</h1>
    <form action="inputshift.php" method="post">
        <p>ログインユーザー: <strong><?php echo $userName; ?></strong></p>

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
        $month = (int)$_POST['month'];
        $selectedDays = $_POST['days'] ?? [];

        // 入力チェック
        if (count($selectedDays) > 7) {
            echo "<p class='error'>休み希望は7日までにしてください。</p>";
        } elseif (empty($selectedDays)) {
            echo "<p class='error'>休み希望日を選択してください。</p>";
        } else {
            $filename = "req.csv"; // データを保存するCSVファイル
            $updatedData = [];
            $newData = [$userName, $month, implode(", ", $selectedDays)];
            $isUpdated = false;

            // CSVファイルが存在する場合、既存データを読み込み
            if (file_exists($filename)) {
                $file = fopen($filename, 'r');
                while (($row = fgetcsv($file)) !== false) {
                    // 名前と月が一致する場合は更新
                    if ($row[0] === $userName && (int)$row[1] === $month) {
                        $isUpdated = true;
                    } else {
                        $updatedData[] = $row; // 他のデータはそのまま保持
                    }
                }
                fclose($file);
            }

            // 新しいデータを追加
            $updatedData[] = $newData;

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
