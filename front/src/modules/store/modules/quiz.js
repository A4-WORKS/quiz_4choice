/* クライアント関連のデータ */

import Http from '@/modules/http'

const state = {
  name: null,
  id: null,
  currentNo: null,
  currentStatus: null,
  members: {},
  quiz: {},
  ans: {},
  ranking: {},
  quizFront: {}
}

const getters = {
  id (state) {
    return state.id
  },
  name (state) {
    return state.name
  },
  currentNo (state) {
    return state.currentNo
  },
  currentStatus (state) {
    return state.currentStatus
  },
  members (state) {
    return state.members
  },
  quiz (state) {
    return state.quiz
  },
  ans (state) {
    return state.ans
  },
  ranking (state) {
    return state.ranking
  },
  quizFront (state) {
    return state.quizFront
  }

}

const actions = {

  entry (context, params) {

    return new Promise((resolve, reject) => {

      if (typeof params.name === 'undefined') {
        let msg = 'name is undefined'
        reject(new Error(msg))
      }

      let apiUrl = process.env.API_ENDPOINT_URL + 'api_user_entry.php'

      Http.get(apiUrl, params).then(response => {

        console.log('response: ', response)

        if (response.data.STATUS === 200) {

          context.commit('saveEntry', response.data.data)

          resolve()

        } else {
          // エラー処理
          let msg = 'API Error'
          reject(new Error(msg))

        }

      }).catch((err) => {
        reject(err)
      })
    })
  },
  getProgress (context) {

    return new Promise((resolve, reject) => {

      let apiUrl = process.env.API_ENDPOINT_URL + 'api_user_progress.php'

      Http.get(apiUrl).then(response => {

        console.log('response: ', response)

        if (response.data.STATUS === 200) {

          context.commit('saveProgress', response.data.data)

          resolve()

        } else {
          // エラー処理
          let msg = 'API Error'
          reject(new Error(msg))

        }

      }).catch((err) => {
        reject(err)
      })
    })
  },
  getOverView (context) {

    return new Promise((resolve, reject) => {

      let apiUrl = process.env.API_ENDPOINT_URL + 'api_monitor_overview.php'

      Http.get(apiUrl).then(response => {

        console.log('response: ', response)

        if (response.data.STATUS === 200) {

          context.commit('saveOverView', response.data.data)

          resolve()

        } else {
          // エラー処理
          let msg = 'API Error'
          reject(new Error(msg))

        }

      }).catch((err) => {
        reject(err)
      })
    })
  },
  nextProgress (context) {

    return new Promise((resolve, reject) => {

      let apiUrl = process.env.API_ENDPOINT_URL + 'api_monitor_next_progress.php'

      Http.get(apiUrl).then(response => {

        console.log('response: ', response)

        if (response.data.STATUS === 200) {

          context.commit('saveOverView', response.data.data)

          resolve()

        } else {
          // エラー処理
          let msg = 'API Error'
          reject(new Error(msg))

        }

      }).catch((err) => {
        reject(err)
      })
    })

  },
  getQuiz (context) {
    return new Promise((resolve, reject) => {

      let apiUrl = process.env.API_ENDPOINT_URL + 'api_user_quiz.php'

      Http.get(apiUrl).then(response => {

        console.log('response: ', response)

        if (response.data.STATUS === 200) {

          context.commit('saveQuiz', response.data.data)

          resolve()

        } else {
          // エラー処理
          let msg = 'API Error'
          reject(new Error(msg))

        }

      }).catch((err) => {
        reject(err)
      })
    })

  },
  choice (context, param) {

    return new Promise((resolve, reject) => {

      let apiUrl = process.env.API_ENDPOINT_URL + 'api_user_answer.php'

      Http.get(apiUrl, param).then(response => {

        console.log('response: ', response)

        if (response.data.STATUS === 200) {

          resolve()

        } else {
          // エラー処理
          let msg = 'API Error'
          reject(new Error(msg))

        }

      }).catch((err) => {
        reject(err)
      })
    })

  }
}

const mutations = {
  saveEntry (state, value) {
    console.log(value)

    state.id = value.id
    state.name = value.name
  },
  saveProgress (state, value) {
    console.log(value)

    state.currentNo = value.progress.current
    state.currentStatus = value.progress.status

  },
  saveOverView (state, value) {
    console.log(value)

    state.members = value.members
    state.quiz = value.quiz
    state.ans = value.ans
    state.ranking = value.ranking

    state.currentNo = value.progress.current
    state.currentStatus = value.progress.status

  },
  saveQuiz (state, value) {
    console.log(value)

    state.quizFront = value.quiz
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
