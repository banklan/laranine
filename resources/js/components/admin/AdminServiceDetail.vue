<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-3">
            <v-col cols="12">
                <v-btn rounded color="primary" dark elevation="4" left :to="{name: 'AdminServicesList'}"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5" :class="$vuetify.breakpoint.smAndDown ? 'ml-n3 mr-n5': ''">
            <v-col cols="12" md="7">
                <v-skeleton-loader type="card" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else light raised outlined elevation="4" min-height="400" class="scroll">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Service Details</v-card-title>
                    <template v-if="service">
                        <v-img v-if="service.image" :src="service.image" height="100%" transition="scale-transition"></v-img>
                        <v-img v-else src="/assets/shared/no-image.png" height="250" transition="scale-transition"></v-img>
                        <v-card-text>
                            <v-simple-table light>
                                <template v-slot:default>
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">Title:</th>
                                            <td>{{ service.title }}</td>
                                        </tr>
                                        <tr>
                                            <th>ID:</th>
                                            <td>{{ service.id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Service ID:</th>
                                            <td>{{ service.service_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Catch:</th>
                                            <td>{{ service.catch }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category:</th>
                                            <td>{{ service.category &&  service.category.name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Experience Years:</th>
                                            <td>{{ service.experience_years }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location:</th>
                                            <td>{{ service.location && service.location.state }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ service.address }}. {{ service.city }}</td>
                                        </tr>
                                        <tr>
                                            <th>Keystrength:</th>
                                            <td>{{ service.keystrength }}</td>
                                        </tr>
                                        <tr>
                                            <th>Highlight:</th>
                                            <td>{{ service.highlight }}</td>
                                        </tr>
                                        <tr>
                                            <th>Position:</th>
                                            <td>{{ service.position }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ service.email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ service.phone }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                        <v-card-actions class="justify-space-around pb-8" v-if="service">
                            <v-btn icon color="icon_color"><i class="uil uil-edit"></i></v-btn>
                            <v-btn dark :color="service.is_approved ? '#db3800': '#026c08'" :loading="isApproving" @click="toggleApproval">{{ service.is_approved ? 'De-Approve' : 'Approve' }}</v-btn>
                            <v-btn dark :color="service.is_active ? 'primary_variant' : 'primary'" :loading="isActivating" @click="toggleStatus">{{ service.is_active ? 'De-activate' : 'Activate' }}</v-btn>
                            <v-btn icon color="red darken-2" @click="serviceDelConfDial = true"><i class="uil uil-trash-alt"></i></v-btn>
                        </v-card-actions>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card light raised outlined elevation="4" min-height="400" class="scroll" v-if="service">
                    <v-card-title class="justify-center primary white--text subtitle-1">Prime Mover & Stats</v-card-title>
                    <v-card-text class="mt-5">
                        <table class="table">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th style="border-top: none" width="20%">Name:</th>
                                    <td class="text-truncate" style="border-top: none"><router-link :to="{name: 'AdminUserDetail', params:{id: service.user.id}}">{{ service.user && service.user.fullname }}</router-link></td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td>{{ service.position }}</td>
                                </tr>
                                <tr>
                                    <th>Featured?</th>
                                    <td>{{ service.is_featured ? 'Yes' : 'No' }} <v-btn small dark class="ml-5" color="#11357e" @click="toggleFeature" :loading="changingFeature">Change</v-btn></td>
                                </tr>
                                <tr>
                                    <th>Account Plan</th>
                                    <td>{{ service.is_premium ? 'Premium' : 'Regular' }} <v-btn small dark class="ml-3" color="#1b9900" @click="togglePlan" :loading="changingPlan">Change</v-btn></td>
                                </tr>
                                <tr>
                                    <th>Views</th>
                                    <td>{{ service.views }}</td>
                                </tr>
                                <tr>
                                    <th>Ratings</th>
                                    <td>{{ service.ratings }}</td>
                                </tr>
                                <tr>
                                    <th>Reviews</th>
                                    <td>{{ service.reviews && service.reviews.length }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
                <v-card light outlined elevation="4" min-height="100" class="scroll mt-5" v-if="service">
                    <v-card-title class="justify-center primary white--text subtitle-1">Socials</v-card-title>
                    <v-card-text class="mt-5">
                        <table class="table">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th style="border-top: none" width="20%">Website:</th>
                                    <td style="border-top: none">www.{{ service.website }}</td>
                                </tr>
                                <tr v-if="service.facebook">
                                    <th>Facebook:</th>
                                    <td>www.facebook.com/{{ service.facebook }}</td>
                                </tr>
                                <tr v-if="service.twitter">
                                    <th>Twitter:</th>
                                    <td>www.twitter.com/{{ service.twitter }}</td>
                                </tr>
                                <tr v-if="service.instagram">
                                    <th>Instagram:</th>
                                    <td>www.instagram.com/{{ service.instagram }}</td>
                                </tr>
                                <tr v-if="service.linkedIn">
                                    <th>LinkedIn:</th>
                                    <td>www.linkedin.com/{{ service.linkedin }}</td>
                                </tr>
                                <tr v-if="service.youtube">
                                    <th>Youtube:</th>
                                    <td>{{ service.youtube }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
                <v-card light outlined elevation="4" min-height="100" class="scroll mt-5" v-if="service">
                    <v-card-title class="justify-center primary white--text subtitle-1">Portfolios</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="service.portfolio.length > 0">
                            <div class="portfolio" v-for="(pf, index) in service.portfolio" :key="pf.id">
                                <div class="item">
                                    <router-link :to="{name: 'AdminPortfolioDetail', params:{id: pf.id}}">{{ pf.title }}</router-link>
                                </div>
                                <v-divider v-if="index < service.portfolio.length - 1"></v-divider>
                            </div>
                        </template>
                        <v-alert v-else type="info">
                            There are no portfolios for this service.
                        </v-alert>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="serviceDelConfDial" max-width="450">
            <v-card min-height="120">
                <v-card-title class="subtitle-1 white--text primary justify-center">Are you sure you want to delete this service?</v-card-title>
                <v-card-text class="mt-5 subtitle-1"><p class="dark_text--text">Once deleted, the process cannot be undone.</p></v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text width="40%" color="red darken--2" @click="serviceDelConfDial = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary" :loading="isDeleting" @click="deleteService">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="serviceUpdated" :timeout="4000" top dark color="green darken-1">
            The service has been updated.
            <v-btn text color="white--text" @click="serviceUpdated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            service: null,
            serviceUpdated: false,
            isApproving: false,
            isActivating: false,
            changingPlan: false,
            changingFeature: false,
            serviceDelConfDial: false,
            isDeleting: false,
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
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminFlashMsg')
        next()
    },
    methods: {
        getService(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_service/${this.$route.params.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.service = res.data
                console.log(res.data)
            })
        },
        toggleApproval(){
            this.isApproving = true
            axios.post(this.api + `/auth-admin/toggle_approval/${this.$route.params.id}`, {}, this.adminHeaders).then((res) => {
                this.isApproving = false
                this.service.is_approved = res.data
                this.serviceUpdated = true
            })
        },
        toggleStatus(){
            this.isActivating = true
            axios.post(this.api + `/auth-admin/toggle_status/${this.$route.params.id}`, {}, this.adminHeaders).then((res) => {
                this.isActivating = false
                this.service.is_active = res.data
                this.serviceUpdated = true
            })
        },
        togglePlan(){
            this.changingPlan = true
            axios.post(this.api + `/auth-admin/toggle_plan/${this.$route.params.id}`, {}, this.adminHeaders).then((res) => {
                this.changingPlan = false
                this.service.is_premium = res.data
                this.serviceUpdated = true
            })
        },
        toggleFeature(){
            this.changingFeature = true
            axios.post(this.api + `/auth-admin/toggle_feature/${this.$route.params.id}`, {}, this.adminHeaders).then((res) => {
                this.changingFeature = false
                this.service.is_featured = res.data
                this.serviceUpdated = true
            })
        },
        deleteService(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_Service/${this.$route.params.id}`, {}, this.adminHeaders).then((res) => {
                this.isDeleting = false
                this.$router.push({name: 'AdminServicesList'})
            })
        }
    },
    mounted() {
        this.getService()
    },
}
</script>
