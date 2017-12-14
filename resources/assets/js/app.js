
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Notifications from 'vue-notification'
Vue.use(Notifications);
import Vuetable from 'vuetable-2';
Vue.use(Vuetable);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('url-shortener-form', require('./components/UrlShortenerForm'));
Vue.component('url-table', require('./components/UrlTable'));

window.vEvent = new class {
    constructor() {
        this.vue = new Vue();
    }

    emit(event, data = null) {
        this.vue.$emit(event, data);
    }

    on(event, callback) {
        this.vue.$on(event, callback);
    }
};

const app = new Vue({
    el: '#app',
    mounted() {
        if(window.google_auth !== false){
            this.$notify({
                type: 'success',
                text: 'Added or updated Google User with the email ' + google_auth.email
            });
        }

    }
});
