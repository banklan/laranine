<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12">
                <auth-pageheader title="My Service" />
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="12" md="7">
                <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="50"></v-skeleton-loader>
                <v-card v-else light min-height="100" elevation="12" raised class="mx-auto">
                    <template v-if="service">
                        <v-img v-if="service.image" :src="service.image" height="400" transition="scale-transition" alt="service image"></v-img>
                        <v-img v-else src="/assets/shared/no-image.png" height="350" transition="scale-transition" alt="service image"></v-img>
                        <v-card-text class="mt-5" v-if="service">
                            <div class="wrap">
                                <div class="subject pl-3">Title: {{ service.title }}</div>
                            </div>
                            <div class="wrap">
                                <div class="subject pl-3">Experience Years: {{ service.experience_years }}</div>
                            </div>
                            <div class="wrap">
                                <div class="subject pl-3">Service ID: {{ service.service_id }}</div>
                            </div>
                            <div class="wrap">
                                <div class="subject pl-3">Category: {{ service.category && service.category.name }}</div>
                            </div>
                            <div class="wrap">
                                <div class="subject pl-3">Address: {{ service.address }}</div>
                            </div>
                            <div class="wrap">
                                <div class="subject pl-3">Location(State): {{ service.location && service.location.state }}</div>
                            </div>
                            <div class="wrap">
                                <div class="subject pl-3">Status: {{ service.is_active  ? 'Active' : 'Not Active' }}</div>
                            </div>
                            <div class="wrap">
                                <div class="subject pl-3">Account Type: {{ service.is_premium  ? 'Premium' : 'Regular' }}</div>
                            </div>
                            <div class="wrap" @click="showCatch = !showCatch">
                                <div class="subject"><i class="uil uil-plus"></i>Catch</div>
                                <div v-if="showCatch" class="body">{{ service.catch }}</div>
                            </div>
                            <div class="wrap" @click="showHighlight = !showHighlight">
                                <div class="subject"><i class="uil uil-plus"></i>Highlight</div>
                                <div v-if="showHighlight" class="body">{{ service.highlight }}</div>
                            </div>
                            <div class="wrap" @click="showKeyStrength = !showKeyStrength">
                                <div class="subject"><i class="uil uil-plus"></i>Keystrength</div>
                                <div v-if="showKeyStrength" class="body">{{ service.keystrength }}</div>
                            </div>
                            <div class="wrap" @click="showDesc = !showDesc">
                                <div class="subject"><i class="uil uil-plus"></i>Description</div>
                                <div v-if="showDesc" class="body">{{ service.description }}</div>
                            </div>
                        </v-card-text>
                    </template>
                    <v-card-text v-else>
                        <div class="text-center pb-6">
                            <v-alert type="info" class="mt-6">
                                You have not created a service for your account.
                            </v-alert>
                            <div class="create_service text-center mt-5">
                                <v-btn text color="primary" :to="{name: 'CreateService'}">Create Service</v-btn>
                            </div>
                        </div>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="service">
                        <v-btn icon color="icon_color" :to="{name: 'EditMyService'}"><i class="uil uil-edit"></i></v-btn>
                        <v-btn icon color="icon_color" :to="{name: 'UpdateServiceImage'}"><i class="uil uil-images"></i></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <service-mover :service="service" />
                <service-socials :service="service" v-if="service"></service-socials>
                <service-portfolio :service="service" v-if="service"></service-portfolio>
            </v-col>
        </v-row>
        <v-snackbar :value="maxPortfolio" :timeout="6000" top color="red darken-1 white--text">
            Maximum number of portfolios for a regular account has been reached. Kindly upgrade to premium plan to be able to upload more portfolios.
            <v-btn text color="white--text" @click="maxPortfolio = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="serviceUpdated" :timeout="4000" top color="green darken-1 white--text">
            Your service has been updated.
            <v-btn text color="white--text" @click="serviceUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="serviceImageAdded" :timeout="4000" top color="green darken-1 white--text">
            Your service imgae has been updated.
            <v-btn text color="white--text" @click="serviceImageAdded = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            isLoading: false,
            showDesc: false,
            showHighlight: false,
            showKeyStrength: false,
            showCatch: false,
        }
    },
    computed: {
        serviceImageAdded(){
            return this.$store.getters.serviceImageAdded
        },
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
        service(){
            return this.$store.getters.authUserService
        },
        maxPortfolio(){
            return this.$store.getters.maxPortfolio
        },
        serviceUpdated(){
            return this.$store.getters.serviceUpdated
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetFlashMsg')
        next()
    },
    methods:{
        toggleTitle(){
            if(this.display == 'hide'){
                this.display = 'show'
            }else{
                this.display === 'hide'
            }
            console.log("clicked!")
        }
    }
}
</script>

<style lang="scss" scoped>
    .wrap{
        background: #fbfbfb;
        border: 1px solid #d1d1d1;
        box-shadow: 0 2px 2px rgba(0,0,0,.18);
        padding: 8px 6px;
        color: #2c2c2c;
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
