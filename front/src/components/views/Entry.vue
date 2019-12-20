<template>
  <div class="c-main-container">

    <div class="header">
      2019 忘年会<br>
      クイズイベント<br>
    </div>

    <input type="text" class="input" name="nickname" placeholder="なまえを入力" v-model="name">
    <div class="attention">誰だか分かる名称を入力しよう(苗字推奨)</div>

    <div v-if="name!=='' && !processLock" class="button" @click="entry">開始</div>
    <div v-else class="button-disable">開始</div>

  </div>
</template>

<script>

export default {
  components: {},
  data () {
    return {
      name: '',
      processLock: false
    }
  },
  methods: {
    entry: function () {
      console.log('entry')
      console.log('name', this.name)

      this.processLock = true

      let data = {name: this.name}

      this.$store.dispatch('quiz/entry', data).then(() => {

        let _this = this

        setTimeout(() => {
          _this.processLock = false
        }, 1000)

        this.$router.push({name: 'Question', params: {no: 1}})

      }).catch((err) => {
        console.log(err)

        this.processLock = false
      })

    }
  }
}

</script>

<style lang="scss" scoped>
  .c-main-container {
    width: 100%;
    padding: 10px;
  }

  .header {
    margin-top: 10px;
    font-weight: 900;
    font-size: 24px;
    margin-bottom: 10px;
  }

  .input {
    border: 1px solid #444;
    padding: 4px;
    border-radius: 4px;
    width: 200px;
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

  .attention {
    margin-top: 10px;
    font-size: 12px;
  }

</style>
