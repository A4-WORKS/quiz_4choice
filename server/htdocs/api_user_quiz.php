<?php
/**
 * クイズ情報取得
 */

require('../libs/api_response.php');
require('../libs/quiz.php');

// クイズ情報取得
$quiz = get_quiz();

$data['quiz'] = $quiz;

$json = json_encode($data);

// レスポンス
api_response($data);
