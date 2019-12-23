<template>
  <div class="fullHD">

    <div class="progress-control">
      <div class="button" @click="start" v-if="currentNo===0">開始</div>
      <div v-else-if="currentNo===10 && currentStatus==='finish'" class="button" @click="finish">最終結果発表</div>
      <div v-else>
        <div v-if="currentStatus==='wait'" class="button" @click="open">回答受付</div>
        <div v-if="currentStatus==='open'" class="button" @click="close">回答終了</div>
        <div v-if="currentStatus==='close'" class="button" @click="result">正解発表</div>
        <div v-if="currentStatus==='result'" class="button" @click="next">次の問題</div>
      </div>
    </div>

    <div v-if="currentNo !== 0">
      <div class="header">
        <div class="number">No {{currentNo}}
          <div class="status-text">
            {{statusText()}}
          </div>
        </div>
        <div class="q-text">
          <div v-if="currentStatus !== 'wait'">
            {{quiz[currentNo].q}}<br>
            <div v-if="currentStatus === 'open' && countDown<=0">Time up!</div>
            <div v-else-if="currentStatus === 'open'">回答時間 残り: {{countDownText}}秒</div>
          </div>
          <div v-else>Please wait.</div>
        </div>
      </div>
      <div class="choice-container">
        <div v-if="currentStatus === 'result'" class="choice-result">
          <div class="item" :class="answer(1)">1. {{quiz[currentNo].c[1]}}</div>
          <div class="item" :class="answer(2)">2. {{quiz[currentNo].c[2]}}</div>
          <div class="item" :class="answer(3)">3. {{quiz[currentNo].c[3]}}</div>
          <div class="item" :class="answer(4)">4. {{quiz[currentNo].c[4]}}</div>
        </div>
        <div v-else-if="currentStatus !== 'wait'" class="choice">
          <div class="item">1. {{quiz[currentNo].c[1]}}</div>
          <div class="item">2. {{quiz[currentNo].c[2]}}</div>
          <div class="item">3. {{quiz[currentNo].c[3]}}</div>
          <div class="item">4. {{quiz[currentNo].c[4]}}</div>
        </div>
      </div>
    </div>
    <div v-else class="entry-text">エントリー受付中</div>

    <div class="main-list">
      <div class="item" v-for="(name, id) in members" :key="'m'+id">
        <div :class="memberStatus(id)" class="item-box">
          <div class="name">{{name}}</div>
          <div v-if="currentNo >= 1">
            <div v-if="ans[currentNo][id].a !==0">
              <div v-if="currentStatus === 'close' || currentStatus === 'result'" class="choice-text">
                {{ans[currentNo][id].a}}
              </div>
              <div v-else-if="ans[currentNo][id].a !==0">回答済</div>
            </div>
            <div v-else-if="currentStatus === 'wait'"></div>
            <div v-else class="choice-none-text">
              未回答
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="debug">
      currentNo: {{currentNo}} - currentStatus: {{currentStatus}} <span @click="pollingControl">pollingコントロール</span>
      pollingControlStatus: {{pollingControlStatus}}
      <a :href="resetUrl" target="_blank">リセット</a>
      <br>
    </div>

  </div>
</template>

<script>

