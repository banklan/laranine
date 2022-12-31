<template>
     <v-container>
        <v-row justify="center" class="mt-15">
            <v-col cols="12" md="2">
                <v-btn color="primary" text elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
            <v-col cols="12" md="8" >
                <v-card light elevation="6" min-height="300" class="mx-auto">
                    <v-card-title class="justify-center subtitle-1">Upload Image For New Service</v-card-title>
                    <v-alert type="info" class="my-2 mx-3" dismissible close-icon="cancel" rounded="10" >
                        Please upload a picture that will be used as display picture for your service/brand. It could be a logo or a picture showing your service.
                    </v-alert>
                    <v-card-text class="text-center">
                        <div v-if="!previewImg" class="mt-5">
                            <v-btn outlined color="primary" large class="mb-5" @click="openUpload">Upload Image</v-btn>
                            <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                        </div>
                        <template v-else>
                            <v-card-subtitle class="text-center subtitle-1">Preview Uploaded Image</v-card-subtitle>
                            <v-img :src="previewImgUrl" height="200" contain alt="service photo" aspect-ratio="1"></v-img>
                            <v-card-actions class="justify-center mt-4 pb-7 ml-n3">
                              <v-btn dark color="primary" @click="uploadImg" :loading="isUploading">Upload Image</v-btn>
                              <v-btn text small @click="removeImg"><v-icon color="red darken--2">delete</v-icon></v-btn>
                            </v-card-actions>
                        </template>
                    </v-card-text>
                    <v-card-actions class="pb-5 mx-5" v-if="!previewImg">
                        <v-btn text color="primary" large dark class="bactToService" to="/my-service">No, i don't want to upload image</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { store } from '../../store/index'
export default {
    data() {
        return {
            id: this.$route.params.id,
            previewImg: false,
            isBusy: false,
            previewImgUrl: '',
            image: null,
            isUploading: false,
        }
    },
    computed:{
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
        }
    },
    beforeRouteEnter (to, from, next) {
        let exp = store.getters.serviceImgExpected
        if(exp){
            next()
        }else{
            next('/dashboard')
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetFlashMsg')
        next()
    },
    methods: {
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
                this.isUploading = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth/service/add_image`, form, this.authHeaders).then((res) => {
                    this.isUploading = false
                    this.$router.push('/user/my-service')
                    this.$store.commit('serviceImageAdded', res.data)
                }).catch(() => {
                    this.isUploading = false
                    this.uploadFailed = true
                })
            }
        },
        removeImg(){
            this.image = null
            this.previewImg = null
            this.previewImgUrl = ''
        }
    }
}
</script>

<style lang="scss" scoped>
    .v-card__title{
        padding-top: 2rem;
    }
    a{
        text-decoration: none;
    }

</style>
