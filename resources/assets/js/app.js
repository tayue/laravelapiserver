window.Vue = require('vue');
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import Dashboard from './components/Dashboard.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Clients from './components/passport/Clients.vue';
require('./bootstrap');
// Vue.use(VueRouter);
// Vue.use(VueAxios, axios);
// axios.defaults.baseURL = 'http://api.demo.test/';
//
// Vue.component(
//     'passport-clients',
//     require('./components/passport/Clients.vue')
// );
//
// Vue.component(
//     'passport-authorized-clients',
//     require('./components/passport/AuthorizedClients.vue')
// );
//
// Vue.component(
//     'passport-personal-access-tokens',
//     require('./components/passport/PersonalAccessTokens.vue')
// );
// console.log($("#app").html());
// const router = new VueRouter({
//     routes: [{
//         path: '/',
//         name: 'home',
//         component: Home
//     },{
//         path: '/register',
//         name: 'register',
//         component: Register,
//         meta: {
//             auth: false
//         }
//     },{
//         path: '/login',
//         name: 'login',
//         component: Login,
//         meta: {
//             auth: false
//         }
//     },{
//         path: '/dashboard',
//         name: 'dashboard',
//         component: Dashboard,
//         meta: {
//             auth: true
//         }
//     },{
//         path: '/clients',
//         name: 'clients',
//         component: Clients,
//         meta: {
//             auth: false
//         }
//     },
//     ]
// });
// Vue.router = router
// Vue.use(require('@websanova/vue-auth'), {
//     auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
//     http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
//     router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
// });
// App.router = Vue.router
// new Vue(App).$mount('#app');