export default {
  components: {},
  data () {
    return {
      pollingControlStatus: true,
      intervalId: null,
      intervalCountdownId: null,
      processLock: false,
      currentNo: 0,
      currentStatus: '',
      members: {},
      quiz: {},
      ans: {},
      ranking: {},
      countDown: 0,
      maxCount: 150
    }
  },
  created: function () {

    this.polling()

    let _this = this

    if (this.intervalId === null) {
      this.intervalId = setInterval(() => {
        _this.polling()
      }, 1000)
    }

    if (this.intervalCountdownId === null) {
      this.intervalCountdownId = setInterval(() => {
        this.countDown = this.countDown - 1
      }, 100)
    }

  },
  destroyed: function () {

    clearInterval(this.intervalId)
    clearInterval(this.intervalCountdownId)

  },
  computed: {
    resetUrl: function () {
      return process.env.API_ENDPOINT_URL + 'reset.php'
    },
    countDownText: function () {

      let countDown = this.countDown

      if (countDown <= 0) {
        countDown = 0
      } else {
        countDown = countDown / 10
      }

      if (countDown % 1 === 0) {
        countDown = countDown + '.0'
      }

      return countDown
    }
  },
  methods: {
    finish: function () {

      if (this.currentNo !== 10) {
        console.log('currentNo not 10')
        return
      }

      if (this.currentStatus !== 'finish') {
        console.log('currentStatus not finish')
        return
      }

      this.$router.push({name: 'Result'})

    },
    open: function () {
      if (this.currentStatus !== 'wait') {
        console.log('currentStatus not wait')
        return
      }

      this.nextProgress()

    },
    close: function () {
      if (this.currentStatus !== 'open') {
        console.log('currentStatus not open')
        return
      }

      this.nextProgress()

    },
    result: function () {

      if (this.currentStatus !== 'close') {
        console.log('currentStatus not close')
        return
      }

      this.nextProgress()
    },
    next: function () {

      if (this.currentStatus !== 'result') {
        console.log('currentStatus not result')
        return
      }

      this.nextProgress()
    },
    start: function () {

      if (this.currentNo !== 0) {
        console.log('currentNo not 0')
        return
      }

      this.nextProgress()
    },
    nextProgress: function () {

      this.$store.dispatch('quiz/nextProgress').then(() => {

        // 更新
        this.polling()

        this.processLock = false

      }).catch((err) => {
        console.log(err)

        this.processLock = false
      })

    },
    polling: function () {

      console.log('polling')

      if (this.processLock) {
        console.log('polling-skip')
        return
      }

      if (this.pollingControlStatus === false) {
        console.log('polling-stop')
        return
      }

      this.processLock = true

      this.$store.dispatch('quiz/getOverView').then(() => {

        this.currentNo = this.$store.getters['quiz/currentNo']

        let currentStatus = this.$store.getters['quiz/currentStatus']

        if (currentStatus !== this.currentStatus) {
          this.currentStatus = currentStatus

          if (currentStatus === 'open') {
            console.log('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<')

            this.countDown = this.maxCount
          }
        }

        this.members = this.$store.getters['quiz/members']
        this.quiz = this.$store.getters['quiz/quiz']
        this.ans = this.$store.getters['quiz/ans']
        this.ranking = this.$store.getters['quiz/ranking']

        this.processLock = false

      }).catch((err) => {
        console.log(err)

        this.processLock = false
      })

    },
    pollingControl: function () {

      if (this.pollingControlStatus) {
        this.pollingControlStatus = false
      } else {
        this.pollingControlStatus = true
      }

    },
    memberStatus: function (id) {

      if (this.currentStatus === 'result') {
        if (this.ans[this.currentNo][id].result) {
          return 'correct'
        } else {
          return 'incorrect'
        }
      } else if (this.currentStatus === 'open') {
        if (this.ans[this.currentNo][id].a !== 0) {
          return 'entry'
        } else {
          return 'not-entry'
        }
      } else if (this.currentStatus === 'close') {
        if (this.ans[this.currentNo][id].a !== 0) {
          return 'entry'
        } else {
          return 'not-choice'
        }
      } else {
        return ''
      }

    },
    statusText: function () {
      if (this.currentStatus === 'wait') {
        return 'wait'
      } else if (this.currentStatus === 'open') {
        return '回答受付中'
      } else if (this.currentStatus === 'close') {
        return '回答締め切り'
      } else if (this.currentStatus === 'result') {
        return ''
      }
    },
    answer: function (choice) {

      if (this.quiz[this.currentNo].a === choice) {
        return 'correct-box'
      } else {
        return 'incorrect-box'
      }

    }
  }
}

</script>

<style lang="scss" scoped>

  .choice-text {
    font-size: 40px;
  }

  .choice-none-text {
    line-height: 40px;
  }

  .item-box {
    height: 100px;
  }

  .incorrect {
    background: #ff8e92;
  }

  .correct {
    background: #1ebeff;
  }

  .not-entry {
    background: #eee;
  }

  .entry {
    background: #fff;
  }

  .not-choice {
    background: #eee;
    color: #999;
  }

  .fullHD {
    width: 100%;
    height: 1080px;
    background: #fff;
  }

  .progress-control {
    position: absolute;
    right: 10px;
    top: 10px;
  }

  .main-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-content: center;
    margin: 20px;

    .item {
      border: 1px solid #000;
      width: 15%;
      margin: 4px;
      overflow: hidden;

      .name {
        font-size: 25px;
        white-space: nowrap;
        width: 100%;
      }
    }
  }

  .button {
    background: #bbbfd8;
    padding: 4px;
    border-radius: 4px;
    width: 200px;
    margin: 4px auto 0;
    cursor: pointer;
  }

  .header {
    background: #eee;
    display: flex;

    .number {
      font-size: 80px;
      margin: 10px;
    }

    .q-text {
      font-size: 30px;
      margin: 10px;
    }

  }

  .choice-container {
    padding-top: 20px;
    height: 200px;
  }

  .choice-result,
  .choice {
    display: flex;
    flex-wrap: wrap;
    margin: 0 auto;
    align-content: space-around;
    justify-content: space-around;

    .item {
      font-size: 30px;
      padding: 20px;
      width: 48%;
      background: #fafafa;
      margin: 2px;
      border: solid 1px #ddd;
    }
  }

  .choice-result {

    .correct-box {
      background: #23f0ff;
    }

    .incorrect-box {
      background: #fafafa;
      color: #aaa;
    }

  }

  .entry-text {
    text-align: center;
    font-size: 80px;
  }

  .debug {
    position: absolute;
    bottom: 0;
    right: 5px;
    font-size: 10px;
  }

  .status-text {
    font-size: 15px;
  }

</style>
