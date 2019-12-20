<?php

function get_quiz()
{

  // クイズ設定ファイル読み込み
  require_once(dirname(dirname(__FILE__)) . '/config.php');

  if (isset($quiz)) {
    return $quiz;
  } else {
    return [];
  }

}

