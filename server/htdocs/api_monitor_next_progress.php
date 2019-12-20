<?php

require('../libs/error.php');
require('../libs/api_response.php');
require('../libs/data_dir.php');
require('../libs/progress.php');

$progress = setNextProgress();
//var_dump($progress);

$data = [];
$data['progress'] = $progress;

// レスポンス
api_response($data);
