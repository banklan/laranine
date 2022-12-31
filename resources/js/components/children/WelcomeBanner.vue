<template>
    <div class="welcome_banner">
        <div class="banner_content">
            <div class="caption">
                <h1 class="animate__animated animate__fadeInDown">List your services, get verified and get hired!</h1>
                <p class="animate__animated animate__bounceInRight">Find freelance professionals, artisans and service providers that you can trust within your neighbourhood..</p>
            </div>
            <div class="banner_cta" :style="$vuetify.breakpoint.smAndDown ? 'width:80%' : 'width: 60%'">
                <input type="text" v-model="q" placeholder="Search e.g Tailor" append="mdi-search">
                <button>Go!</button>
                <!-- <v-btn class="mt-5" elevation="12" dark color="sec white--text" x-large >View Listings</v-btn> -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            q: ''
        }
    },
}
</script>

<style lang="scss" scoped>
    .animate__animated.animate__fadeInDown{
        --animate-duration: 1s;
    }
    .animate__animated.animate__bounceInRight{
        --animate-duration: 2s;
    }
</style>
