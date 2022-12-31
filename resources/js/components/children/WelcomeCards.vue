<template>
    <v-container>
        <v-row justify="center" class="mt-7 mb-5">
            <v-col cols="12" md="8">
                <div class="section_header text-center">
                    Take your services to the next level! List them on HozuMart!
                </div>
            </v-col>
        </v-row>
        <v-row justify="center" class="mb-8">
            <v-col cols="12" md="5">
                <v-card tile raised elevation="12" outlined ripple min-height="400" light>
                    <v-card-title class="justify-center mt-5">
                        <v-icon color="primary">mdi-tools</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center">
                        <p class="card_headers pa-2">Join Our professionals in 2 easy steps!</p>
                        <p class="card_text pt-2 px-2">Register and list your service. <br> Be descriptive with the details of your service.</p>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn dark color="primary white--text" rounded x-large to="/register" class="px-5">Register Now</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card hover tile raised outlined ripple min-height="400" light>
                    <v-card-title class="justify-center mt-5">
                        <v-icon color="primary">mdi-shield-check</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center">
                        <p class="card_headers pa-2">Get Verified</p>
                        <p class="card_text pt-2">Add your portfolio. Include valid contacts, social media handles & business locations, get verified.</p>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8 mt-n1">
                        <v-btn dark elevation="12" color="primary" rounded x-large class="px-5">Create Portfolio</v-btn>
                        <a ref="search" href="#pick_loc" style="display:none"></a>
                    </v-card-actions>
                </v-card>
            </v-col>

        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
        }
    },
    methods: {
        goToSearch(){
            this.$refs.search.click()
        }
    },
    created() {

    },
}
</script>

<style lang="scss" scoped>
    .v-icon{
        font-size: 7rem !important;
    }
    .v-btn{
        text-decoration: none !important;
        transition: all .4s !important;
    }
    .v-card .v-card__actions a{
        text-decoration: none;
        transition: all .3s !important;
        &:hover{
            background: transparent !important;
            border:1px solid #ff5200 !important;
            color: #ff5200 !important;
        }
    }
    .v-card i{
        font-size: 7rem;
        color: #99154E;
    }
    .v-card__actions a.v-btn{
        transition: all .4s ease !important;
        &:hover{
            background: white !important;
            color: #99154E !important;
            border: 1px solid #99154E !important;
        }
    }
</style>
