/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


import Vue from 'vue'
import Validations from 'vuelidate'
import store from './store/store'
import DateFilter from './filters/date'
import CurrencyFilter from './filters/currency'
import UppercaseFilter from './filters/uppercase'


import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "1337cb518b5cc4704455",
    cluster: "eu",
    forceTLS: true,
    auth: {
        headers: {
            Authorization: 'Bearer ' + (typeof Laravel !== 'undefined' ? Laravel.apiToken : "")
        },
    },
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.filter('date', DateFilter)
Vue.filter('currency', CurrencyFilter)
Vue.filter('uppercase', UppercaseFilter)

Vue.use(Validations)
const app = new Vue({
    // el: '#app',
    store,
    methods: {
        closeEntryModal() {
            this.$refs.modal1.close()
        },
        openEntryModal() {
            this.$refs.modal1.open()
        },
        openImportModal() {
            this.$refs.modal2.open()
        },
        init(data) {
            const user = 'user' in data ? data.user : null
            store.dispatch('user/init', user)

        },
    },
    computed: {
        locked() {
            return this.$store.getters['user/user'].locked
        }
    }
})

window.vueApp = app

