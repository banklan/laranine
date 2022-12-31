<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-3">
            <v-col cols="12">
                <v-btn rounded color="primary" dark elevation="4" left :to="{name: 'AdminUsersList'}"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="space-around" class="mt-5" :class="$vuetify.breakpoint.smAndDown ? 'ml-n3 mr-n5': ''">
            <v-col cols="12" md="5">
                <v-skeleton-loader type="card" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else light raised outlined elevation="4" min-height="400" class="scroll">
                    <template v-if="user">
                        <v-img v-if="user.picture" :src="user.picture" height="100%" transition="scale-transition"></v-img>
                        <v-card-text>
                            <div class="subtitle-2 my-3 text-center">User Profile</div>
                            <v-simple-table light>
                                <template v-slot:default>
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="30%">First Name:</th>
                                            <td>{{ user.first_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Name:</th>
                                            <td>{{ user.last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>User ID:</th>
                                            <td>{{ user.id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ user.email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>{{ user.status ? 'Active' : 'Inactive' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone No:</th>
                                            <td>{{ user.phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ user.created_at | moment('DD/MM/YYYY') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated:</th>
                                            <td>{{ user.updated_at | moment('DD/MM/YYYY - H:mma') }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions class="justify-space-around pb-5" v-if="user">
                            <v-btn icon dark text color="primary" :to="{name: 'AdminUpdateUser', params: {id: user.id}}"><i class="uil uil-edit"></i></v-btn>
                            <v-btn dark text color="primary darken-2" @click="statusDialog = true">{{ user.status == 1 ? 'De-activate' : 'Activate' }}</v-btn>
                            <v-btn icon large color="red darken-2" dark @click="confirmDelDialog = true"><i class="uil uil-trash-alt"></i></v-btn>
                        </v-card-actions>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="150">
                    <v-card-title class="primary white--text justify-center">Reset Password</v-card-title>
                    <v-card-text class="mt-4 text-center">
                        <template>
                            <v-btn text dark color="secondary darken-3" @click="resetPassword = !resetPassword">Reset Password</v-btn>
                            <template v-if="resetPassword">
                                <v-text-field label="New Password" type="password" v-model="password.password" required ref="new_pswd" v-validate="'required|min:5|max:20'" :error-messages="errors.collect('password')"  name="password" data-vv-as="new password"></v-text-field>
                                <v-text-field label="Confirm Password" type="password" v-model="password.password_confirmation" v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('confirm')"  name="confirm" data-vv-as="confirm password"></v-text-field>
                            </template>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="resetPassword">
                        <v-btn width="40%" text dark color="red darken-2" @click="resetPassword = false">Cancel</v-btn>
                        <v-btn width="40%" dark class="primary" :loading="isUpdating" @click="updatePassword">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="statusDialog" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 white--text primary justify-center">{{ user && user.status == 0 ? 'Activate' : 'De-activate'}} this user?</v-card-title>
                <v-card-actions class="pb-8 mt-8 justify-center">
                    <v-btn text width="40%" color="red darken--2" @click="statusDialog = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary" :loading="isToggling" @click="toggleUserStatus">Yes, {{ user && user.status == 0 ? 'Activate' : 'De-activate' }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDialog" max-width="450">
            <v-card min-height="120">
                <v-card-title class="subtitle-1 white--text primary justify-center">Are you sure you want to delete this user profile?</v-card-title>
                <v-card-text class="mt-5 subtitle-1 grey_text--text">Once deleted, the process cannot be undone.</v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text width="40%" color="red darken--2" @click="confirmDelDialog = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary" :loading="isDeleting" @click="deleteUser">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminUpdatedUser" :timeout="4000" top dark color="green darken-1">
            User profile has been successfully updated.
            <v-btn text color="white--text" @click="adminUpdatedUser = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="statusChanged" :timeout="4000" top color="green darken-1 white--text">
            User status was updated successfully.
            <v-btn text color="white--text" @click="statusChanged = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordResetSuccessfull" :timeout="4000" top color="green darken-1 white--text">
            Password was reset successfully.
            <v-btn text color="white--text" @click="passwordResetSuccessfull = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordResetFailed" :timeout="6000" top color="red darken-1 white--text">
            Password reset failed. Please ensure that the new password is between 5 & 20 characters.
            <v-btn text color="white--text" @click="passwordResetFailed = false">Close</v-btn>
        </v-snackbar>
        <!-- <v-snackbar :value="adminUpdatedUser" :timeout="4000" top color="green darken-1 white--text">
            User profile has been updated successfully.
            <v-btn text color="white--text" @click="adminUpdatedUser = false">Close</v-btn>
        </v-snackbar> -->
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            user: null,
            isLoading: false,
            id: this.$route.params.id,
            confirmDelDialog: false,
            isDeleting: false,
            statusDialog: false,
            statusChanged: false,
            resetPassword: false,
            password: {
                password: '',
                password_confirmation: ''
            },
            isUpdating: false,
            passwordResetSuccessfull: false,
            passwordResetFailed: false,
            isToggling: false,
            creator: null,
            confirmAuthDial: false,
            isConfirmingAuth: false,
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminUpdatedUser(){
            return this.$store.getters.adminUpdatedUser
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        // adminUpdatedUser(){
        //     return this.$store.getters.adminUpdatedUser
        // }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminFlashMsg')
        next()
    },
    methods: {
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_user/${this.$route.params.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.user = res.data
            })
        },
        toggleUserStatus(){
            this.isToggling = true
            axios.post(this.api + `/auth-admin/change_user_status/${this.user.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isToggling = false
                this.statusChanged =true
                // this.$store.commit('adminUpdatedUser')
                this.user.status = res.data
                this.statusDialog = false
            })
        },
        updatePassword(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_user_password/${this.id}`, {
                        password: this.password
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.passwordResetSuccessfull = true
                        this.resetPassword = false
                        this.password.password = ''
                        this.password.password_confirmation = ''
                        this.$validator.pause()
                        this.$validator.fields.items.forEach(field => field.reset())
                        this.$validator.errors.clear()
                    }).catch(()=>{
                        this.isUpdating = false
                        this.passwordResetFailed = true
                    })
                }
            })
        },
        deleteUser(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_user/${this.$route.params.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDialog = false
                this.$store.commit('adminDeletedUser')
                this.$route.push({name: 'AdminUsersList'})
            })
        }
    },
    created() {
        this.getUser()
    },
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr th,table tbody tr td {
        white-space: nowrap !important;
    }
    .v-btn i{
        font-size: 1.2rem !important;
    }
</style>

