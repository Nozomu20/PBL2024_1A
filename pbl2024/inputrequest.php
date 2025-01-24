<?php
session_start();
ini_set('display_errors', 1);

// セッションチェック
if (!isset($_SESSION['name'])) {
    header('Location: staff_login.php');
    exit();
}

// ログインユーザーの情報を取得
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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        .day-cell {
            width: 14%;
        }
    </style>
    <script>
        function updateDays() {
            const year = document.getElementById("year").value;
            const month = document.getElementById("month").value - 1;
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            const calendar = document.getElementById("calendar-body");
            calendar.innerHTML = "";

            let row = document.createElement("tr");

            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement("td");
                row.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const cell = document.createElement("td");
                cell.className = "day-cell";

                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "days[]";
                checkbox.value = day;
                checkbox.onclick = checkLimit;

                cell.appendChild(checkbox);
                cell.appendChild(document.createTextNode(day));
                row.appendChild(cell);

                if ((firstDay + day - 1) % 7 === 6) {
                    calendar.appendChild(row);
                    row = document.createElement("tr");
                }
            }

            if (row.children.length > 0) {
                calendar.appendChild(row);
            }
        }

        function checkLimit() {
            const checkboxes = document.querySelectorAll('input[name="days[]"]:checked');
            if (checkboxes.length > 7) {
                alert("休み希望日は最大7日までです。");
                this.checked = false;
            }
        }

        window.onload = updateDays;
    </script>
</head>
<body>
<div class="header">
    <a href="./home.php"><h1>愛媛新聞社 シフト管理システム</h1></a>
</div>
<button>設定</button>
<div class="logout">
    <span><?php echo $department; ?>部 <?php echo $name; ?> さん</span>
    <button onclick="location.href='staff_logout.php'">ログアウト</button>
</div>

<h1>シフト希望入力フォーム</h1>
<form action="inputrequest.php" method="post">
    <p>ログインユーザー: <strong><?php echo $name; ?></strong></p>
    <label for="year">年を選択：</label>
    <select id="year" name="year" onchange="updateDays()">
        <?php
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
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

    <table>
        <thead>
            <tr>
                <th>日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
            </tr>
        </thead>
        <tbody id="calendar-body"></tbody>
    </table>

    <button type="submit">CSV出力</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = (int)$_POST['year'];
    $month = (int)$_POST['month'];
    $selectedDays = $_POST['days'] ?? [];

    if (count($selectedDays) > 7) {
        echo "<p class='error'>休み希望は7日までにしてください。</p>";
    } elseif (empty($selectedDays)) {
        echo "<p class='error'>休み希望日を選択してください。</p>";
    } else {
        $filename = './admin/data/req_' . $year . '_' . $month . '.csv';
        $updatedData = [];
        $newData = [$name, implode(", ", $selectedDays)];
        $isUpdated = false;

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

        if (!file_exists($filename)) {
            $file = fopen($filename, 'w');
            foreach ($defaultData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }

        if (file_exists($filename)) {
            $file = fopen($filename, 'r');
            while (($row = fgetcsv($file)) !== false) {
                if ($row[0] === $name) {
                    $row[1] = implode(", ", $selectedDays);
                    $isUpdated = true;
                }
                $updatedData[] = $row;
            }
            fclose($file);
        }

        if (!$isUpdated) {
            $updatedData[] = $newData;
        }

        $file = fopen($filename, 'w');
        foreach ($updatedData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        echo $isUpdated 
            ? "<p class='success'>既存のデータを更新しました。</p>" 
            : "<p class='success'>新しいデータを保存しました。</p>";
    }
}
?>
</body>
</html>
