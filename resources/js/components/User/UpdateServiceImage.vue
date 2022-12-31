<template>
    <v-container>
         <v-row justify="center" class="update_pg">
            <v-col cols="12" md="2">
                <v-btn color="primary" text elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
            <v-col cols="12" md="8" >
                <div class="text-center pb-4 title">Update Service Image</div>
                <v-skeleton-loader type="card" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <v-card v-else light elevation="6" min-height="150" class="mx-auto">
                    <v-card-text class="service_img" v-if="service">
                        <v-img :src="service.image" height="350" transition="scale-transition" alt="service image"></v-img>
                      </v-card-text>
                    <v-card-text class="text-center pb-8">
                        <div v-if="!previewImg">
                            <v-btn outlined color="primary" large class="mb-5" @click="openUpload">Replace Image</v-btn>
                            <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                        </div>
                        <div v-else class="preview">
                            <v-card-subtitle class="text-center subtitle-1">Preview Uploaded Image</v-card-subtitle>
                            <div class="prev_img">
                                <v-img :src="previewImgUrl" height="" contain alt="service photo" aspect-ratio="1"></v-img>
                            </div>
                            <v-card-actions class="justify-center mt-6 pb-7">
                              <v-btn dark color="primary" @click="uploadImg" :loading="isBusy">Upload Image</v-btn>
                              <v-btn text @click="removeImg" color="red darken-2"><i class="uil uil-trash"></i></v-btn>
                            </v-card-actions>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="uploadFailed" :timeout="4000" top color="red darken-1 white--text">
            There was an error while trying to update your service picture. Please try again.
            <v-btn text color="white--text" @click="uploadFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            service: null,
            isLoading: false,
            previewImg: false,
            isBusy: false,
            previewImgUrl: '',
            image: null,
            uploadFailed: false,
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
        getService(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_auth_service`, this.authHeaders)
            .then((res) => {
                this.isLoading = false
                this.service = res.data
            })
        },
        openUpload(){
            this.$refs.image.click()
        },
        pickImg(e){
            const file = e.target.files[0]
            this.image = file
            this.previewImg = true
            this.previewImgUrl = URL.createObjectURL(file)
        },
        uploadImg(){
            if(this.image !== null){
                this.isBusy = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth/update_service_image`, form, this.authHeaders).then((res) => {
                    this.isBusy = false
                    this.$router.push('/user/my-service')
                    this.$store.commit('serviceImageAdded', res.data)
                }).catch(() => {
                    this.isBusy = false
                    this.uploadFailed = true
                })
            }
        },
        removeImg(){
            this.image = null
            this.previewImg = null
            this.previewImgUrl = ''
        }
    },
    mounted() {
        this.getService()
    },
}
</script>



<style lang="scss" scoped>
    .update_pg{
        margin-top: 6rem;
    }
    .v-card__text{
        .preview{
            .prev_img{
                width: 15rem;
                height: 15rem;
                border-radius: 6px;
                margin: 0 auto;
                .v-image{
                    height: 100%;
                    width: 100%;
                }
            }
        }
    }
    .v-card__actions{
        margin-top: 1rem !important;
        .v-btn{
            margin-left: 1.5rem;
            margin-right: 1.5rem;

            i{
                font-size: 1.1rem;
            }
        }
    }
    .v-card__text.service_img{
        padding: 0 0 !important;
        margin-bottom: 2rem;
    }
</style>
