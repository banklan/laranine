<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12">
                <auth-pageheader title="Profile" />
            </v-col>
        </v-row>
        <v-row justify="center" class="acc_pg">
            <v-col cols="12" md="5">
                <div class="profile_card">
                    <div class="upper">
                        <v-img :src="authUserService.image"></v-img>
                    </div>
                    <div class="picture">
                        <v-img v-if="authUser.picture" :src="authUser.picture" alt="profile picture"></v-img>
                        <img v-else src="/assets/profile/pic1.jpg" alt="profile picture">
                    </div>
                    <div class="lower">
                        <div class="mover">{{ authUser.fullname }}</div>
                        <div class="email"><i class="uil uil-envelope-alt"></i><span class="">{{ authUser.email }}</span></div>
                        <div class="phone"><i class="uil uil-phone-alt"></i> <span class="">{{ authUser.phone }}</span></div>
                    </div>
                    <div class="text-center edit_btn">
                        <v-btn icon color="icon_color" :to="{name: 'UpdateAuthUser'}"><i class="uil uil-edit"></i></v-btn>
                    </div>
                </div>
            </v-col>
            <v-col cols="12" md="5" offset-md="1">
                <div class="acct_params">
                    <v-card light elevation="3" min-height="200" class="mx-auto">
                        <v-card-title class="justify-center primary white--text subtitle-1">Service</v-card-title>
                        <v-card-text class="mt-5">
                            <template v-if="authUserService">
                                <table class="table table-condensed">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%" style="border-top: none">Title:</th>
                                            <td style="border-top: none"><router-link :to="{name: 'MyService'}">{{ authUserService.title }}</router-link></td>
                                        </tr>
                                        <tr>
                                            <th width="20%" style="border-top: none">Service ID:</th>
                                            <td style="border-top: none">{{ authUserService.service_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Published:</th>
                                            <td>{{ authUserService.created_at | moment('Do MMMM, YYYY') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated: </th>
                                            <td>{{ authUser.updated_at | moment('Do MMMM, YYYY - H:mma') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td :class="authUserService.is_active ? 'green-text' : 'warning_text--text'">{{ authUserService.is_active ? 'Active' : 'Not Active' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Service Plan:</th>
                                            <td>{{ authUserService.is_premium ? 'Premium' : 'Regular' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Views:</th>
                                            <td>{{ authUserService.views }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ratings:</th>
                                            <td>{{ authUserService.ratings }}</td>
                                        </tr>
                                        <tr>
                                            <th>Reviews:</th>
                                            <td>250</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                            <template v-else>
                                <v-alert type="info" class="mt-5">
                                    You have not created a service for your account.
                                </v-alert>
                            </template>
                        </v-card-text>
                        <v-card-actions class="justify-center mt-n2 pb-6" v-if="authUserService">
                            <v-btn icon color="icon_color" :to="{name: 'EditMyService'}"><i class="uil uil-edit"></i></v-btn>
                        </v-card-actions>
                    </v-card>
                    <profile-password-card />
                     <v-card light elevation="3" min-height="100" class="mx-auto mt-5">
                        <v-card-title class="justify-center primary white--text subtitle-1">Delete Account</v-card-title>
                        <v-card-text class="mt-5 delete_account">
                            <v-alert border="left" type="error" close-icon="cancel" class="mt-5 pb-6">
                                Confirming and clicking on the Delete Account button deletes your account irreversibly. All your personal, service and portfolio details and media will be deleted from our servers.
                            </v-alert>
                            <div class="mt-8">
                                <v-text-field :label="`Type ${authUserService.service_id} and then click the delete button`" v-model="confirmDel.serv_id" :placeholder="`Type ${authUserService.service_id} and click the Delete account button`"></v-text-field>
                            </div>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-6 mt-n4">
                            <v-btn large color="red darken-1" dark :disabled="authUserService.service_id != confirmDel.serv_id" @click="confirmAccountDelDial = true">Delete Account</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmAccountDelDial" max-width="450">
            <v-card min-height="100">
                <v-card-title class="subtitle-1 white--text primary justify-center">Are you sure you want to delete your Account?</v-card-title>
                <v-card-text>
                    Confirming this delete means all your details will be deleted from our servers and you will not be to recover it.
                </v-card-text>
                <v-card-actions class="pb-8 mt-8 justify-center">
                    <v-btn text width="40%" color="red darken--2" @click="confirmAccountDelDial = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary" :loading="isDeleting" @click="deleteAccount">Yes, Delete Account</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="authUserUpdated" :timeout="4000" top color="green darken-1 white--text">
            Your profile details have been updated successfully.
            <v-btn text color="white--text" @click="authUserUpdated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            confirmDel:{
                serv_id: ''
            },
            isDeleting: false,
            isLoading: false,
            confirmAccountDelDial: false

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
        authUserUpdated(){
            return this.$store.getters.authUserUpdated
        }
    },
    methods: {
        deleteAccount(){
            this.isDeleting = true
            axios.post(this.api + '/auth/delete_user_account', {}, this.authHeaders)
            .then((res) => {
                this.isDeleting = false
                this.$store.commit('removeUserMeta')
                this.$router.go('/')
            })
        }
    },
}
</script>


<style lang="scss" scoped>
    .acc_pg{
        margin-top: .7rem;
        margin-bottom: 5rem;
        .profile_card{
            margin: 0 auto;
            background: #fff;
            color: #2e2e2e;
            border: 1px solid #d3d3d3;
            border-radius: 6px;
            box-shadow: 0 2px 2px rgba(0,0,0,.13);
            width: 100%;
            min-height: 20rem;
            box-sizing: border-box;
            cursor:pointer;
            transition: all .4s ease;
            position: relative !important;

            .upper{
                height: 8rem;
                .v-image{
                    height: 100%;
                    width: 100%;
                }
            }
            .lower{
                width: 100%;
                height: 100%;
                margin: 4.5rem auto 1rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center !important;
                gap: .5rem;

                .mover{
                    color: #1c1c1c;
                    font-size: 1rem;
                    padding-left: 5px;
                }
                .email, .phone{
                    font-size: .9rem;
                    i{
                        color: #99154E;
                        font-size: .9rem;
                    }
                }
            }
            .edit_btn{
                margin: 1rem auto;
                text-align: center;
                i{
                    font-size: 1rem;
                }
            }
            .picture{
                margin: 0 auto;
                width: 8rem;
                height: 8rem;
                position: absolute !important;
                top: 4rem;
                left: 35%;
                overflow: hidden;
                img, .v-image{
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    border: 1px solid #d3d3d3;
                    z-index: 1;
                }
            }
        }
        .acct_params{
            margin: 0 auto;
        }
    }
    table{
        tbody{
            tr{
                th{
                    white-space: nowrap !important;
                }
                td{
                    a{
                        color: #03286d;
                        text-decoration: none;
                    }
                }
            }
        }
    }
    .v-card .delete_account{
        .v-text-field__slot input[type="text"]{
            &::placeholder{
                color: red !important;
            }
        }
    }
</style>
