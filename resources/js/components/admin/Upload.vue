<template>
    <v-container>
        <v-row>
            <v-col cols="6" md="3">
                <v-btn rounded color="primary" dark elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <div class="upload">
                    <div v-if="!previewImg" class="mt-5">
                        <v-btn outlined color="primary" large class="mb-5" @click="openUpload">Upload Image</v-btn>
                        <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                    </div>
                    <template v-else>
                        <v-card-subtitle class="text-center subtitle-1">Preview Uploaded Image</v-card-subtitle>
                        <v-img :src="previewImgUrl" height="200" contain alt="service photo" aspect-ratio="1"></v-img>
                        <v-card-actions class="justify-center mt-4 pb-7 ml-n3">
                            <v-btn dark color="primary" @click="uploadImg" :loading="isLoading">Upload Image</v-btn>
                            <v-btn text small @click="removeImg"><v-icon color="red darken--2">delete</v-icon></v-btn>
                        </v-card-actions>
                    </template>
                </div>
            </v-col>
        </v-row>
        <v-snackbar v-model="uploadFailed" :timeout="4000" top color="red darken-1 white--text">
            Upload failed!
            <v-btn text color="white--text" @click="uploadFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            previewImg: false,
            previewImg: null,
            uploadFailed: false,
            image: null,
        }
    },
    computed:{
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
                this.isLoading = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth-admin/upload_test_image`, form, this.adminHeaders).then((res) => {
                   this.isLoading = false
                   console.log(res.data)
                }).catch(() => {
                    this.isLoading = false
                    this.uploadFailed = true
                })
            }
        },
        removeImg(){}
    },
}
</script>
