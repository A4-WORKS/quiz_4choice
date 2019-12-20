<template>
  <div class="fullHD">

    <div class="progress-control">
      <div class="button" @click="next">Next</div>
    </div>

    <div class="header">最終結果発表</div>

    <div>
      <div v-for="(data, id) in result" :key="'m'+id">

        <div class="ranking-container" :class="rankingEffect(data.r)">
          <span class="rank-no">{{data.r}}位</span>{{name(data.id)}}
        </div>
      </div>
    </div>

  </div>
</template>

<script>

export default {
  components: {},
  data () {
    return {
      members: {},
      quiz: {},
      ans: {},
      ranking: [],
      result: []
    }
  },
  created: function () {

    this.getData()

  },
  methods: {
    rankingEffect: function (rank) {

      if (rank === 1) {
        return 'rank1'
      } else if (rank === 2) {
        return 'rank2'
      } else if (rank === 3) {
        return 'rank3'
      } else if (rank <= 5) {
        return 'rank5'
      }

      return ''
    },
    name: function (id) {

      if (typeof this.members[id] !== 'undefined') {
        return this.members[id]
      }

    },
    next: function () {

      console.log(this.ranking.length)

      if (this.ranking.length <= 0) {
        return
      }

      let data = this.ranking.pop()

      this.result.unshift(data)

    },
    getData: function () {

      this.$store.dispatch('quiz/getOverView').then(() => {

        this.currentNo = this.$store.getters['quiz/currentNo']
        this.currentStatus = this.$store.getters['quiz/currentStatus']

        this.members = this.$store.getters['quiz/members']

        let tmp = this.$store.getters['quiz/ranking']

        let ranking = []

        Object.keys(tmp).forEach(function (key) {
          console.log(tmp[key])

          ranking.push(tmp[key])
        })

        this.ranking = ranking

      }).catch((err) => {
        console.log(err)
      })

    }

  }
}

</script>

<style lang="scss" scoped>

  .header {
    font-size: 80px;
  }

  .progress-control {
    position: absolute;
    right: 10px;
    top: 10px;
  }

  .button {
    background: #bbbfd8;
    padding: 4px;
    border-radius: 4px;
    width: 200px;
    margin: 4px auto 0;
    cursor: pointer;
  }

  .ranking-container {
    width: 80%;
    background: #eee;
    margin: 10px auto 0;
    padding: 20px;
    font-size: 40px;
    text-align: left;
    border-radius: 40px 0 500px 0;
    text-shadow: 2px 2px 3px rgba(255,255,255,0.8);
  }

  .rank-no {
    display: inline-block;
    width: 300px;
    padding-left: 30px;
  }

  .rank1{
    font-size: 100px;
    background: #ffd700;
  }

  .rank2{
    font-size: 80px;
    background: #d3d3d3;
  }

  .rank3{
    font-size: 80px;
    background: #f4a460;
  }

  .rank5{
    font-size: 55px;
    background: #e6e6fa;
  }

</style>
