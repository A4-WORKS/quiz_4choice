import axios from 'axios'

// const SECURITY_CSRF_COOKIE = 'RESPONSE_TOKEN'
// const SECURITY_CSRF_HEADER = 'X-CSRFToken'

// cookieを付与
// axios.defaults.withCredentials = true

var Http = {
  name: 'http',
  get: function (url, params) {
    return axios.get(url, {params: params})
  },
  post: function (url, params, options) {
    options = {}
    return axios.post(url, params, options)
  },
  patch: function (url, params, options) {
    options = {}
    return axios.patch(url, params, options)
  },
  put: function (url, params, options) {
    options = {}
    return axios.put(url, params, options)
  },
  delete: function (url, params) {
    return axios.delete(url, params)
  },
  supplyCsrfHeader: function (options) {
    // let responseToken = this.getCookie(SECURITY_CSRF_COOKIE)
    // options = options || {}
    // options.headers = options.headers || {}
    // options.headers[SECURITY_CSRF_HEADER] = responseToken
    // return options
  }
}

Http.install = function (Vue, options) {
  Vue.prototype.$http = Http
}

export default Http
