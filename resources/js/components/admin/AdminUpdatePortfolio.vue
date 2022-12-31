<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-3">
            <v-col cols="12">
                <v-btn rounded color="primary" dark elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5" :class="$vuetify.breakpoint.smAndDown ? 'ml-n3 mr-n5': ''">
            <v-col cols="12" md="10">
                <v-skeleton-loader type="card" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else light raised outlined elevation="4" min-height="400" class="scroll">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Update Portfolio</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="portfolio">
                            <v-text-field label="Title" required v-model="portfolio.title"></v-text-field>
                            <v-textarea rows="2" auto-grow label="Detail" required v-model="portfolio.detail"></v-textarea>
                            <v-text-field label="Cost(NGN)" required v-model="portfolio.cost"></v-text-field>
                        </template>
                        <template v-else>
                            <v-alert type="warning" class="mt-5">
                                The portfolio you are trying to update does not exist.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn width="60%" color="primary" dark large :loading="isBusy" @click="updatePf">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="updateFailed" :timeout="5000" top dark color="red darken-1">
            There was an error whie trying to update the portfolio. Please try again later.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            isLoading: false,
            portfolio: null,
            isBusy: false,
            updateFailed: false,
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
    methods: {
        getpf(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_portfolio/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.portfolio = res.data
                this.portfolio.cost = res.data.cost / 100
            })
        },
        updatePf(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/update_pf/${this.$route.params.id}`, {
                portfolio: this.portfolio
            }, this.adminHeaders).then((res) => {
                this.isBusy = false
                this.$store.commit('adminUpdatedPf')
                this.$router.push({name: 'AdminPortfolioDetail', params: {id: res.data.id}})
                // console.log(res.data)
            }).catch((err) => {
                this.isBusy = false
                this.updateFailed = true
                console.log(err)
            })
        }
    },
    mounted() {
        this.getpf()
    },
}
</script>
