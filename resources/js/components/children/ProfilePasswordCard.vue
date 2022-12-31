<template>
    <div class="profile_password mt-5">
        <v-card light raised outlined elevation="4" min-height="150">
            <v-card-title class="justify-center subtitle-1 primary white--text">
                Manage Account Password
            </v-card-title>
            <v-card-text class="mt-5">
                <v-btn text color="primary" @click="openChangePswd = !openChangePswd"><v-icon left>lock</v-icon>Change Password</v-btn>
                <template v-if="openChangePswd">
                    <v-text-field label="Current Password" type="password" v-model="password.current_pswd" @blur="checkCurrentPswd" v-validate="'required'" :error-messages="errors.collect('pswd.current_pswd')" name="current_pswd" data-vv-scope="pswd" data-vv-as="current password"></v-text-field>
                    <div class="red--text darken-2 mb-3 font-italic subtitle-2" v-if="pswdChngErr">{{ oldPswdError }}</div>
                    <v-text-field label="New Password" type="password" v-model="password.new_pswd" ref="new_pswd" v-validate="'required|min:5|max:20'" :error-messages="errors.collect('pswd.new_pswd')" name="new_pswd" data-vv-scope="pswd" data-vv-as="new password"></v-text-field>
                    <div class="red--text darken-2 mt-n3 mb-3 font-italic subtitle-2" v-if="newPswdChngErr">{{ newPswdErr }}</div>
                    <v-text-field label="Confirm New Password" type="password" v-model="password.confirm" v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('pswd.confirm')" name="confirm" data-vv-scope="pswd" data-vv-as="confirm password"></v-text-field>
                    <v-card-actions class="justify-center pb-8 mt-3">
                        <v-btn color="red darken-2" large width="40%" text @click="clearPswdFields">Clear</v-btn>
                        <v-btn v-if="!pswdChngErr" dark color="primary" large width="40%" :loading="isUpdatingPswd" @click="updatePswd">Update</v-btn>
                    </v-card-actions>
                </template>
            </v-card-text>
        </v-card>
         <v-snackbar v-model="passwordChanged" :timeout="4000" top color="green darken-1 white--text">
            Your profile password was updated successfully.
            <v-btn text color="white--text" @click="passwordChanged = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordChangeFailed" :timeout="8000" top color="red darken-1 white--text">
            There was an error while trying to update your profile password. Please ensure you choose a new password between 5 and 30 characters and you have mobile network before trying again.
            <v-btn text color="white--text" @click="passwordChangeFailed = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            openChangePswd: false,
            password: {
                current_pswd: '',
                new_pswd: '',
                confirm: ''
            },
            pswdChngErr: false,
            oldPswdError: null,
            newPswdChngErr: false,
            newPswdErr: null,
            isUpdatingPswd: false,
            passwordChanged: false,
            passwordChangeFailed: false,
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        authUserService(){
            return this.$store.getters.authUserService
        },
        authHeaders(){
            let conf = {
                headers: {
                    Authorization: `Bearer ${this.authUser.token}`,
                },
            }
            return conf
        },
    },
    methods: {
        checkCurrentPswd(){
            if(this.password.current_pswd !== ''){
                this.pswdChngErr = false
                axios.post(this.api + '/auth/confirm_current_pswd', {
                    password: this.password.current_pswd
                }, this.authHeaders).then((res) => {
                    if(res.data == false){
                        this.pswdChngErr = true
                        this.oldPswdError = 'Invalid password.'
                    }
                })
            }
        },
        updatePswd(){
            this.$validator.validateAll('pswd').then((isValid) => {
                if (isValid) {
                    if(this.pswdChngErr === false){
                        if(this.password.current_pswd !== this.password.new_pswd){
                            this.isUpdatingPswd = true
                            axios.post(this.api + '/auth/update_user_profile_password', {
                                password: this.password.new_pswd.trim(),
                                password_confirmation: this.password.confirm.trim()
                            }, this.authHeaders)
                            .then((res) => {
                                this.isUpdatingPswd = false
                                this.passwordChanged = true
                                this.openChangePswd = false
                                this.clearPswdFields()
                                this.newPswdChngErr = false
                                this.newPswdErr = ''
                            }).catch(()=>{
                                this.isUpdatingPswd = false
                                this.passwordChangeFailed = true
                            })
                        }else{
                            this.newPswdChngErr = true
                            this.newPswdErr = 'Invalid action. New password cannot be the same as the one you are trying to change.'
                        }
                    }else{
                        this.currentPswdError = true
                    }
                }
            })
        },
        clearPswdFields(){
            this.pswdChngErr = false
            this.password.current_pswd = ''
            this.password.new_pswd = ''
            this.password.confirm = ''
            this.$validator.pause()
            this.$validator.fields.items.forEach(field => field.reset())
            this.$validator.errors.clear()
        },
    }
}
</script>
