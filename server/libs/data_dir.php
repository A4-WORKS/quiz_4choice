<?php

function getDataDir(){

  $dataDir = '/tmp/event2019';

  if(!is_dir($dataDir)){
    mkdir($dataDir);
  }

  if(!is_writable($dataDir)){
    echo 'data dir is not writable.';
    exit;
  }

  return $dataDir;

}
