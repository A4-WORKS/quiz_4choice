# スマホ対応 4択クイズシステム

全10問の4択クイズがおこなえます。
進行用のモニタシステムと、回答者用システムの2部構成となっています。
DB不要で、vueをビルドできる環境＆PHPが動作する環境があれば稼働させる事ができます。
最大参加人数は特に制限がありませんが、APIサーバーのスペック次第となります。

## 最初に。。
忘年会イベント用に超短納期でやっつけたシステムです。  
その為、あちらこちら突っ込みどころがある仕上がりとなっております。
予めご了承ください。

## クイズ問題の変更について
/server/config.php を変更してください。
10問固定前提のシステムの為、設問数を変更する場合は適宜改修をしてください。

### APIサーバーの準備
/server配下のファイルをPHPが動作するサーバーに設置します。  
データは/tmp配下に書き込みします。

###UI側の準備
/front/config/prod.env.js を開きます。
API_ENDPOINT_URL にAPIサーバーのURLを設定します。  
※最後は"/"で終わる必要があります

$ cd /front
$ npm install
$ npm run dev

ローカルサーバーが起動したら
http://localhost:8080 にアクセスし、動作確認を行います。

ファイルをビルドします。  
$ npm rub build

/front/dist に出力されるので、任意のサーバーに設置します。  
混乱をさける為、/server のファイル群とは別のディレクトリに設置するとこをオススメします。  
サブドメンを使うのも良いかとおもいます。

### 進行管理用画面
http://localhost:8080/#/monitor

### データのリセット
http://localhost:8080/#/monitor の画面下部にリセット用のリンクを設置しています。

### 注意点
進行管理用の同期処理の為に、APIサーバーに対し頻繁なポーリング処理を行っています。

## 動作環境
サーバーサイド:PHP7系  
フロント: vue.js(npm)

## フォルダ構成
/front  
  vue関連のファイル一式  
/server  
  APIまわりのファイル一式  

## vue Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

```
