/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import VueApexCharts from 'vue-apexcharts'

require('./bootstrap');

window.Vue = require('vue');
import { BootstrapVue } from 'bootstrap-vue'

Vue.component('game-screen', require('./components/GameScreen.vue').default);
Vue.component('admin-live', require('./components/AdminLive.vue').default);


Vue.use(VueApexCharts)

Vue.component('apexchart', VueApexCharts)

Vue.filter('balance', function (value) {
    return Math.round(parseFloat(value) * 100) / 100
})

import axios from './bootstrap';
Vue.prototype.$axios = axios;
Vue.use(BootstrapVue)
window.Vue = Vue;
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
