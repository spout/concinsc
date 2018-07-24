import Vue from 'vue'
import App from './App.vue'
import localforage from 'localforage'
import 'bootstrap'
import './assets/scss/app.scss'
import $ from 'jquery'
import './registerServiceWorker'

Vue.config.productionTip = false

localforage.config({
  name: 'concinsc'
})

const GlobalPlugin = {
  install (Vue, options) {
    Vue.prototype.$localforage = localforage
  }
}

if (window.navigator.onLine) {
  // let exportUrl = 'http://localhost:8080/export.json'
  // let exportUrl = 'http://localhost/concinsc/export.php'
  let exportUrl = '/export.json'

  $.get(exportUrl).done(function (data) {
    localforage.setItem('animals', data)
  })
}

Vue.use(GlobalPlugin)

new Vue({
  render: h => h(App)
}).$mount('#app')
