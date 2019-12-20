
const Common = {
  install (Vue, options) {
  },
  unixtime: function () {

    // Dateオブジェクトを作成
    let date = new Date()

    // UNIXタイムスタンプを取得する (ミリ秒単位)
    let time = date.getTime()

    // UNIXタイムスタンプを取得する (秒単位 - PHPのtime()と同じ)
    let unixtime = Math.floor(time / 1000)

    return unixtime
  }
}

export default Common
