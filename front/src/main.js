// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import VueSession from 'vue-session'
import VueCookie from 'vue-cookie'
import VueI18n from 'vue-i18n'
import 'es6-promise/auto'

import router from './modules/router'
import store from './modules/store'
import Common from './modules/common'

import I from './components/common/_Icon'

const messages = require('./messages.json')

Vue.config.productionTip = false

/** モジュールの登録 */
Vue.use(VueSession)
Vue.use(VueCookie)
Vue.use(VueI18n)
Vue.use(Common)

/** コンポーネントの登録 */
Vue.component('I', I)

const i18n = new VueI18n({
  locale: 'ja', // デフォルト言語をjaで日本語に(この値をenに変更すると英語になる)
  messages: messages
})

router.beforeEach((to, from, next) => {
  // 遷移時に処理を挟みたい場合はここに記述する
  // nextを実行しなければ遷移しない

  next()

})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  i18n: i18n,
  router,
  store,
  components: {App},
  template: '<App/>'
})
