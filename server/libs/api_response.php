<?php


function api_response(array $data, $status = 200)
{

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Content-Type');
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PATCH');
  header('Content-Type: application/json');

  $jsonData = [];
  $jsonData['STATUS'] = $status;
  $jsonData['request'] = $_REQUEST;
  $jsonData['data'] = $data;

  $json = json_encode($jsonData);

  echo $json;

}
