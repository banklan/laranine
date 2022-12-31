<template>
    <div class="featured_sps">
        <div class="section_title">
            Featured Services
        </div>
        <div class="featured_wrap">
            <service-card v-for="feat in feats" :key="feat.id" :serv="feat"></service-card>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            feats: [],
            isLoading: false,
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
    },
    methods: {
        getFeatSps(){
            this.isLoading = true
            axios.get(this.api + '/get_featured_sps').then((res) => {
                this.isLoading = false
                this.feats = res.data
            })
        }
    },
    mounted() {
        this.getFeatSps()
    },
}
</script>

<style lang="scss" scoped>
    .featured_sps{
        min-height: 32rem;
        margin: 0 auto;
        padding: 1.2rem 1rem;
        width: 100vw;
        .featured_wrap {
            margin: 0 auto;
            margin-top: -2rem;
            width: 90%;
            background: #fff;
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 2.5rem;
            padding: 2rem 1rem;
        }
    }
</style>
