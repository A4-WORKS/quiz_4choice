<?php

/**
 * @param $msg
 */
function error($msg){

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Content-Type');
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PATCH');
  header('Content-Type: application/json');

  $data = [];
  $data['STATUS'] = 500;
  $data['message'] = $msg;
  $data['request'] = $_REQUEST;

  $json = json_encode($data);

  echo $json;

  exit;
}
