<template>
    <v-container>
        <v-row justify="center" class="">
            <v-col cols="12" md="6">
                <admin-search model="Admin" searchFor="super-users"/>
            </v-col>
        </v-row>
        <v-row class="" justify="center" >
            <v-col cols="12" md="10">
                <v-skeleton-loader type="table" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else min-height="200" light raised elevation="6">
                    <v-card-title class="primary white--text subtitle-1 justify-center">Admins List <span v-if="users.length" class="ml-2"><v-chip color="primary lighten-2 white--text">{{ users.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <table class="table table-hover table-striped table-condensed" v-if="users.length > 0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Fullname</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <tr v-for="(user, i) in users" :key="user.id">
                                    <template>
                                        <td @click="showUser(user.id)">{{ user.id }}</td>
                                        <td @click="showUser(user.id)">{{ user.fullname }}</td>
                                        <td @click="showUser(user.id)">{{ user.email }}</td>
                                        <td>{{ user.status ? 'Active' : 'Inactive'}} </td>
                                        <td><v-btn icon color="primary_variant" :to="{name: 'AdminUpdateUser', params:{id: user.id}}"><i class="uil uil-edit"></i></v-btn> &nbsp; <v-btn icon color="red darken-2" @click="confirmDel(user.id, i)"><i class="uil uil-trash-alt"></i></v-btn></td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                        <v-alert class="mt-5" type="info" border="left" v-else>
                            There are no registered users(staff) at the moment.
                        </v-alert>
                    </v-card-text>

                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            isLoading: false,
            users: []
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
        getUsers(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/all_superusers', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.users = res.data
                console.log(res.data)
                console.log(res.data)
                console.log(res.data)
            })
        },
        showUser(user_id){
            this.$router.push({name: 'AdminSuperUserDetail', params:{id: user_id}})
        }
    },
    mounted() {
        this.getUsers()
    },
}
</script>


<style lang="scss" scoped>
    table tbody tr{
        cursor: pointer;
    }
    table tbody tr th,table tbody tr td {
        white-space: nowrap !important;
    }
</style>
