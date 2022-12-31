<template>
    <v-container>
        <v-row justify="center" class="">
            <v-col cols="12" md="6">
                <admin-search model="User" searchFor="users"/>
            </v-col>
        </v-row>
        <v-row class="" justify="center" >
            <v-col cols="12" md="10">
                <v-skeleton-loader type="table" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else min-height="200" light raised elevation="6">
                    <v-card-title class="primary white--text subtitle-1 justify-center">Users <span v-if="users.length" class="ml-2"><v-chip color="primary lighten-2 white--text">{{ users.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <table class="table table-hover table-condensed" v-if="users.length > 0">
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
                    <v-card-actions class="pb-8 justify-center" v-if="users.length > 0 ">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getUsers(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getUsers(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getUsers(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getUsers(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDialog" max-width="450">
            <v-card min-height="120">
                <v-card-title class="subtitle-1 white--text primary justify-center">Are you sure you want to delete this user?</v-card-title>
                <v-card-text class="mt-5 subtitle-1">Once deleted, the process cannot be undone.</v-card-text>
                <v-card-actions class="pb-8 mt-8 justify-center">
                    <v-btn text width="40%" color="red darken--2" @click="confirmDelDialog = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary" :loading="isDeleting" @click="deleteUser">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminDeletedUser" :timeout="4000" top dark color="green darken-2">
            A user profile has been deleted successfully.
            <v-btn text color="white--text" @click="adminDeletedUser = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            isLoading: false,
            users: [],
            pagination: {},
            total: null,
            confirmDelDialog: false,
            userToDel: null,
            userIndex: null,
            isDeleting: false,
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
        adminDeletedUser(){
            return this.$store.getters.adminDeletedUser
        }
    },
    methods: {
        getUsers(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/all_users`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.users = res.data.data
                this.total = res.data.total
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        showUser(user){
            this.$router.push({name:'AdminUserDetail', params:{id: user}})
        },
        confirmDel(user, i){
            this.confirmDelDialog = true
            this.userToDel = user
            this.userIndex = i
        },
        deleteUser(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_user/${this.userToDel}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDialog = false
                this.$store.commit('adminDeletedUser')
                this.users.splice(this.userIndex, 1)
            })
        }
    },
    mounted() {
        this.getUsers()
    },
}
</script>


<style lang="css" scoped>
    table .list tr th, table .list tr td{
        cursor: pointer;
        height: 1rem !important;
        font-size: .9rem !important;
        line-height: 1.2 !important;
    }
</style>
