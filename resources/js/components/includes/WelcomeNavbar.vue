<template>
    <nav>
        <v-app-bar flat fixed dark class="white--text" :class="pgScrollPosition > 50 ? 'primary' : 'transparent'">
            <span class="hidden-md-and-up">
                <v-app-bar-nav-icon class="white--text hidden-md-and-up" @click="navDrawer = true"></v-app-bar-nav-icon>
            </span>
            <v-toolbar-title class="ml-5 white--text">
                <router-link to="/" style="cursor:pointer" exact>
                    <span class="white--text">
                        HozuMart
                    </span>
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down align-center">
                <template v-if="userIsLoggedIn">
                    <v-btn class="white--text" text v-for="menu in authMenus" :key="menu.title">
                        <router-link class="white--text" :to="menu.path">{{ menu.title }}</router-link>
                    </v-btn>
                    <v-btn class="white--text" text @click="userLogout">Logout</v-btn>
                </template>
                <template v-else>
                    <v-btn class="white--text" text v-for="menu in menus" :key="menu.title">
                        <router-link class="white--text" :to="menu.path">{{ menu.title }}</router-link>
                    </v-btn>
                </template>
            </v-toolbar-items>
        </v-app-bar>
        <v-navigation-drawer absolute hide-overlay v-model="navDrawer" color="primary white--text" class="hidden-md-and-up" disable-resize-watcher>
            <v-toolbar-title class="white--text headline font-weight-bold ml-3 mt-5"></v-toolbar-title>
            <v-divider></v-divider>
            <v-list dark class="mt-7 ml-3">
                <template v-if="userIsLoggedIn">
                    <v-list-item dark class="white--text" v-for="item in authMenus" :key="item.title" link :to="item.path" exact>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item dark class="white--text" @click.prevent="userLogout">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </template>
                <template v-else>
                    <v-list-item dark class="primary--text" v-for="item in menus" :key="item.title" link :to="item.path" exact>
                        <v-list-item-content class="pl-5">
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>
    </nav>
</template>

<script>
export default {
    data(){
        return{
            menus: [
                {title: 'Home', path: '/'},
                {title: "About Us", path: "/about-us"},
                {title: "Login", path: "/login"},
            ],
            authMenus: [
                {title: 'Home', path: '/'},
                {title: 'Staff Home', path: '/user/dashboard'},
            ],
            navDrawer: false,
            bg: ''
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        adminIsLoggedIn(){
            return this.$store.getters.adminIsLoggedIn
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        pgScrollPosition(){
            return this.$store.getters.pgScrollPosition
        }
    },
    methods: {
        adminLogout(){
            if(this.adminIsLoggedIn){
                axios.post(this.api + `/auth-admin/logout`, {}, this.adminHeaders).then(() =>{
                this.$store.commit('logOutAdmin')
                    this.$router.push('/')
                })
            }
        },
        userLogout(){
            if(this.userIsLoggedIn){
                axios.post(this.api + `/auth/logout`, {}, this.authHeaders).then(() =>{
                    this.$store.commit('logOutAuthUser')
                    this.$router.push('/')
                })
            }
        }
    },
    mounted() {

    },
}
</script>

<style lang="scss" scoped>
    a{
        color: #fff !important;
        text-decoration: none;
        text-transform:capitalize;
    }
    a.router-link-exact-active{
        color: #c1e6ff !important;
    }

    // .v-list a.v-list-item:hover .v-list-item__title{
    //     margin-left: 3px !important;
    // }

    // .v-list a.v-list-item    .v-list-item__title{
    //     transition: all 400ms ease;
    // }

    // .v-list-item--active{
    //     background: #c1e6ff !important;
    //     .v-list-item__title{
    //         color: #fff !important;
    //     }
    // }
</style>
