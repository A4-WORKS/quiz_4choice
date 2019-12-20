<?php
/**
 * エントリー
 */

require('../libs/error.php');
require('../libs/api_response.php');
require('../libs/quiz.php');
require('../libs/data_dir.php');

// データ保存領域
$dataPath = getDataDir();

if(isset($_REQUEST['name'])){
  $name = $_REQUEST['name'];

  $name = trim($name);

  if($name == ''){
    $name = 'no name';
  }
} else {
  $name = 'no name';
}

$data = scandir($dataPath);
//var_dump($data);

$no = count($data) + 1 - 4;// カウント外の4ファイルアリ
//var_dump($no);

$md5 = md5(uniqid(rand(),1));
//var_dump($md5);

$id = $no.'_'.$md5;
//var_dump($id);

$filePath = $dataPath.'/'.$id;
touch($filePath);

// 回答保持データ
$ans = [];
$time = [];
for($i=1; $i<=10; $i++){
  $ans[$i] = 0;
  $time[$i] = 0;
}

//var_dump($ans);

$data = [];
$data['name'] = $name;
$data['id'] = $id;
$data['question'] = $ans;
$data['time'] = $time;
$data['totalTime'] = 0;

$json = json_encode($data);

// データ保存
file_put_contents($filePath, $json);

$json = json_encode($data);

// レスポンス
api_response($data);
