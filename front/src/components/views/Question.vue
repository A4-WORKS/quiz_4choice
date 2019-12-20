<template>
  <div class="c-main-container">

    <div v-if="standby">
      <div v-if="currentNo === 0" class="please-wait">
        まもなく開始
        <div class="please-wait-sub">Please Wait...</div>
      </div>
      <div v-else>

        <div v-if="currentStatus === 'wait'" class="wait">
          Question. {{currentNo}}
        </div>
        <div v-else-if="currentStatus === 'open'" class="open">
          <div class="q-header">Question. {{currentNo}}</div>
          <div class="q-text">{{quiz[currentNo].q}}</div>

          <div>
            <div class="choice" @click="choice(1)" :class="selected(1)">{{quiz[currentNo].c[1]}}</div>
            <div class="choice" @click="choice(2)" :class="selected(2)">{{quiz[currentNo].c[2]}}</div>
            <div class="choice" @click="choice(3)" :class="selected(3)">{{quiz[currentNo].c[3]}}</div>
            <div class="choice" @click="choice(4)" :class="selected(4)">{{quiz[currentNo].c[4]}}</div>
          </div>

          <div class="connect">
            <div v-if="ansSend==='sending'">回答を送信中....</div>
            <div v-if="ansSend==='finish'">回答を送信しました</div>
          </div>

        </div>
        <div v-else-if="currentStatus === 'close'" class="close">
          <div class="q-header">Question. {{currentNo}}</div>
          <div class="attention">回答を締め切りました</div>
        </div>
        <div v-else-if="currentStatus === 'result'" class="result">
          <div class="q-header">Question. {{currentNo}}</div>
          <div class="attention">
            モニターに注目！！
          </div>
        </div>
        <div v-else-if="currentStatus === 'finish'" class="result">
          <div class="attention">
            まもなくランキング発表！
          </div>
        </div>
      </div>

    </div>

    <hr>
    <div class="debug">
      id:{{id}}<br>
      name:{{name}}<br>
      currentNo:{{currentNo}}<br>
      currentStatus:{{currentStatus}}<br>
      startTime:{{startTime}}<br>
      {{choiceData}}
    </div>

  </div>
</template>

<script>

export default {
  components: {},
  data () {
    return {
      standby: false,
      id: '',
      name: '',
      processLock: false,
      progressCheckId: null,
      progressCheckLock: false,
      currentNo: 0,
      currentStatus: '',
      quiz: {},
      choiceData: {1: 0, 2: 0, 3: 0, 4: 0, 5: 0, 6: 0, 7: 0, 8: 0, 9: 0, 10: 0},
      startTime: 0,
      pramNo: 0,
      ansSend: null
    }
  },
  created: function () {
    console.log('created')
  },
  mounted: function () {
    console.log('mounted')

    this.initialize()
  },
  destroyed: function () {
    console.log('destroyed')

    clearInterval(this.progressCheckId)

  },
  watch: {
    '$route' () {
      this.initialize()
    }
  },
  methods: {
    selected: function (pos) {

      if (this.choiceData[this.currentNo] === 0) {
        return 'choice-ok'
      } else {
        if (this.choiceData[this.currentNo] === pos) {
          return 'selected'
        } else {
          return 'not-selected'
        }
      }

    },
    choice: function (choice) {

      console.log(choice)

      console.log(this.choiceData[this.currentNo])

      this.choiceData[this.currentNo] = choice

      // 選択データ送信

      let t = this.microUnixtime() - this.startTime

      let param = {}
      param['id'] = this.id
      param['n'] = this.currentNo
      param['a'] = choice
      param['t'] = t

      console.log(param)

      this.ansSend = 'sending'

      this.$store.dispatch('quiz/choice', param).then(() => {

        this.ansSend = 'finish'

      }).catch((err) => {
        console.log(err)

      })

    },
    microUnixtime: function () {

      // Dateオブジェクトを作成
      let date = new Date()

      // UNIXタイムスタンプを取得する (ミリ秒単位)
      let time = date.getTime()

      return time
    },
    initialize: function () {
      console.log('initialize')

      this.ansSend = false

      this.id = this.$store.getters['quiz/id']
      this.name = this.$store.getters['quiz/name']

      // IDチェック
      if (this.id === null) {

        console.warn('id is null')

        this.$router.push({name: 'Entry'})

        return
      }

      this.pramNo = this.$route.params['no']
      this.pramNo = parseInt(this.pramNo)
      console.log('pramNo', this.pramNo)

      let _this = this

      this.progressCheckId = setInterval(
        function () {

          console.log('interval')

          _this.progressCheck()

        }, 1000
      )

      this.$store.dispatch('quiz/getQuiz').then(() => {

        this.quiz = this.$store.getters['quiz/quizFront']

        this.startTime = this.microUnixtime()

        this.standby = true

      }).catch((err) => {
        console.log(err)

      })

    },
    progressCheck: function () {

      console.log('progressCheck')

      if (this.progressCheckLock) {
        console.log('interval-skip', this.progressCheckLock)
        return
      }

      this.progressCheckLock = true

      this.$store.dispatch('quiz/getProgress').then(() => {

        this.currentNo = this.$store.getters['quiz/currentNo']
        this.currentStatus = this.$store.getters['quiz/currentStatus']

        this.currentNo = parseInt(this.currentNo)

        if (this.pramNo !== this.currentNo) {
          console.log('change', this.pramNo, this.currentNo)
          this.$router.push({name: 'Question', params: {no: this.currentNo}})
        }

        this.progressCheckLock = false

      }).catch((err) => {
        console.log(err)

        this.progressCheckLock = false
      })

    }
  }
}

</script>

<style lang="scss" scoped>

  .c-main-container {
    width: 100%;
    padding: 10px;
    font-size: 18px;
  }

  .connect {
    text-align: center;
    font-size: 15px;
    height: 20px;
    line-height: 20px;
  }

  .choice-ok {
    background: #eee;
  }

  .selected {
    background: #98f2ff;
  }

  .not-selected {
    background: #eee;
  }

  .choice {
    height: 80px;
    display: flex;
    align-items: center;
    margin: 2px;
    padding: 4px 4px 4px 15px;
    text-align: center;
    cursor: pointer;
  }

  .q-header {
    line-height: 30px;
  }

  .q-text {
    margin-bottom: 20px;
  }

  .wait {
    line-height: 400px;
    font-size: 50px;
  }

  .attention {
    line-height: 200px;
  }

  .open {
    text-align: left;

  }

  .please-wait {
    font-size: 24px;
    text-align: center;
    line-height: 150px;
    position: relative;

    .please-wait-sub {
      position: absolute;
      top: 30px;
      left: 0;
      right: 0;
      font-size: 14px;
    }
  }

  .button {
    border-radius: 4px;
    background: #ffc427;
    width: 200px;
    margin: 10px auto 0;
    padding: 4px;
    cursor: pointer;
  }

  .button-disable {
    border-radius: 4px;
    background: #e9e9e9;
    width: 200px;
    margin: 10px auto 0;
    padding: 4px;
  }

  .debug {
    font-size: 10px;
    margin-top: 15px;
    color: #aaa;
  }

</style>
