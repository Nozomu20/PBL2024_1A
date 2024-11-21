<html>
<head>
  <meta charset="UTF-8">
  <title>シフト社の勤務表作成ツール</title>

</head>

<?php
ini_set('default_charset', 'UTF-8');
if (is_uploaded_file($_FILES["csvfile"]["tmp_name"])) {
  $file_tmp_name = $_FILES["csvfile"]["tmp_name"];
  $file_name = $_FILES["csvfile"]["name"];

  //拡張子を判定
  if (pathinfo($file_name, PATHINFO_EXTENSION) != 'csv') {
    $err_msg = 'CSVファイルのみ対応しています。';
           echo '<div class="contact">';
           echo "<h2>シフト作成者 様へ</h2>";
           echo '<dl class="form-area">';
           echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
           恐れ入りますが、入力したファイル、もしくは入力していただいた年月に誤りがあるようです。下記ボタンから再度やり直してください。</div>";
           echo '</dl>';
           echo '</div>';
           echo
               "
                <div class='link-button-area'>
                <a class='link-button' href='admin-home.php'>戻る</a>
                </div>
             ";
	   //ファイルの削除
           unlink('./data/uploaded/'.$file_name);
  } else {
    //ファイルをdataディレクトリに移動
    if (move_uploaded_file($file_tmp_name, "./data/uploaded/" . $file_name)) {
      //後で削除できるように権限を644に
      chmod("./data/uploaded/" . $file_name, 0644);
      $msg = $file_name . "をアップロードしました。";
      $file = './data/uploaded/'.$file_name;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからのデータを取得
    $selectedOption = $_POST["部署選択"];

    // 部署によって処理を分岐
    switch ($selectedOption) {
        case "デジタル報道部配信班":
            // デジタル報道部配信班の処理
            #echo "デジタル報道部配信班が選択されました。";
            $cmd = '/venv/bin/python3 degitalstreaming_shift_make.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
            exec($cmd, $opt, $return_ver);
	        //print_r($opt);
            //echo '実行結果：'.$return_ver;
	    break;
        case "システム部ローテ業務":
            // システム部ローテ業務の処理
	    #echo "システム部ローテ業務が選択されました。";
            $cmd = '/venv/bin/python3 systemrotation_shift_make.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
	        exec($cmd, $opt, $return_ver);
	        //print_r($opt);
          //  echo '実行結果：'.$return_ver;
            break;
        case "新聞編集部整理班":
            // 新聞編集部整理班の処理
            #echo "新聞編集部整理班が選択されました。";
            $cmd = '/venv/bin/python3 renewspaper_editing.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
            exec($cmd, $opt, $return_ver);
            //print_r($opt);
            //echo '実行結果：'.$return_ver;		
	    break;
        default:
            // 未知の選択に対する処理
            #echo "不明な部署が選択されました。";
    }
}


      //$cmd = 'python3 systemrotation_shift_make.py'.' '.$file.' '.$_POST['year'].' '.$_POST['month'].' '.'2>&1';
      //exec($cmd, $opt, $return_ver);
      //print_r($opt);
      //echo '実行結果：'.$return_ver;
      
      if ($return_ver == 0) {
      $contents = file_get_contents("searchpath.txt");
      //echo $contents;
      echo '<div class="contact">';
            echo "<h2>シフト作成者 様へ</h2>";
            echo '<dl class="form-area">';
            echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
            作成に成功しましたので、お手数ですが下記ボタンからダウンロードをお願いします。</div>";
            echo '</dl>';
            echo '</div>';
      echo 
      "
      <ul class='item-button'>
      <li>
        <div class='link-button-area'>
          <a class='link-button' href='admin-home.php'>戻る</a>
        </div>
      </li>
      <li>
        <div class='link-button-area'>
        <div class='link-button'>
        <a href='data/dl/$contents' download='data/dl/$contents'>
          ダウンロード
        </a>
        </div>
        </div>
      </li>

      </ul>
      ";

      //ファイルの削除
      unlink('./data/uploaded/'.$file_name);
      }
      else {
           $err_msg = "プログラムの実行不可能";
           echo '<div class="contact">';
           echo "<h2>シフト作成者 様へ</h2>";
           echo '<dl class="form-area">';
           echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
           恐れ入りますが、入力したファイル、もしくは入力していただいた年月に誤りがあるようです。下記ボタンから再度やり直してください。</div>";
           echo '</dl>';
           echo '</div>';
           echo
               "
                <div class='link-button-area'>
                <a class='link-button' href='admin-home.php'>戻る</a>
                </div>
             ";
	   //ファイルの削除
           unlink('./data/uploaded/'.$file_name);
      }
    } else {
      $err_msg = "ファイルをアップロードできません。";
    }
  }
} else {
  $err_msg = "ファイルが選択されていません。";
  echo '<div class="contact">';
  echo "<h2>シフト作成者 様へ</h2>";
  echo '<dl class="form-area">';
  echo "<div class='form-text'>本シフト作成ツールをご利用いただき、ありがとうございます。
  恐れ入りますが、入力したファイルに誤りがあるようです。下記ボタンから再度やり直してください。</div>";
  echo '</dl>';
  echo '</div>';
  echo 
      "
        <div class='link-button-area'>
          <a class='link-button' href='admin-home.php'>戻る</a>
        </div>
      ";
}
//echo $msg;
//echo $err_msg;

?>
</html>