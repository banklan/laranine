import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import router from '../router/routes'
import axios from 'axios';
// import auth from './auth'

Vue.use(Vuex, axios)

const auth_user = window.localStorage.getItem('authUser')
const authUser = auth_user ? JSON.parse(auth_user) : null

const user_loggedin = window.localStorage.getItem('userIsLoggedIn')
const userIsLoggedIn = user_loggedin ? true : false

const authServ = window.localStorage.getItem('authUserService')
const authUserService = authServ ? JSON.parse(authServ) : null

const auth_admin = window.localStorage.getItem('authAdmin')
const authAdmin = auth_admin ? JSON.parse(auth_admin) : null;

const adminlogged_in = window.localStorage.getItem('adminIsLoggedIn')
const adminIsLoggedIn = adminlogged_in ? true : false

const redirect = window.localStorage.getItem('redirOnlogin')
const redirectOnLogin = redirect ? redirect : null

export const store = new Vuex.Store({
    state: {
        api: 'http://localhost:8000/api',
        userIsLoggedIn: userIsLoggedIn,
        authUser: authUser,
        authUserService: authUserService,
        adminIsLoggedIn: adminIsLoggedIn,
        authAdmin: authAdmin,
        redirectOnLogin: redirectOnLogin,
        PgScrollPosition: null,
        serviceImgExpected: false,
        serviceImageAdded: false,
        maxPortfolio: false,
        portfolioCreated: false,
        serviceUpdated: false,
        portfolioUpdated: false,
        authUserUpdated: false,
        AdminUpdatedSUProfilePic: false,
        adminUpdatedSuperUser: false,
        adminUpdatedUser: false,
        adminDeletedUser: false,
        adminDeletedPf: false,
        adminUpdatedPf: false,
        adminDeletedPf: false,
    },
    getters: {
        api(state){
            return state.api
        },
        authUser(state)
        {
            return state.authUser
        },
        userIsLoggedIn(state){
            return state.userIsLoggedIn
        },
        authUserService(state)
        {
            return state.authUserService
        },
        authAdmin(state){
            return state.authAdmin
        },
        adminIsLoggedIn(state){
            return state.adminIsLoggedIn
        },
        redirectOnLogin(state)
        {
            return state.redirectOnLogin
        },
        pgScrollPosition(state){
            return state.PgScrollPosition
        },
        serviceImgExpected(state)
        {
            return state.serviceImgExpected
        },
        serviceImageAdded(state)
        {
            return state.serviceImageAdded
        },
        maxPortfolio(state)
        {
            return state.maxPortfolio
        },
        portfolioCreated(state)
        {
            return state.portfolioCreated
        },
        serviceUpdated(state)
        {
            return state.serviceUpdated
        },
        portfolioUpdated(state)
        {
            return state.portfolioUpdated
        },
        authUserUpdated(state)
        {
            return state.authUserUpdated
        },
        AdminUpdatedSUProfilePic(state)
        {
            return state.AdminUpdatedSUProfilePic
        },
        adminUpdatedSuperUser(state)
        {
            return state.adminUpdatedSuperUser
        },
        adminUpdatedUser(state)
        {
            return state.adminUpdatedUser
        },
        adminDeletedUser(state)
        {
            return state.adminDeletedUser
        },
        adminDeletedPf(state)
        {
            return state.adminDeletedPf
        },
        adminUpdatedPf(state)
        {
            return state.adminUpdatedPf
        },
        adminDeletedPf(state)
        {
            return state.adminDeletedPf
        },
    },
    actions: {
        resetAuthService(context)
        {
            axios.get(context.state.api + '/auth/get_auth_service', {
                headers: {
                    "Authorization": `Bearer ${context.state.authUser.token}`
                }
            }).then((res) =>
            {
                context.commit('updateService', res.data)
            })
        },
        // portfolioUpdated(context)
    },
    mutations:{
        checkPgScrollHeight(state){
            state.PgScrollPosition = window.scrollY
        },
        userLoginSuccess(state, payload)
        {
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.adminIsLoggedIn = false
            state.authAdmin = null
            state.userIsLoggedIn = true
            state.authUser = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            window.localStorage.setItem('userIsLoggedIn', true)
        },
        setAuthUserService(state, payload)
        {
            state.authUserService = payload
            window.localStorage.setItem('authUserService', JSON.stringify(state.authUserService))
        },
        redirectOnLogin(state, payload)
        {
            window.localStorage.setItem('redirOnLogin', payload)
            state.redirectOnLogin = payload
        },
        setService(state, payload)
        {
            state.authUserService = payload
            window.localStorage.setItem('authUserService', JSON.stringify(state.authUserService))
        },
        serviceImgExpected(state, payload)
        {
            state.serviceImgExpected = true
        },
        serviceImageAdded(state, payload)
        {
            state.authUser.service = payload
            state.authUserService = payload
            window.localStorage.setItem('authUserService', JSON.stringify(state.authUserService))
            state.serviceImageAdded = true
        },
        maxPortfolio(state)
        {
            state.maxPortfolio = true
        },
        portfolioCreated(state)
        {
            state.portfolioCreated
        },
        updateService(state, payload)
        {
            state.authUser.service = payload
            state.authUserService = payload
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            window.localStorage.setItem('authUserService', JSON.stringify(payload))
            state.serviceUpdated = true
        },
        resetFlashMsg(state)
        {
            state.serviceUpdated = false
            state.serviceImageAdded = false
            state.maxPortfolio = false
            state.portfolioCreated = false
            state.portfolioUpdated = false
        },
        resetAdminFlashMsg(state)
        {
            state.AdminUpdatedSUProfilePic = false
            state.adminUpdatedSuperUser = false
            state.adminUpdatedUser = false
            state.adminDeletedUser = false
        },
        portfolioUpdated(state)
        {
            state.portfolioUpdated = true
        },
        resetAuthUser(state, payload)
        {
            let oldUser = JSON.parse(window.localStorage.getItem('authUser'))
            let token = oldUser.token
            let newUser = payload
            state.authUser = Object.assign({}, newUser, {token:token})
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            state.authUserUpdated = true
        },
        removeUserMeta(state, payload)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('userIsLoggedIn')
            localStorage.removeItem('authUserservice')
            state.userIsLoggedIn = false
            state.authUser = null
            state.authUserservice = null
        },
        logOutAuthUser(state)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('userIsLoggedIn')
            localStorage.removeItem('authUserService')
            state.userIsLoggedIn = false
            state.authUser = null
            state.authUserService = null
        },
        adminLoginSuccess(state, payload)
        {
            state.adminIsLoggedIn = true
            state.authAdmin = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authAdmin', JSON.stringify(state.authAdmin))
            window.localStorage.setItem('adminIsLoggedIn', true)
        },
        logOutAuthUser(state)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('userIsLoggedIn')
            state.userIsLoggedIn = false
            state.authUser = null
        },
        logOutAdmin(state)
        {
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.adminIsLoggedIn = false
            state.authAdmin = null
        },
        AdminUpdatedSUProfilePic(state)
        {
            state.AdminUpdatedSUProfilePic = true
        },
        adminUpdatedSuperUser(state)
        {
            state.adminUpdatedSuperUser = true
        },
        adminUpdatedUser(state)
        {
            state.adminUpdatedUser = true
        },
        adminDeletedUser(state)
        {
            state.adminDeletedUser = true
        },
        adminDeletedPf(state){
            state.adminDeletedPf = true
        },
        adminUpdatedPf(state){
            state.adminUpdatedPf = true
        },
        adminDeletedPf(state)
        {
            state.adminDeletedPf = true
        }
    }
})
