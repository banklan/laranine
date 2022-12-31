<template>
    <v-container>
        <v-row justify="center" class="mt-8">
            <v-col cols="12" md="6">
                <v-card elevation="3" light min-height="200" class="mx-auto mt-5">
                    <v-card-title class="mt-5 subtitle-1 justify-center primary white--text">Create Portfolio</v-card-title>
                    <v-card-text class="mt-8">
                        <v-textarea label="Title" rows="1" v-model="portfolio.title" :counter="200" auto-grow required v-validate="'required|min:5|max:200'" :error-messages="errors.collect('title')" name="title"></v-textarea>
                        <v-textarea label="Details" rows="2" v-model="portfolio.detail" :counter="600" auto-grow required v-validate="'required|min:10|max:600'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                        <v-text-field label="Price" v-model="portfolio.cost" v-validate="'decimal'" :error-messages="errors.collect('cost')" name="cost" hint="Cost must not be blank and must be a digit greater than 0"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn large color="primary darken--2" @click="createPortfolio" :disabled="isLoading" width="50%">Upload Pictures <span right><i class="uil uil-arrow-right"></i></span></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar :value="createFail" :timeout="6000" top color="red darken-1 white--text">
            Your portfolio was not created. Please try again later.
            <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
import { store } from '../../store'
export default {
    data() {
        return {
            isLoading: false,
            portfolio: {
                title: '',
                detail: '',
                cost: null
            },
            createFail: false
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetFlashMsg')
        next()
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        authService(){
            return this.$store.getters.authUserService
        },
        api(){
            return this.$store.getters.api
        },
        authHeaders(){
            let conf = {
                headers: {
                    Authorization: `Bearer ${this.authUser.token}`,
                },
            }
            return conf
        }
    },
    methods: {
        createPortfolio(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    this.portfolio.cost = this.portfolio.cost
                    axios.post(this.api + `/auth/create_portfolio/${this.authService.id}`, {
                        portfolio: this.portfolio
                    }, this.authHeaders).then((res) => {
                        this.isLoading = false
                        // this.createSuccess = true
                        this.$router.push({name: 'CreatePfUploadPics', params: {id: res.data.id}})
                        // this.$store.dispatch('fetchService')
                        // this.$store.commit('portfolioCreated')
                        // this.$router.push({name: 'MyPortFolioShow', params: {id: res.data.id, slug: res.data.slug}})
                        // redirect
                    }).catch((err) => {
                        this.isLoading = false
                        this.createFail = true
                    })
                }
            })
        }
    },
    beforeRouteEnter (to, from, next) {
        let pfLen = store.getters.authUserService.portfolio && store.getters.authUserService.portfolio.length
        let prem = store.getters.authUserService.is_premium
        if(!prem && pfLen > 3){
            store.commit('maxPortfolio')
            next('/my-portfolio')
        }else{
            next()
        }
    },
}
</script>

<style lang="scss" scoped>
    .v-btn i{
        font-size: 1.3rem;
    }
</style>

