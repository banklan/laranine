<template>
<v-container>
    <v-row justify="center" class="mt-5">
        <v-col cols="12">
            <auth-pageheader title="My Portfolio" />
        </v-col>
    </v-row>
    <v-row justify="center">
        <v-col cols="12" md="7">
            <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
            <v-card v-else light min-height="400" elevation="12" raised class="mx-auto">
                <template v-if="portfolio">
                    <v-carousel height="400" v-if="files.length > 0">
                        <v-carousel-item v-for="(file, i) in files" :key="i" :src="file.file" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                    </v-carousel>
                    <v-img v-else height="400" src="/assets/shared/no-image.png"></v-img>
                    <v-card-text class="mt-5">
                        <div class="wrap">
                            <div class="subject pl-3">Title: {{ portfolio.title }}</div>
                        </div>
                        <div class="wrap">
                            <div class="subject pl-3">Cost: &#8358;{{ portfolio.cost | price }}</div>
                        </div>
                        <div class="wrap" @click="showDetail = !showDetail">
                            <div class="subject"><i class="uil uil-plus"></i>Detail</div>
                            <div v-if="showDetail" class="body">{{ portfolio.detail }}</div>
                        </div>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6">
                        <v-btn icon color="icon_color" :to="{name: 'EditMyPortfolio', params:{id: portfolio.id}}"><i class="uil uil-edit"></i></v-btn>
                        <v-btn icon color="icon_color" :to="{name: 'UpdatePortfolioImages', params:{id: portfolio.id}}"><i class="uil uil-images"></i></v-btn>
                    </v-card-actions>
                </template>
                <v-card-text class="mt-5" v-else>
                    <v-alert type="warning" border="left" class="">
                        The portfolio you are trying to view is either invalid or does not exist.
                    </v-alert>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" md="4" offset-md="1">
            <v-card light min-height="100" elevation="12" raised class="mx-auto">
                <v-card-title class="justify-center primary white--text subtitle-1">My Service</v-card-title>
                <router-link :to="{name: 'MyService'}" class="service_card">
                    <v-avatar v-if="authService.image">
                        <v-img :src="authService.image" aspect-ratio="1" contain height="150" alt="Avatar"></v-img>
                    </v-avatar>
                    <v-img v-else src="/assets/shared/no-image.png" height="150"></v-img>
                    <span>{{ authService.title }}</span>
                </router-link>
            </v-card>
            <v-card light min-height="100" elevation="12" raised class="mx-auto mt-5">
                <v-card-title class="justify-center primary white--text subtitle-1">Other Portfolios</v-card-title>
                <v-card-text class="mt-5">
                    <div v-for="(pf, index) in otherPortfolios" :key="pf.id">
                        <div class="item">
                            <router-link :to="{name: 'MyPortfolioDetail', params:{id: pf.id}}">{{ pf.title }}</router-link>
                        </div>
                        <v-divider v-if="index < otherPortfolios.length - 1"></v-divider>
                    </div>
                </v-card-text>
                <v-card-actions class="justify-center pb-6" v-if="authService.portfolio.length < 3">
                    <v-btn text color="primary" class="mt-n3" :to="{name: 'CreatePortfolio'}">Add New Portfolio</v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
    <v-snackbar :value="portfolioCreated" :timeout="4000" top color="green darken-1 white--text">
        Your portfolio has been created.
        <v-btn text color="white--text" @click="portfolioCreated = false">Close</v-btn>
    </v-snackbar>
    <v-snackbar :value="portfolioUpdated" :timeout="4000" top color="green darken-1 white--text">
        Your portfolio has been updated.
        <v-btn text color="white--text" @click="portfolioUpdated = false">Close</v-btn>
    </v-snackbar>
</v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            isLoading: false,
            portfolio: null,
            files: [],
            showDetail: false
        }
    },
    watch:{
        '$route.params':{
            handler(newVal){
                let id = newVal
                this.getPortfolio(id)
                // this.getOtherPortfolios(id)
            },
            immediate: true,
            deep: true
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
        portfolioCreated(){
            return this.$store.getters.portfolioCreated
        },
        portfolioUpdated(){
            return this.$store.getters.portfolioUpdated
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
        },
        otherPortfolios(){
            let $pfs = this.$store.getters.authUserService.portfolio
            let $other_pfs = $pfs.filter(item => item.id != this.$route.params.id)
            return $other_pfs
        }
    },
    methods: {
        getPortfolio(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_my_portfolio/${this.$route.params.id}`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.portfolio = res.data
                this.files = res.data.files
                console.log(res.data)
            }).catch(() => {
                this.isLoading = false
            })
        }
    },
    mounted(){
        this.getPortfolio()
    }
}
</script>

<style lang="scss" scoped>
    .wrap{
        background: #fbfbfb;
        border: 1px solid #d1d1d1;
        box-shadow: 0 2px 2px rgba(0,0,0,.18);
        padding: 8px 6px;
        color: #1a1a1a;
        cursor: pointer;
        margin: 6px auto 6px;
        transition: all .4s ease;

        &:hover{
            box-shadow: 0 3px 3px 1px rgba(0,0,0,.25);
        }

        .subject{
            font-weight: 500;
            i{
                color: #99154E;
                font-size: 16px;
            }
        }
        .body{
            margin-top: 5px;
            padding: 5px 8px;
            transition: all .4s ease;
        }
    }
    .portfolio{
        margin-top: 1.2rem;
        padding: 0 .6rem;
        // border-bottom: 1px solid red;

        .item a{
            font-size: 1rem;
        }
    }
    .v-card a{
        text-decoration: none !important;
    }
    .service_card{
        display: flex;
        align-items: center;
        padding: 1.4rem 1rem;
        gap: .8rem;

        .v-avatar{
            border: 1px solid #b3738f;
            width: 50%;
        }
    }
    .v-card__actions{
        a{
            margin-right: 1rem;
            margin-left: 1rem;
            i{
                font-size: 1rem;
            }
        }
    }
</style>
