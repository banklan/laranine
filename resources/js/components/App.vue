<template>
    <v-app>
        <template v-if="$route.path == '/'">
            <WelcomeNavbar />
            <sec-nav></sec-nav>
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <router-view></router-view>
            </transition>
        </template>
        <template v-else>
            <template v-if="adminIsLoggedIn">
                <template v-if="$route.path.substring(0, 6) == '/admin'">
                    <AdminAuthNavbar />
                </template>
                <template v-else>
                    <Navbar />
                    <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                        <router-view></router-view>
                    </transition>
                </template>
            </template>
            <template v-if="userIsLoggedIn">
                <template v-if="$route.path.substring(0, 5) == '/user'">
                    <UserAuthNavbar />
                    <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                        <router-view></router-view>
                    </transition>
                </template>
                <template v-else>
                    <Navbar />
                    <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                        <router-view></router-view>
                    </transition>
                </template>
            </template>
            <template v-if="!adminIsLoggedIn && !userIsLoggedIn">
                <Navbar />
                <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                    <router-view></router-view>
                </transition>
            </template>
        </template>
    </v-app>
</template>

<script>
import Navbar from './includes/Navbar'
import WelcomeNavbar from './includes/WelcomeNavbar'
import AdminAuthNavbar from './includes/AdminAuthNavbar'
import UserAuthNavbar from './includes/UserAuthNavbar'

export default {
    components: {
        Navbar, WelcomeNavbar, AdminAuthNavbar, UserAuthNavbar
    },
    computed:{
        pgScrollPosition(){
            return this.$store.getters.pgScrollPosition
        },
        adminIsLoggedIn(){
            return this.$store.getters.adminIsLoggedIn
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
    },
    methods: {
        handleScroll(){
            if(this.$route.path == '/'){
                this.$store.commit('checkPgScrollHeight')
            }
        }
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll)
    },
    destroyed() {
        window.removeEventListener('scroll')
    },
}
</script>
