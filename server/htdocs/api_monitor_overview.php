<?php
/**
 * 進行
 */

require('../libs/error.php');
require('../libs/api_response.php');
require('../libs/quiz.php');
require('../libs/data_dir.php');
require('../libs/progress.php');

// データ保存領域
$dataPath = getDataDir();

$progress = getProgress();
//var_dump($progress);

// クイズ情報取得
$quiz = get_quiz();

// var_dump($quiz);

$ary = @scandir($dataPath, SCANDIR_SORT_DESCENDING);

// var_dump($ary);

$memberData = [];
$members = [];
$collectNum = [];

foreach ($ary as $file) {

  // var_dump(strpos($file, '_'));

  if (strpos($file, '_') === false) {
    continue;
  }

  $path = $dataPath . '/' . $file;

  $json = file_get_contents($path);

  $data = json_decode($json, true);

  if (empty($data)) {
    continue;
  }


  $id = $data['id'];
  $name = $data['name'];

  $memberData[$id] = $data;

  $members[$id] = $name;

  $collectNum[$id] = 0;

}

// var_dump($members);
// var_dump($collectNum);

$ans = [];

for ($q = 1; $q <= 10; $q++) {

  $ans[$q] = [];

  $correct = $quiz[$q]['a'];
  // var_dump($correct);

  foreach ($memberData as $member) {

    // var_dump($member['time']);
    // var_dump($member['question'][$q]);

    $id = $member['id'];
    $a = $member['question'][$q];

    if ($correct == $a) {
      // 正解
      $result = true;
      $collectNum[$id]++;

    } else {
      $result = false;
    }

    $ans[$q][$id] = ['a' => $a, 'result' => $result];

  }
}

// var_dump($ans);
//var_dump($collectNum);

$tmp = [];
// ランキング

arsort($collectNum);

// var_dump($collectNum);
// var_dump($memberData);

foreach ($collectNum as $id => $num) {

  $t = $memberData[$id]['totalTime'];

  if (!$t) {
    $t = time();
  }

  if (isset($tmp[$num][$t])) {
    $t = $t + 1;
  }

  $tmp[$num][$t] = $id;

}

//var_dump($tmp);

krsort($tmp);

// var_dump($tmp);

$ranking = [];
$rank = 1;
foreach ($tmp as $num => $c) {

  ksort($c);
  // var_dump($c);

  foreach ($c as $t => $id) {
    $ranking[$rank] = [];

    $ranking[$rank]['id'] = $id;
    $ranking[$rank]['r'] = $rank;
    $ranking[$rank]['n'] = $memberData[$id]['name'];
    $ranking[$rank]['c'] = $num;
    $ranking[$rank]['t'] = $t;

    $rank++;
  }


}

// var_dump($ranking);

// レスポンス

$data = [];

$data['members'] = $members;
$data['quiz'] = $quiz;
$data['ans'] = $ans;
$data['ranking'] = $ranking;
$data['progress'] = $progress;

// レスポンス
api_response($data);
