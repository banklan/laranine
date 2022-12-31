<template>
    <div class="service_mover mt-8">
        <v-card light min-height="100" elevation="12" raised class="mx-auto">
            <v-card-title class="justify-center subtitle-1 primary white--text">Portfolio <v-chip class="ml-2" color="primary lighten-2" v-if="service.portfolio && service.portfolio.length > 0">{{ service.portfolio.length }}</v-chip></v-card-title>
            <v-card-text color="dark_text--text">
                <template v-if="service && service.portfolio">
                    <div class="portfolio" v-for="(pf, index) in service.portfolio" :key="pf.id">
                        <div class="item">
                            <router-link :to="{name: 'MyPortfolioDetail', params:{id: pf.id}}">{{ pf.title }}</router-link>
                        </div>
                        <!-- <v-divider></v-divider> -->
                        <v-divider v-if="index < service.portfolio.length - 1"></v-divider>
                    </div>
                    <div class="text-center pb-5 mt-5" v-if="service.portfolio.length < 3">
                        <v-btn text color="primary" :to="{name: 'CreatePortfolio'}">New Portfolio</v-btn>
                    </div>
                </template>
                <template v-else>
                    <div class="warning--text mt-5 pb-6">You have not set up a portfolio for your service.</div>
                    <div class="text-center pb-5">
                        <v-btn text color="primary" :to="{name: 'CreatePortfolio'}">Create Portfolio</v-btn>
                    </div>
                </template>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['service']
}
</script>

<style lang="scss" scoped>
 .v-card{
    .v-card__text{
        font-size: .9rem;
        color: #2e2e2e;

        .portfolio{
            margin-top: 1.2rem;
            padding: 0 .6rem;

            .item {
                padding: 0;
                a{
                    font-size: .9rem;
                    text-decoration: none;
                }
            }
        }
    }
 }
 </style>
