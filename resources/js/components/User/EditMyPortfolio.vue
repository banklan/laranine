<template>
    <v-container>
        <v-row justify="center" class="form_row">
            <v-col cols="12" md="2">
                <v-btn color="primary" text elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
            <v-col cols="12" md="8" >
                <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else light elevation="6" min-height="300" class="mx-auto">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Update Portfolio</v-card-title>
                    <v-card-text class="mt-8" v-if="portfolio">
                        <v-textarea label="Title" rows="1" v-model="portfolio.title" :counter="200" auto-grow required v-validate="'required|min:5|max:200'" :error-messages="errors.collect('title')" name="title"></v-textarea>
                        <v-textarea label="Details" rows="2" v-model="portfolio.detail" :counter="600" auto-grow required v-validate="'required|min:10|max:600'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                        <v-text-field label="Price(NGN)" v-model="portfolio.cost" v-validate="'decimal'" :error-messages="errors.collect('cost')" name="cost" hint="Cost must not be blank and must be a digit greater than 0"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6">
                        <v-btn large dark color="primary" :loading="isBusy" @click="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            id: this.$route.params.id,
            isLoading: false,
            portfolio: null,
            isBusy: false,
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
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
        getPortfolio(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_my_portfolio/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.portfolio = res.data
                this.portfolio.cost = res.data.cost / 100
            })
        },
        submit(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isBusy = true
                    axios.post(this.api + `/auth/update_portfolio/${this.$route.params.id}`, {
                        portfolio: this.portfolio
                    }, this.authHeaders).then((res) => {
                        this.isBusy = false
                        this.$store.dispatch('resetAuthService')
                        this.$store.commit('portfolioUpdated')
                        this.$router.push({name: 'MyPortfolioDetail', params: {id: res.data.id}})
                    })
                }
            })
        }
    },
    mounted() {
        this.getPortfolio()
    },
}
</script>


<style lang="scss" scoped>
    .form_row{
        margin-top: 6rem !important;
    }

</style>
