<template>
    <v-container>
        <v-row justify="center" class="update_pg">
            <v-col cols="12" md="2">
                <v-btn color="primary" text elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
            <v-col cols="12" md="7" >
                <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else light elevation="6" min-height="150" class="mx-auto">
                    <v-card-title class="justify-center subtitle-1 primary white--text">Update Portfolio Files</v-card-title>
                    <v-card-text class="mt-5" v-if="portfolio">
                        <div class="pf_hint" v-if="files.length > 0">This profile has {{ files.length }} pictures.</div>
                        <div class="files_wrap" v-if="files.length > 0">
                            <div class="file" v-for="(file, index) in files" :key="file.id">
                                <img :src="`/assets/portfolios/${file.file}`" alt="file.file">
                                <v-btn large icon color="red darken-2" @click="confirmDel(file, index)"><i class="uil uil-trash-alt"></i></v-btn>
                            </div>
                        </div>
                        <div v-else>
                            <v-alert type="info" border="left">
                                You have not uploaded any pictures for this portfolio.
                            </v-alert>
                        </div>
                    </v-card-text>
                    <v-divider></v-divider>
                    <div class="upload text-center pb-8">
                        <div v-if="!preview" class="text-center mt-8">
                            <v-btn outlined color="primary_variant" @click="openUpload">Add Pictures</v-btn>
                            <input type="file" multiple ref="files" style="display:none" @change.prevent="pickImg" accept="image/*">
                        </div>
                        <div v-else class="preview">
                            <v-card-actions v-if="filesPicked" class="info--text justify-center mb-3 mx-3 font-weight-bold">
                                **{{ uploads.length }} files have been selected for upload.**
                            </v-card-actions>
                            <v-card-actions class="justify-center">
                                <v-btn text large color="red darken-2" outlined @click="cancelUploads">Cancel</v-btn>
                                <v-btn dark large color="primary" @click="uploadImgs" :loading="isBusy">Upload Files</v-btn>
                            </v-card-actions>
                        </div>
                    </div>
                </v-card>
            </v-col>
             <v-col cols="12" md="4" ></v-col>
            <v-dialog v-model="confirmDelDial" max-width="450">
                <v-card min-height="120">
                    <v-card-title class="subtitle-1 white--text primary justify-center">Are you sure you want to delete this file?</v-card-title>
                    <v-card-actions class="pb-8 mt-3 justify-center">
                        <v-btn text width="40%" color="red darken--2" @click="confirmDelDial = false">Cancel</v-btn>
                        <v-btn dark width="40%" color="primary" :loading="isDeleting" @click="deleteFile">Yes, Delete</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-snackbar v-model="fileDeleted" :timeout="3000" top color="green darken-1 white--text">
                Your portfolio picture has been deleted.
                <v-btn text color="white--text" @click="fileDeleted = false">Close</v-btn>
            </v-snackbar>
            <!-- <v-snackbar v-model="fileDelFailed" :timeout="5000" top color="red darken-1 white--text">
                There was an error while trying to delete a portfolio picture. Please try again.
                <v-btn text color="white--text" @click="fileDelFailed = false">Close</v-btn>
            </v-snackbar> -->
            <v-snackbar v-model="maxExceeded" :timeout="4000" top color="red darken-1 white--text">
                You cannot upload more than 5 files to your portfolio.
                <v-btn text color="white--text" @click="maxExceeded = false">Close</v-btn>
            </v-snackbar>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            portfolio: null,
            files: [],
            fileToDel: null,
            indexToDel: null,
            confirmDelDial: false,
            isDeleting: false,
            fileDeleted: false,
            fileDelFailed: false,
            preview: false,
            files: [],
            filesPicked: false,
            maxExceeded: false,
            uploads: [],
            filesUploaded: false,
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
                this.files = res.data.files
            })
        },
        confirmDel(file, index){
            this.fileToDel = file
            this.indexToDel = index
            this.confirmDelDial = true
        },
        deleteFile(){
            this.isDeleting = true
            axios.post(this.api + `/auth/delete_portfolio_file/${this.fileToDel.id}`, {}, this.authHeaders)
            .then((res) => {
                this.isDeleting = false
                this.files.splice(this.indexToDel, 1)
                this.confirmDelDial = false
                this.fileDeleted = true
                this.$store.dispatch(resetAuthService)
            }).catch(() => {
                this.isDeleting = false
                this.fileDelFailed = true
            })
        },
        openUpload(){
            this.$refs.files.click()
        },
        pickImg(e){
            const files = e.target.files
            if(!files){
                return false
            }
            this.preview = true
            let filesCount = this.files.length
            let uploadAble = parseInt(5 - filesCount)
            if(files.length <= uploadAble){
                for(let i=0; i<files.length; i++){
                    this.uploads.push(files[i])
                }
                this.filesPicked = true
            }else{
                 this.maxExceeded = true
                 this.preview = false
            }
        },
        cancelUploads(){
            this.preview = false
            this.uploads = []
        },
        uploadImgs(){
            this.isBusy = true
            let form = new FormData();
            let self = this
            for(let i=0; i<this.uploads.length; i++){
                let file = self.uploads[i]
                form.append('files[' + i + ']', file)
            }
            axios.post(this.api + `/auth/upload_pf_images/${this.$route.params.id}`, form, this.authHeaders)
            .then((res)=>{
                this.isBusy = false
                this.previewUploads = false
                this.$router.push({name: 'MyPortfolioDetail', params:{id: res.data.id}})
                this.resetUpload()
            }).catch((err) => {
                this.isBusy = false
                this.createFail = true
            })
        },
        resetUpload(){
            this.preview = false
            this.uploads = []
            this.$refs.files.value = ''
        },
    },
    mounted() {
        this.getPortfolio()
    },
}
</script>

<style lang="scss" scoped>
    .update_pg{
        margin-top: 6rem;
        .pf_hint{
            padding: .6rem .5rem 1.5rem .5rem;
            font-size: 1rem;
            color: #2e2e2e;
            font-weight: bold;
        }
        .files_wrap{
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;

            .file{
                display: flex;
                align-items: center;
                gap: 5rem;
                justify-content: space-between;

                .v-btn {
                    margin-right: 3rem;
                    i{
                        font-size: 1.1rem;
                    }
                }
                // background: blue;
                img{
                    height: 10rem;
                    width: 10rem;
                    border: 1px solid rgb(243, 243, 243);
                    border-radius: 4px;
                }
            }
        }
    }
</style>
