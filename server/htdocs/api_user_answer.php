<?php
/**
 * 回答送信
 */

require('../libs/error.php');
require('../libs/api_response.php');
require('../libs/data_dir.php');

// データ保存領域
$dataPath = getDataDir();

// var_dump($_REQUEST);

$id = $_REQUEST['id'];

$check = true;

if(!isset($_REQUEST['id'])){
  $check = false;
}

if(!isset($_REQUEST['n'])) {
  $check = false;
}

if(!isset($_REQUEST['a'])) {
  $check = false;
}

if(!isset($_REQUEST['t'])) {
  $check = false;
}

if(!$check){
  // error
  $msg = 'Invalid parameter.';
  error($msg);
}

$filePath = $dataPath.'/'.$id;

$jsonData = @file_get_contents($filePath);
// var_dump($jsonData);

$data = json_decode($jsonData, true);
// var_dump($data);

if(empty($data)){
  $msg = 'data not found.';
  error($msg);
}


$no = $_REQUEST['n'];
$ans = $_REQUEST['a'];
$time = $_REQUEST['t'];

//var_dump($no);
//var_dump($ans);
//var_dump($time);

$data['question'][$no] = $ans;
$data['time'][$no] = $time;
$data['totalTime'] = $data['totalTime'] + $time;

$jsonData = json_encode($data);

file_put_contents($filePath, $jsonData);

unset($data['STATUS']);

api_response($data);
