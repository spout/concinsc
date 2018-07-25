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

Vue.use(GlobalPlugin)

new Vue({
  render: h => h(App)
}).$mount('#app')
