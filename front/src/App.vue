<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>

export default {
  name: 'App',
  watch: {
    '$route' (to) {
      console.log(to)

      this.setTitle()
    }
  },
  created: function () {
    this.setTitle()
  },
  methods: {
    setTitle: function () {

      let title = ''

      if (this.$route.meta.title !== '') {
        title = process.env.APP_PREFIX + this.$route.meta.title + ' | ' + process.env.APP_TITLE
      } else {
        title = process.env.APP_PREFIX + process.env.APP_TITLE
      }

      document.title = title

    }
  }
}

</script>

<style lang="scss">
  @import '../node_modules/ress';
  @import './assets/styles/_common.scss';
  @import './assets/styles/_margin_padding.scss';
  @import './assets/styles/style.scss';

  #app {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    background: #fff;
  }

</style>
