<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-btn rounded color="primary" dark elevation="4" left :to="{name: 'AdminPortfolioList'}"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5" :class="$vuetify.breakpoint.smAndDown ? 'ml-n3 mr-n5': ''">
            <v-col cols="12" md="7">
                <v-skeleton-loader type="card" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else light raised outlined elevation="4" min-height="300" class="scroll">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Portfolio Details</v-card-title>
                    <v-card-text class="">
                        <template v-if="portfolio">
                            <table class="table">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="20%" style="border-top:none">Title</th>
                                        <td style="border-top:none">{{ portfolio.title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Detail</th>
                                        <td>{{ portfolio.detail }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cost(&#8358;)</th>
                                        <td>{{ portfolio.cost | price()}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <v-alert v-else>
                            The portfolio you are trying to view does not exist.
                        </v-alert>
                    </v-card-text>
                    <v-card-actions class="justify-space-around pb-8 mx-5" v-if="portfolio">
                        <v-btn icon color="icon_color" :to="{name: 'AdminUpdatePortfolio', params: {id: portfolio.id}}"><i class="uil uil-edit-alt"></i></v-btn>
                        <v-btn dark small color="primary" :loading="isApproving" @click="approvePf">{{ portfolio.is_approved ? 'Dis-Approve' : 'Approve' }}</v-btn>
                        <v-btn icon color="red darken-1" @click="confirmDelDialog = true"><i class="uil uil-trash-alt"></i></v-btn>
                    </v-card-actions>
                </v-card>
                <v-card min-height="150" class="mt-5" v-if="portfolio">
                    <template v-if="portfolio.files && portfolio.files.length > 0">
                        <v-carousel height="400" cycle>
                            <v-carousel-item contain v-for="(file, i) in portfolio.files" :key="i" :src="file.file" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                        </v-carousel>
                        <v-card-actions class="justify-center px-3 mt-5">
                            <v-btn large icon color="red darken-2 mx-5" @click="fileListDial = true">
                                <i class="uil uil-trash-alt"></i>
                            </v-btn>
                        </v-card-actions>
                    </template>
                    <v-card-text class="my-5" v-else>
                        <v-alert type="info" class="my-5">
                            This portfolio has no files.
                        </v-alert>
                    </v-card-text>
                </v-card>
            </v-col>
             <v-col cols="12" md="5">
                 <v-card light raised outlined elevation="4" min-height="200" class="scroll">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Service Details</v-card-title>
                    <v-card-text v-if="portfolio" class="">
                        <template v-if="portfolio.service">
                            <table class="table">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <th width="20%" style="border-top:none">Service:</th>
                                        <td style="border-top:none"><router-link :to="{name:'AdminServiceDetail', params:{id: portfolio.service.id}}">{{ portfolio.service.title }}</router-link></td>
                                    </tr>
                                    <tr>
                                        <th>Prime Mover:</th>
                                        <td><router-link :to="{name:'AdminUserDetail', params:{id: portfolio.service.user.id}}">{{ portfolio.service.user.fullname }}</router-link></td>
                                    </tr>
                                    <tr>
                                        <th>Catch:</th>
                                        <td>{{ portfolio.service.catch }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ratings:</th>
                                        <td>{{ portfolio.service.ratings }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                    </v-card-text>
                </v-card>
             </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDialog" max-width="450">
            <v-card min-height="120">
                <v-card-title class="subtitle-1 white--text primary justify-center">Are you sure you want to delete this portfolio?</v-card-title>
                <v-card-text class="mt-5 subtitle-1"><p class="dark_text--text">Once deleted, the process cannot be undone.</p></v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text width="40%" color="red darken--2" @click="confirmDelDialog = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary" :loading="isDeleting" @click="deletePf">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmFileDelDial" max-width="450">
            <v-card min-height="120">
                <v-card-title class="subtitle-1 white--text primary justify-center">Are you sure you want to delete this file?</v-card-title>
                <v-card-text class="mt-5 subtitle-1"><p class="dark_text--text">Once deleted, the process cannot be undone.</p></v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text width="40%" color="red darken--2" @click="confirmFileDelDial = false">Cancel</v-btn>
                    <v-btn dark width="40%" color="primary" :loading="isDeleting" @click="deleteFile">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="fileListDial" max-width="450" v-if="portfolio && portfolio.files">
            <v-card min-height="120">
                <v-card-title class="subtitle-1 white--text primary justify-center">Delete Portfolio Files</v-card-title>
                <v-card-text class="mt-5 subtitle-1">
                    <template v-if="portfolio && portfolio.files.length > 0">
                        <div class="pf_files" v-for="(file, index) in portfolio.files" :key="file.id">
                            <div class="file">
                                <img :src="file.file" alt="portfolio image">
                            </div>
                            <v-btn icon color="red darken-1" @click="delFileConfirmation(file, index)"><i class="uil uil-times"></i></v-btn>
                        </div>
                    </template>
                    <template v-else>
                        <v-alert type="info" class="mt-5">
                            There are no files for this portfolio.
                        </v-alert>
                    </template>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center" v-if="portfolio && portfolio.files.length > 0">
                    <v-btn text width="60%" color="red darken--2" @click="fileListDial = false">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminUpdatedPf" :timeout="4000" top dark color="green darken-1">
            This portfolio has been updated.
            <v-btn text color="white--text" @click="adminUpdatedPf = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="portfolioUpdated" :timeout="3000" top dark color="green darken-1">
            This portfolio has been updated.
            <v-btn text color="white--text" @click="portfolioUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="portfolioFileDeleted" :timeout="3000" top dark color="green darken-1">
            Portfolio file was deleted.
            <v-btn text color="white--text" @click="portfolioFileDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            portfolio: null,
            isApproving: false,
            isDeleting: false,
            portfolioUpdated: false,
            confirmDelDialog: false,
            confirmFileDelDial: false,
            fileListDial: false,
            fileToDelIndex: null,
            fileToDel: null,
            portfolioFileDeleted: false,
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        adminUpdatedPf(){
            return this.$store.getters.adminUpdatedPf
        }
    },
    methods: {
        getPf(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_portfolio/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.portfolio = res.data
                console.log(res.data)
            })
        },
        approvePf(){
            this.isApproving = true
            axios.post(this.api + `/auth-admin/toggle_pf_approval/${this.$route.params.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isApproving = false
                this.portfolio.is_approved = res.data
                this.portfolioUpdated = true
            })
        },
        deletePf(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_pf/${this.$route.params.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.$store.commit('adminDeletedPf')
                this.$router.push({name: 'AdminPortfolioList'})
            })
        },
        delFileConfirmation(file, index){
            this.fileToDel = file
            this.fileToDelIndex = index
            this.confirmFileDelDial = true
        },
        deleteFile(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/del_portfolio_file/${this.fileToDel.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.portfolio.files.splice(this.fileToDelIndex, 1)
                this.confirmFileDelDial = false
                this.portfolioFileDeleted = true
                this.confirmFileDelDial = false
            })
        }
    },
    mounted() {
        this.getPf()
    },
}
</script>

<style lang="scss" scoped>
    .pf_files{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.2rem;

        img{
            height: 6rem;
            width: 6rem;
        }
        .v-btn{
            margin-right: 2rem;
        }
    }
</style>
