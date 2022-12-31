<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12">
                <auth-pageheader :title="`Welcome ${authUser.first_name}`" />
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="5">
                <v-card raised elevation="8" light min-height="100" class="mx-auto">
                    <v-card-title class="justify-center primary white--text subtitle-1">Account</v-card-title>
                    <v-card-text class="mt-5">
                        <table class="table table-condensed">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th style="border-top: none" width="25%">Name</th>
                                    <td style="border-top: none">{{ authUser.fullname }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ authUser.email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone No:</th>
                                    <td>{{ authUser.phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                    <v-card-actions class="pb-6 justify-center">
                        <v-btn text color="primary" :to="{name: 'UserAccount'}">Go To Profile</v-btn>
                        <v-btn text color="icon_color"><i class="uil uil-edit"></i></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="5" offset-md="1">
                <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="50"></v-skeleton-loader>
                <template v-else>
                    <v-card raised elevation="8" light min-height="100" class="mx-auto">
                        <v-card-title class="justify-center primary white--text subtitle-1">Features</v-card-title>
                        <v-card-text class="mt-5">
                            <table class="table" v-if="authUserService">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th style="border-top:none" width="20%">Title:</th>
                                        <td style="border-top:none">{{ authUserService.title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>{{ authUserService.category && authUserService.category.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ratings:</th>
                                        <td class="d-flex"><v-rating class="" dense color="primary" small></v-rating> <span class="ml-3"><router-link :to="{name: 'MyServiceReviews'}">See Reviews</router-link></span></td>
                                    </tr>
                                    <tr>
                                        <th>Views:</th>
                                        <td class="d-flex">550</td>
                                    </tr>
                                    <tr>
                                        <th>Account Type:</th>
                                        <td style="border-top:none">{{ authUserService.is_premium ? 'Premium' : 'Regular' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Service Status:</th>
                                        <td>{{ authUserService.is_active ? 'Active' : 'Inactive'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated Last:</th>
                                        <td>{{ authUser.updated_at | moment('Do MMMM, YYYY') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-else>
                                <v-alert type="info" class="mt-5">
                                    You have not created any service for your account.

                                </v-alert>
                                <div class="create_service text-center">
                                    <v-btn text color="primary" :to="{name: 'CreateService'}">Create Service</v-btn>
                                </div>
                            </template>
                        </v-card-text>
                        <v-card-actions class="pb-6 justify-center" v-if="authUserService">
                            <v-btn text color="primary" :to="{name: 'MyService'}">Go To Service</v-btn>
                        </v-card-actions>
                    </v-card>
                    <v-card raised elevation="8" light min-height="100" class="mx-auto mt-5">
                        <v-card-title class="justify-center primary white--text subtitle-1">Params</v-card-title>
                        <v-card-text class="mt-5">
                            <table class="table">
                                <thead></thead>
                                <tbody>
                                    <template v-if="authUserService">
                                        <tr>
                                            <th style="border-top:none" width="30%">Account Type:</th>
                                            <td style="border-top:none">{{ authUserService.is_premium ? 'Premium' : 'Regular' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Service Status:</th>
                                            <td>{{ authUserService.is_active ? 'Active' : 'Inactive'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated Last:</th>
                                            <td>{{ authUser.updated_at | moment('Do MMMM, YYYY') }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </v-card-text>
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
            // service: null
        }
    },
    computed: {
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        authUserService(){
            return this.$store.getters.authUserService
        }
    },
    methods: {
    },
    mounted() {
    },
}

</script>
<style lang="scss" scoped>

.panel_title{
    text-align: left;
    font-size: 1.1rem;
    color: #2e2e2e;
    padding: .5rem .8rem 0.5rem;
    border-bottom: 1px solid #dadada;
}
.panel_body{
    margin-top: .5rem;

    .no_service{
        margin: .8rem .6rem 1.2rem;
    }
    .set_up{
        text-align: center;
    }
}
.panel_action{
    margin: 1rem auto 1.5rem;
    text-align: center;

    .v-btn i{
        font-size: .8rem;
        color: #10527e;
    }
}
a{
    text-decoration: none !important;
}
.table tbody tr td{
    vertical-align: middle !important;
    white-space: nowrap !important;
}
table tbody tr th{
    white-space: nowrap !important;
}
</style>
