<?php

require('../libs/api_response.php');
require('../libs/progress.php');
require('../libs/data_dir.php');

$progress = getProgress();
//var_dump($progress);

$data = [];
$data['progress'] = $progress;

// レスポンス
api_response($data);
