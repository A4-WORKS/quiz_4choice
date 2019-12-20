<?php

require('../libs/progress.php');
require('../libs/data_dir.php');

$msg = null;

if (isset($_REQUEST['type'])) {

  $type = $_REQUEST['type'];
  //var_dump($type);

  if ($type === 'entry') {

    // データ保存領域
    $dataPath = getDataDir();

    $ary = @scandir($dataPath);

    foreach ($ary as $file) {

      if (strpos($file, '_') === false) {
        continue;
      }

      $path = $dataPath . '/' . $file;

      unlink($path);

      //var_dump($path);

    }

    $msg = 'エントリーをリセットしました';

  } else if ($type === 'progress') {
    resetProgress();

    $msg = 'プログレスをリセットしました';
  }

}

echo <<<END
<a href="reset.php?type=progress">プログレスリセット</a>
<a href="reset.php?type=entry">エントリーリセット</a>
<hr>
{$msg}
END;

