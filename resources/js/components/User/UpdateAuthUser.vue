<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12">
                <auth-pageheader title="Update Profile" back="UserProfile" />
            </v-col>
        </v-row>
        <v-row justify="center" class="acc_pg">
            <v-col cols="12" md="6">
                <v-card elevation="3" min-height="200" raised class="mx-auto">
                    <v-card-title class="justify-center primary white--text">Update User Details</v-card-title>
                    <v-card-text class="mt-5">
                        <v-text-field label="First Name" v-model="user.first_name" required placeholder="First Name/Given name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('first_name')" name="first_name" data-vv-as="first name"></v-text-field>
                        <v-text-field label="Last Name" v-model="user.last_name" required placeholder="Surname/Family Name" v-validate="'required|min:2|max:30'" :error-messages="errors.collect('last_name')" name="last_name" data-vv-as="last name"></v-text-field>
                        <v-text-field label="Phone No" type="text" v-model="user.phone" required v-validate="'required|numeric|max:16'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6">
                        <v-btn dark large color="primary" width="50%" :loading="isBusy" @click="update">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="4" offset-md="1">
                <v-card elevation="3" min-height="200" raised class="mx-auto">
                    <v-card-title class="justify-center primary white--text">Update User Picture</v-card-title>
                    <v-card-text class="mt-5 pic_update">
                        <div class="profile_pic">
                            <v-img v-if="authUser.picture" :src="authUser.picture" alt="Profile picture"></v-img>
                            <v-img v-else src="/assets/shared/no-image.png" alt="Profile picture"></v-img>
                        </div>
                        <div class="update_pic">
                            <div v-if="!previewImg">
                                <v-btn outlined color="primary" large class="mb-5" @click="openUpload">Replace Image</v-btn>
                                <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                            </div>
                            <div v-else class="preview">
                                <v-card-subtitle class="text-center subtitle-1">Preview Uploaded Image</v-card-subtitle>
                                <div class="prev_img">
                                    <v-img :src="previewImgUrl" height="" contain alt="service photo" aspect-ratio="1"></v-img>
                                </div>
                                <v-card-actions class="justify-center mt-4 pb-7">
                                    <v-btn dark color="primary" @click="uploadImg" :loading="isUploading">Upload Picture</v-btn>
                                    <v-btn text @click="removeImg" color="red darken-2"><i class="uil uil-trash"></i></v-btn>
                                </v-card-actions>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="updateFailed" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to update your profile. Please try again later
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="uploadFailed" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying to update your profile picture. Please try again later
            <v-btn text color="white--text" @click="uploadFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            user: {
                first_name: '',
                last_name: '',
                phone: ''
            },
            isBusy: false,
            updateFailed: false,
            previewImg: false,
            previewImgUrl: null,
            image: null,
            isUploading: false,
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
        authUserService(){
            return this.$store.getters.authUserService
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
        update(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isBusy = true
                    axios.post(this.api + '/auth/update_user_details', {
                        user: this.user
                    }, this.authHeaders).then((res) => {
                        this.isBusy = false
                        this.$store.commit('resetAuthUser', res.data)
                        this.$router.push({name: 'UserAccount'})
                        // console.log(res.data)
                    }).catch(() => {
                        this.isBusy = false
                        this.updateFailed = true
                    })
                }
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
                this.isUploading = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth/update_profile_pic`, form, this.authHeaders).then((res) => {
                    this.isUploading = false
                    this.$router.push('/user/profile')
                    this.$store.commit('resetAuthUser', res.data)
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
    },
    mounted() {
        this.user = this.authUser
    },

}
</script>


<style lang="scss" scoped>
    .acc_pg{
        margin-top: 1rem;
        margin-bottom: 5rem;
        .profile_card{
            margin: 0 auto;
            background: #fff;
            color: #2e2e2e;
            border: 1px solid #d3d3d3;
            border-radius: 6px;
            box-shadow: 0 2px 2px rgba(0,0,0,.13);
            width: 100%;
            min-height: 20rem;
            box-sizing: border-box;
            cursor:pointer;
            transition: all .4s ease;

            .upper{
                position: relative !important;
                .v-image{
                    height: 8rem;
                }
            }
            .lower{
                width: 100%;
                height: 100%;
                margin: 7rem auto 1rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center !important;
                gap: .5rem;

                .mover{
                    color: #1c1c1c;
                    font-size: 1rem;
                    padding-left: 5px;
                }
                .email, .phone{
                    font-size: .9rem;
                    i{
                        color: #99154E;
                        font-size: .9rem;
                    }
                }
            }
            .edit_btn{
                margin: 1rem auto;
                text-align: center;
                i{
                    font-size: 1rem;
                }
            }
            .picture{
                margin: 0 auto;
                width: 10rem;
                height: 10rem;
                position: absolute !important;
                top: 8%;
                left: 35%;
                overflow: hidden;
                img{
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    border: 1px solid #d3d3d3;
                    z-index: 100;
                }
            }
        }

        .acct_params{
            margin: 0 auto;
        }
    }
    table{
        tbody{
            tr{
                th{
                    white-space: nowrap !important;
                }
                td{
                    a{
                        color: #03286d;
                        text-decoration: none;
                    }
                }
            }
        }
    }
    .pic_update{
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        gap: 1rem;

        .profile_pic{
            margin: 0 auto;
            // padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 8rem;
            width: 8rem;
            .v-image{
                height: 100%;
                width: 100%;
                border-radius: 50%;
                // border: 1px solid #d3d3d3;
            }
        }
        .update_pic{
            margin: 0 auto;
            text-align: center;

            .preview{
                margin: 0 auto;
                .prev_img{
                    margin: 0 auto;
                    height: 8rem;
                    width: 8rem;
                }
                .v-card__actions{
                    .v-btn{
                        i{
                            color: rgb(248, 24, 24) !important
                        }
                    }
                }
            }
        }
    }
</style>
