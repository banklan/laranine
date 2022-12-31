/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router';
import Vuex from 'vuex';

import vuetify from './plugins/vuetify';
import Routes from './router/routes';
import VeeValidate from 'vee-validate';
import { store } from './store/index';
import './filters'
import App from './components/App'
import Moment from 'vue-moment'
import 'animate.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css'

Vue.use(VueRouter)
Vue.use(VeeValidate)
Vue.use(Moment)

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    linkActiveClass: 'font-semibold'
})


router.beforeEach((to, from, next) => {
    const requiresAdminAuth = to.matched.some(rec => rec.meta.requiresAdminAuth);
    const authAdmin = store.state.authAdmin
    if (requiresAdminAuth && authAdmin == null) {
        next('/admin-login')
    }else if(to.path == '/admin-login' && authAdmin){
        next('/admin')
    }else if(to.path == '/login' && authAdmin){
        next('/')
    }else if(to.path == '/register' && authAdmin){
        next('/')
    }else {
        next()
    }
});


router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(rec => rec.meta.requiresAuth);
    const authUser = store.state.authUser
    if (requiresAuth && authUser == null) {
        store.commit('redirectOnLogin', to.path)
        next('/login')
    }else if(to.path == '/login' && authUser){
        next('/user/dashboard')
    }else if(to.path == '/register' && authUser){
        next('/user/dashboard')
    }else if(to.path.substring(0, 6) == '/admin' && authUser){
        next('/user/dashboard')
    }else {
        next()
    }
});


// axios interceptor for expired token
axios.interceptors.response.use(null, (err) =>
{
    if (err.response.status == 401) {
        store.commit('logOutAuthUser')
        store.commit('logOutAdmin')
        if (router.currentRoute.name !== ('AdminLogin' || 'Login')) {
            router.push('/')
        }
        router.push('/')
    }
    return Promise.reject(err)
})




Vue.component('welcome-banner', require('./components/Children/WelcomeBanner.vue').default);
// Vue.component('categories', require('./components/includes/Categories.vue').default);
Vue.component('welcome-cards', require('./components/Children/WelcomeCards.vue').default);
Vue.component('sec-nav', require('./components/Children/SecondaryNav.vue').default);
Vue.component('featured-sps', require('./components/Children/FeaturedSps.vue').default);
Vue.component('popular-sps', require('./components/Children/PopularSps.vue').default);
Vue.component('popular-cats', require('./components/Children/PopularCats.vue').default);
Vue.component('auth-pageheader', require('./components/Children/AuthPageHeader.vue').default);
Vue.component('service-mover', require('./components/Children/ServiceMover.vue').default);
Vue.component('service-socials', require('./components/Children/ServiceSocials.vue').default);
Vue.component('service-portfolio', require('./components/Children/ServicePortfolio.vue').default);
Vue.component('profile-password-card', require('./components/Children/ProfilePasswordCard.vue').default);
Vue.component('admin-search', require('./components/Children/AdminSearch.vue').default);
Vue.component('service-card', require('./components/Children/ServiceCard.vue').default);


const app = new Vue({
    el: '#app',
    components: { App },
    router,
    vuetify,
    store
});
