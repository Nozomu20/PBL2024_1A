<?php
    session_start();
    ini_set('display_errors', "On");
    $name = $_SESSION['name'];
    $position = $_SESSION['position'];
    $department = $_SESSION['department']
?>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="../..home.css" />
    </head>
    <body>
        <div class="header">
            <a href="../../home.php"><h1>愛媛新聞社 シフト管理システム</h1></a>
        </div>
        <button>設定</button>
        <div class="logout">
            <span><?php echo $name;?> さん</span>
            <button onclick="location.href='staff_logout.php'">ログアウト</button>
        </div>
    </body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST["department"]) && !empty($_POST["year"]) && !empty($_POST["month"])) {
        $year = $_POST['year'];
        $month = $_POST['month'];
        $department = $_POST['department'];
    
        $file = "../data/req_".$year."_".$month."_".$department.".csv";
        $cmd = "";

        switch ($department) {
            case "デジタル報道部配信班":
                $cmd = '/var/www/html/Morioka/pbl2024/myenv/bin/python3 degitalstreaming_shift_make.py ' . escapeshellarg($file) . ' ' . escapeshellarg($_POST['year']) . ' ' . escapeshellarg($_POST['month']) . ' 2>&1';
                break;
            case "システム部ローテ業務":
                $cmd = '/usr/bin/python3 systemrotation_shift_make.py ' . escapeshellarg($file) . ' ' . escapeshellarg($_POST['year']) . ' ' . escapeshellarg($_POST['month']) . ' 2>&1';
                break;
            case "新聞編集部整理班":
                $cmd = '/usr/bin/python3 renewspaper_editing.py ' . escapeshellarg($file) . ' ' . escapeshellarg($_POST['year']) . ' ' . escapeshellarg($_POST['month']) . ' 2>&1';
                break;
            default:
                die("無効な部署です。");
        }

        exec($cmd, $output, $return_ver);            
        error_log("Command executed: " . $cmd);             
        error_log("Output: " . implode("\n", $output));            
        error_log("Return code: " . $return_ver);            
        //echo "<pre>" . implode("\n", $output) . "</pre>";
            

        if ($return_ver === 0) {            
            echo "処理が正常に終了しました。";
        } else {
                echo "処理中にエラーが発生しました。";
        }
    } else {
        die("部署、年、月のいずれかが指定されていません。");
    }
} else {
    die("無効なリクエストです。");
}
?>
