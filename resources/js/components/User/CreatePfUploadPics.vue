<template>
    <v-container>
        <v-row justify="center" class="mt-10">
            <v-col cols="12" md="7">
                <v-card elevation="3" light min-height="200" class="mx-auto mt-5">
                    <v-card-title class="mt-5 subtitle-1 justify-center primary white--text">Create Portfolio</v-card-title>
                    <v-card-text class="mt-5">
                        <v-alert dismissible close-icon="cancel" type="info">
                            Upload images showing your service portfolio.
                        </v-alert>
                        <div v-if="!preview" class="text-center mt-8">
                            <v-btn outlined color="primary lighten--2" @click="openUpload">Add Images</v-btn>
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
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6" v-if="!preview">
                        <v-btn large text color="primary" @click="routeToPortfolioDetail">Create Portfolio, I don't want to upload any files.</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="maxExceeded" :timeout="4000" top color="red darken-1 white--text">
            You cannot upload more than 5 files to your portfolio.
            <v-btn text color="white--text" @click="maxExceeded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createFail" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to create your portfolio. Please ensure you are uploading valid image files (JPEG, JPG, PNG, PDF).
            <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            preview: false,
            isBusy: false,
            files: [],
            filesPicked: false,
            maxExceeded: false,
            uploads: [],
            filesUploaded: false,
            createFail: false,
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        authService(){
            return this.$store.getters.authUserService
        },
        portfolio(){
            let ports = this.$store.getters.authUserService.portfolio
            let portf = ports.find(item => item.id == this.$route.params.id)
            return portf
        },
        api(){
            return this.$store.getters.api
        },
        fullHeader(){
            let Header = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`,
                    "Content-Type": "multipart/form-data"
                }
            }
            return Header;
        },
        header(){
            let Header = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return Header;
        }
    },
    methods: {
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
            axios.post(this.api + `/auth/upload_pf_images/${this.$route.params.id}`, form, this.fullHeader)
            .then((res)=>{
                this.isBusy = false
                this.routeToPortfolioDetail()
                this.previewUploads = false
                this.resetUpload()
            }).catch((err) => {
                this.isBusy = false
                this.createFail = true
            })
        },
        resetUpload(){
            this.uploads = []
            this.$refs.files.value = ''
        },
        routeToPortfolioDetail(){
            // this.$store.commit('portfolioCreated')
            this.$store.dispatch('resetAuthService')
            this.$router.push({name: 'MyPortfolioDetail', params:{id: this.$route.params.id}})
        }
    }
}
</script>
