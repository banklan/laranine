<template>
    <v-container>
        <v-row>
            <v-col cols="6" md="3">
                <v-btn rounded color="primary" dark elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <div class="wrap">
                    <div class="prof_pic text-center" v-if="user && user.picture">
                        <v-img :src="user.picture" ></v-img>
                    </div>
                    <div v-else>
                        <v-alert type="info" class="mt-5">
                            There is no profile picture for the admin user.
                        </v-alert>
                    </div>
                </div>
                <div class="upload">
                    <div v-if="!previewImg" class="text-center">
                        <v-btn text color="primary" large class="" @click="openUpload">Update Profile Picture</v-btn>
                        <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                    </div>
                    <template v-else>
                        <v-card-subtitle class="text-center subtitle-1">Preview Uploaded Image</v-card-subtitle>
                        <v-img :src="previewImgUrl" height="200" contain alt="service photo" aspect-ratio="1"></v-img>
                        <v-card-actions class="justify-center mt-4 pb-7 ml-n3">
                            <v-btn dark color="primary" @click="uploadImg" :loading="isBusy">Upload Image</v-btn>
                            <v-btn text small color="red darken-1" @click="removeImg" class="ml-2"><i class="uil uil-trash"></i></v-btn>
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
            previewImgUrl: null,
            uploadFailed: false,
            image: null,
            user: null,
            isBusy: false,
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
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_superuser/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.user = res.data
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

                axios.post(this.api + `/auth-admin/update_superuser_profile_pic/${this.$route.params.id}`, form, this.adminHeaders)
                .then((res) => {
                   this.isBusy = false
                //    this.user.picture = res.data
                   this.$store.commit('AdminUpdatedSUProfilePic')
                   this.$router.push({name: 'AdminSuperUserDetail', params:{id: this.$route.params.id}})
                   console.log(res.data)
                }).catch(() => {
                    this.isBusy = false
                    this.uploadFailed = true
                })
            }
        },
        removeImg(){
            this.image = null
            this.previewImgUrl = ''
            this.previewImg = false
        }
    },
    mounted() {
        this.getUser()
    },
}
</script>

<style lang="scss" scoped>
.wrap{
    margin: 1.5rem auto;
    width: 100%;
    // margin-top: 1.5rem;

    .prof_pic{
        text-align: center;
        margin: 0 auto;
        border: 1px solid rgb(175, 175, 175);
        box-shadow: 0 2px 2px rgba(0,0,0,.16);
        border-radius: 16px;
        height: 12rem;
        width: 12em;
        // padding-bottom: 2rem;

        .v-image{
            height: 12rem;
            width: 12rem;
        }
    }
}
.upload{
    margin: 0 auto;

}
</style>
