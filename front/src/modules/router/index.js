import Vue from 'vue'
import Router from 'vue-router'

import Entry from '@/components/views/Entry'
import Question from '@/components/views/Question'

import Monitor from '@/components/views/Monitor'
import Result from '@/components/views/Result'

// -----------------------------------------------------

Vue.use(Router)

export default new Router({
  base: '.',
  // mode: 'history',
  routes: [

    {
      path: '/monitor',
      name: 'Monitor',
      component: Monitor,
      meta: {
        title: 'Monitor'
      }
    },
    {
      path: '/result',
      name: 'Result',
      component: Result,
      meta: {
        title: 'Result'
      }
    },
    {
      path: '/',
      name: 'Entry',
      component: Entry,
      meta: {
        title: 'Entry'
      }
    },
    {
      path: '/question/:no(\\d+)',
      name: 'Question',
      component: Question,
      meta: {
        title: 'Question'
      }
    }

  ],
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      // スクロールをトップに戻す
      return {x: 0, y: 0}
    }

  }
})
