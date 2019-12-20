<?php


function progressDataPath()
{

  $progressDataPath = getDataDir().'/progress.data';

  return $progressDataPath;
}

function getProgress()
{

  $progressDataPath = progressDataPath();

  if (!file_exists($progressDataPath)) {

    $progress = [];

    $progress['current'] = 0; // 現在の番号 0は開始前
    $progress['status'] = 'wait'; // wait, open, close ,result, finish(current:10の時だけ)

    $json = json_encode($progress);

    file_put_contents($progressDataPath, $json);

  } else {
    $json = file_get_contents($progressDataPath);

    $progress = json_decode($json, true);

  }

  return $progress;

}

function setNextProgress()
{

  $progress = getProgress();

  //var_dump($progress);

  // 例外処理
  if ($progress['current'] == 10 && $progress['status'] == 'finish') {
    // なにもしない
  } elseif ($progress['current'] == 10 && $progress['status'] == 'result') {

    $progress['status'] = 'finish';

  } else if ($progress['current'] == 0) {

    $progress['current'] = 1;

  } else {

    if ($progress['status'] == 'wait') {

      $progress['status'] = 'open';

    } else if ($progress['status'] == 'open') {

      $progress['status'] = 'close';

    } else if ($progress['status'] == 'close') {

      $progress['status'] = 'result';

    } else if ($progress['status'] == 'result') {

      $progress['current']++;
      $progress['status'] = 'wait';
    }

  }

  $json = json_encode($progress);

  $progressDataPath = progressDataPath();

  file_put_contents($progressDataPath, $json);

  return $progress;

}

function resetProgress(){

  $progress = [];

  $progress['current'] = 0;
  $progress['status'] = 'wait';

  $json = json_encode($progress);

  $progressDataPath = progressDataPath();

  file_put_contents($progressDataPath, $json);

}
