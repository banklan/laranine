<template>
    <span>
        <v-app-bar flat fixed dark class="primary white--text mb-10">
            <span class="hidden-md-and-up">
                <v-app-bar-nav-icon class="white--text hidden-md-and-up" @click="navdrawer = true"></v-app-bar-nav-icon>
            </span>
            <v-toolbar-title class="ml-5 white--text">
                <router-link to="/" style="cursor:pointer" exact>
                    <span class="white--text">
                        HozuMart
                    </span>
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <span class="white--text mt-1 mr-5"> {{ authUser.fullname }}</span>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down align-center">
                <template>
                    <v-btn class="white--text" text v-for="menu in authMenu" :key="menu.title">
                        <router-link class="white--text" :to="menu.path">{{ menu.title }}</router-link>
                    </v-btn>
                    <v-btn color="white--text" text @click="logout" class="logout" style="text-transform: lower">Logout</v-btn>
                </template>
            </v-toolbar-items>
        </v-app-bar>
        <v-navigation-drawer absolute hide-overlay v-model="navdrawer" color="primary white--text" class="hidden-md-and-up mt-6" disable-resize-watcher>
            <v-toolbar-title class="white--text headline font-weight-bold ml-3 mt-5">
                <!-- <router-link to="/" style="cursor: pointer" exact>
                    <span class="white--text">
                        HozuMart
                    </span>
                </router-link> -->
            </v-toolbar-title>
            <v-divider></v-divider>
            <v-list dark class="ml-3">
                <template>
                    <v-list-item dark color="white--text" v-for="menu in authMenu" :key="menu.title" link :to="menu.path">
                        <v-list-item-content>
                            <v-list-item-title>{{ menu.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item dark class="white--text"  @click="logout">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>
    </span>
</template>

<script>
export default {
    data(){
        return{
            navdrawer: false,
            authMenu: [
                { title: "DashBoard", path: "/user/dashboard"},
                { title: "My Service", path: "/user/my-service"},
                // { title: "My Profile", path: "/user/profile"},
                { title: "Profile", path: "/user/profile"},
            ],
        }
    },
    computed: {
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        api(){
            return this.$store.getters.api
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
    },
    methods: {
        logout(){
            if(this.userIsLoggedIn){
                axios.post(this.api + `/auth/logout`, {}, this.authHeaders).then(() =>{
                    this.$store.commit('logOutAuthUser')
                    this.$router.push('/')
                })
            }
        },
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

    .v-list-item__title{
        transition: all 400ms ease;
    }

    .v-list a.v-list-item:hover .v-list-item__title{
        margin-left: 3px !important;
    }
    .v-toolbar__items{
        .v-btn.logout{
            text-transform: capitalize;
        }
    }
</style>
