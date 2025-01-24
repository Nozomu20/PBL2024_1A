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
        // 月が変更されたときにカレンダーを更新
        function updateDays() {
            const month = parseInt(document.getElementById("month").value);
            const year = parseInt(document.getElementById("year").value);
            const daysInMonth = new Date(year, month, 0).getDate(); // 月の最終日取得
            const firstDay = new Date(year, month - 1, 1).getDay(); // 月初の曜日 (0:日曜日)
            const daysContainer = document.getElementById("days");
            daysContainer.innerHTML = ''; // 既存の内容をクリア

            // テーブル作成
            const table = document.createElement('table');
            table.classList.add('calendar');

            // 曜日ヘッダーを作成
            const daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
            const headerRow = document.createElement('tr');
            daysOfWeek.forEach(day => {
                const th = document.createElement('th');
                th.textContent = day;
                headerRow.appendChild(th);
            });
            table.appendChild(headerRow);

            // 日付を埋める
            let row = document.createElement('tr');
            for (let i = 0; i < firstDay; i++) { // 空白のセルを追加
                const emptyCell = document.createElement('td');
                row.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                if ((firstDay + day - 1) % 7 === 0 && day !== 1) { // 行の切り替え
                    table.appendChild(row);
                    row = document.createElement('tr');
                }

                const cell = document.createElement('td');
                const checkbox = document.createElement('input');
                checkbox.type = "checkbox";
                checkbox.name = "days[]";
                checkbox.value = day;
                checkbox.id = `day${day}`;
                checkbox.onclick = checkLimit;

                const label = document.createElement('label');
                label.htmlFor = `day${day}`;
                label.textContent = day;

                cell.appendChild(checkbox);
                cell.appendChild(label);
                row.appendChild(cell);
            }

            // 残りのセルを空白で埋める
            while (row.children.length < 7) {
                const emptyCell = document.createElement('td');
                row.appendChild(emptyCell);
            }

            table.appendChild(row);
            daysContainer.appendChild(table);
        }

        // 休み希望日の選択上限をチェック
        function checkLimit() {
            const checkboxes = document.querySelectorAll('input[name="days[]"]:checked');
            if (checkboxes.length > 7) {
                alert("休み希望日は最大7日までです。");
                this.checked = false;
            }
        }

        window.onload = updateDays; // ページ読み込み時に初期化
    </script>
    <style>
        .calendar {
            border-collapse: collapse;
            width: 100%;
        }
        .calendar th, .calendar td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
            width: 14.28%;
        }
        .calendar th {
            background-color: #f4f4f4;
        }
        .calendar td {
            height: 50px;
        }
    </style>
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

</body>
</html>
