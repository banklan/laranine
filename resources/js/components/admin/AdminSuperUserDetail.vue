<template>
    <v-container>
        <v-row>
            <v-col cols="6" md="3">
                <v-btn rounded color="primary" dark elevation="4" left :to="{name: 'AdminSuperUsersList'}"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="6">
                <v-skeleton-loader type="table" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else elevation="6" raised light class="mx-auto" min-height="300">
                    <v-card-title class="justify-center primary white--text subtitle-1">Superuser (Admin)</v-card-title>
                    <v-card-text class="user_details">
                        <template v-if="user">
                            <v-img v-if="user.picture" :src="user.picture" height="400" transition="scale-transition"></v-img>
                            <v-img v-else src="/assets/shared/no-image.png" height="300" contain transition="scale-transition"></v-img>
                            <div class="text-center py-4">
                                <v-btn icon dark color="icon_color" :to="{name: 'AdminSuperUserUpdateProfPic', params: {id: this.$route.params.id}}"><i class="uil uil-image"></i></v-btn>
                            </div>
                            <div class="justify-center">
                                <table class="table table-borderless">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">Name:</th>
                                            <td>{{ user.fullname }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ user.email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ user.phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>{{ user.status ? 'Active' : 'Inactive' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ user.created_at | moment('Do MMM, YYYY') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ user.updated_at | moment('DD/MM/YYYY - H:mma') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                        <template v-else>
                            <v-alert type="info" class="mt-5">
                                The admin user you are trying to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="pb-6 justify-center">
                        <v-btn color="icon_color" icon :to="{name: 'AdminSuperUserUpdate', params:{id: $route.params.id}}"><i class="uil uil-edit"></i></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="AdminUpdatedSUProfilePic" :timeout="4000" top color="green darken-1 white--text">
            Super user profile picture updated!
            <v-btn text color="white--text" @click="AdminUpdatedSUProfilePic = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedSuperUser" :timeout="4000" top color="green darken-1 white--text">
            Super user profile has been updated!
            <v-btn text color="white--text" @click="adminUpdatedSuperUser = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            user: null,
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
        AdminUpdatedSUProfilePic(){
            return this.$store.getters.AdminUpdatedSUProfilePic
        },
        adminUpdatedSuperUser(){
            return this.$store.getters.adminUpdatedSuperUser
        },
    },
    methods: {
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_superuser/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.user = res.data
            })
        }
    },
    mounted() {
        this.getUser()
    },
}
</script>

<style lang="scss" scoped>
    .user_details{
        padding: 0;
        width: 100%;

        table{
            margin: 0 1rem;
        }
    }
</style>
