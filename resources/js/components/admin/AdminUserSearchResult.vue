<template>
    <v-container>
        <div class="upper_row">
            <div class="back">
                <v-btn rounded color="primary" dark elevation="4" left :to="{name: 'AdminUsersList'}"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </div>
            <div class="search">
                <div class="search_field">
                    <admin-search model="User" searchFor="users"/>
                </div>
            </div>
        </div>
        <v-row class="" justify="center">
            <v-col cols="12" md="10">
                <v-skeleton-loader type="card" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <template v-else>
                    <div class="grey_text--text text-center mb-5">
                        Search for <strong><em>{{ $route.query.q }}</em></strong> returned {{ users.length }} listing.
                    </div>
                    <v-card light raised elevation="8" min-height="100" class="scroll">
                        <template v-if="users.length > 0">
                            <v-card-title class="subtitle-1 primary white--text justify-center"> Users Listing <v-chip color="primary lighten-2" class="ml-1" v-if="users.length > 0">{{ users.length }}</v-chip></v-card-title>
                            <v-card-text class="mt-5">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Fullname</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <tr v-for="(user) in users" :key="user.id">
                                            <td @click="showUser(user)">{{ user.id }}</td>
                                            <td @click="showUser(user)">{{ user.fullname }}</td>
                                            <td @click="showUser(user)">{{ user.email }}</td>
                                            <td>{{ user.status ? 'Active' : 'Inactive'}} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                        </template>
                        <template v-else>
                            <v-card-text>
                                <v-alert type="error" border="left" class="mt-8 mx-3">
                                    Search for {{ $route.query.q }} returned no listing.
                                </v-alert>
                            </v-card-text>
                        </template>
                    </v-card>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            users: [],
        }
    },
    watch: {
        '$route.query.q':{
            handler(newVal){
                this.users = newVal
                this.getResult()
            },
            immediate: true
        }
    },
    computed:{
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getResult(){
            this.isLoading = true
            axios.post(this.api + '/auth-admin/search_for_users', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.users = res.data
            }).catch(()=>{
                this.isLoading = false
            })
        },
       showUser(user){
            this.$router.push({name: 'AdminUserDetail', params:{id: user.id}})
        }
    },
    created() {
        this.getResult()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
</style>
