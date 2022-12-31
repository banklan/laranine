<template>
    <v-container>
        <v-row>
            <v-col cols="6" md="3">
                <v-btn rounded color="primary" dark elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <v-skeleton-loader type="table" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else elevation="6" raised light class="mx-auto" min-height="300">
                    <v-card-title class="justify-center primary white--text subtitle-1">Superuser (Admin) Update</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="user">
                            <v-text-field label="First Name" v-model="user.first_name" required v-validate="'required|min:2|max:30'" :error-messages="errors.collect('first_name')" name="first_name" data-vv-as="first name"></v-text-field>
                            <v-text-field label="Last Name" v-model="user.last_name" required v-validate="'required|min:2|max:30'" :error-messages="errors.collect('last_name')" name="last_name" data-vv-as="last name"></v-text-field>
                            <v-text-field label="Phone Number" v-model="user.phone" required v-validate="'required|numeric|max:16'" :error-messages="errors.collect('phone')" name="phone" data-vv-as="phone"></v-text-field>
                        </template>
                        <template v-else>
                            <v-alert type="info" class="mt-5">
                                The admin user you are trying to view is invalid.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="pb-8 justify-center">
                        <v-btn large color="primary" width="60%" :loading="isBusy" @click="updateAdmin">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="AdminUpdatedSUProfilePic" :timeout="4000" top color="green darken-1 white--text">
            Super user profile picture updated!
            <v-btn text color="white--text" @click="AdminUpdatedSUProfilePic = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="4000" top color="red darken-1 white--text">
            There was an error while trying to update the super-user. Please try again later!
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            user: null,
            isBusy: false,
            updateFailed: false,
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
        }
    },
    methods: {
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_superuser/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.user = res.data
            })
        },
        updateAdmin(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isBusy = true
                    axios.post(this.api + `/auth-admin/update_superuser/${this.$route.params.id}`, {
                        user: this.user
                    }, this.adminHeaders)
                    .then((res) => {
                        this.isBusy = false
                        this.$store.commit('adminUpdatedSuperUser')
                        this.$router.push({name: 'AdminSuperUserDetail', params:{id: this.$route.params.id}})
                    }).catch(()=>{
                        this.isBusy = false
                        this.updateFailed = true
                    })
                }
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
