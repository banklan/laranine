<template>
    <v-container>
        <v-row justify="center" class="mt-15">
            <v-col cols="12" md="2">
                <v-btn color="primary" text elevation="4" left @click="$router.go(-1)"><i class="uil uil-arrow-left"></i> Back</v-btn>
            </v-col>
            <v-col cols="12" md="8" >
                <v-card light elevation="6" min-height="300" class="mx-auto">
                    <v-card-title class="justify-center subtitle-1">Create New Service</v-card-title>
                    <v-alert type="info" class="my-2 mx-3" dismissible close-icon="cancel" rounded="10" >
                        All fields marked * are required.
                    </v-alert>
                    <v-card-text class="">
                        <v-select :items="categories" item-text="name" item-value="id" label="Select Category*" v-model="service.category_id" persistent-hint required v-validate="'required'" :error-messages="errors.collect('category_id')" name="category_id" data-vv-as="category id"></v-select>
                        <v-text-field label="Catch*" placeholder="Catch(Sell your services to your clients in a short statement)" hint="E.g I will design 100pcs of flier for just 2000 naira" v-model="service.catch" required v-validate="'required|min:5|max:120'" :error-messages="errors.collect('catch')" name="catch" data-vv-as="catch"></v-text-field>
                        <v-text-field label="Name Of Service Company*" placeholder="Name/Title of Service Company" v-model="service.title" required v-validate="'required|min:5|max:200'" :error-messages="errors.collect('title')" name="title" data-vv-as="title/name of service"></v-text-field>
                        <v-text-field label="Your Position In Company*" v-model="service.position" v-validate="'max:100'" :error-messages="errors.collect('position')" name="position"></v-text-field>
                        <v-textarea label="Description Of Service*" placeholder="Give details of the service you offer" rows="2" auto-grow v-model="service.description" required v-validate="'required|min:10|max:600'" :error-messages="errors.collect('description')" name="description"></v-textarea>
                        <v-text-field label="Experience Years*" v-model="service.experience_years" placeholder="How many years have you been offering this service" required v-validate="'required|numeric'" :error-messages="errors.collect('experience_years')" name="experience_years" data-vv-as="years of experience"></v-text-field>
                        <v-textarea label="Highlights" placeholder="State major achievements, awards and recognitions that could influence a client to choose you" rows="2" auto-grow v-model="service.highlights" v-validate="'min:5|max:255'" :error-messages="errors.collect('highlights')" name="highlights"></v-textarea>
                        <v-textarea label="Keystrength" placeholder="Your advantage over competitors e.g we are the cheapest in the industry" rows="2" auto-grow v-model="service.keystrength" v-validate="'min:5|max:150'" :error-messages="errors.collect('keystrength')" name="keystrength"></v-textarea>
                        <v-textarea label="Address*" rows="2" auto-grow v-model="service.address" v-validate="'required|min:5|max:255'" :error-messages="errors.collect('address')" name="address"></v-textarea>
                        <v-text-field label="City*" v-model="service.city" placeholder="city" required v-validate="'required|max:30'" :error-messages="errors.collect('city')" name="city"></v-text-field>
                        <v-select :items="locations" item-text="state" item-value="id" label="Select State*" v-model="service.location_id" persistent-hint required v-validate="'required'" :error-messages="errors.collect('location_id')" name="location_id" data-vv-as="state"></v-select>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Phone Number*" v-model="service.phone_number" required v-validate="'required|numeric'" :error-messages="errors.collect('phone_number')" name="phone_number" data-vv-as="phone number"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Email Address*" v-model="service.email" required v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Website" v-model="service.website" prefix="http://www."></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Facebook Handle" v-model="service.facebook" prefix="https://www.facebook.com/"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Instagram handle" v-model="service.instagram"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Twitter handle" v-model="service.twitter"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="LinkedIn handle" v-model="service.linkedIn"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Youtube channel" v-model="service.youtube" prefix="https://www.youtube.com/c/"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn class="primary" large @click="submit" width="25%" :loading="isLoading">Submit</v-btn>
                        <v-btn outlined color="primary" class="" large @click="reset" width="25">Reset</v-btn>
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
            categories: [],
            locations: [],
            service: {
                category_id: null,
                title:'',
                position: '',
                description: '',
                experience_years: '',
                highlights: '',
                keystrength: '',
                phone_number: '',
                address: '',
                city: '',
                location_id: null,
                website: '',
                facebook: '',
                instagram: '',
                twitter: '',
                linkedIn: '',
                youtube: '',
                catch: ''
            },
            isLoading: false,
            createFail: false,
            validationError: false
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        authUserService(){
            return this.$store.getters.authUserService
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        }
    },
    beforeRouteEnter (to, from, next) {
        let authService = store.getters.authUserService
        if(authService){
            next('/dashboard')
        }else{
            next()
        }
    },
    methods: {
        getCategories(){
            axios.get(this.api + '/categories').then((res) => {
                this.categories = res.data
            })
        },
        getStates(){
            axios.get(this.api + '/locations').then((res) => {
                this.locations = res.data
            })
        },
        submit(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    axios.post(this.api + '/auth/service/create', {
                        service: this.service
                    }, this.authHeaders).then((res) => {
                        this.isLoading = false
                        this.$store.commit('setService', res.data)
                        this.$store.commit('serviceImgExpected')
                        this.$router.push(`/user/create-service/add-image`)
                    }).catch((err) => {
                        this.isLoading = false
                        if(err.response.status === 422){
                            this.validationError = true
                        }else{
                            this.createFail = true
                        }
                    })
                }
            })
        },
        reset(){}
    },
    mounted() {
        this.getCategories()
        this.getStates()
    },
}
</script>

<style lang="scss" scoped>
    .v-card__title{
        padding-top: 2rem;
    }
    .v-card__text{
        padding: 0 1.5rem 1.5rem !important;
    }
    .v-card__actions{
        padding-bottom: 15px !important;
        .v-btn{
            margin: 0 20px 40px;
        }
    }
</style>
