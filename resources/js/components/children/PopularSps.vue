<template>
    <div class="featured_sps">
        <div class="section_title">
            Popular Services
        </div>
        <div class="featured_wrap">
            <service-card v-for="serv in servs" :key="serv.id" :serv="serv"></service-card>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            servs: [],
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
        getPopularSps(){
            this.isLoading = true
            axios.get(this.api + '/get_popular_sps').then((res) => {
                this.isLoading = false
                this.servs = res.data
            })
        }
    },
    mounted() {
        this.getPopularSps()
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
        @media screen and (max-width: 960px){
            .featured_wrap{
                display: grid;
                grid-template-columns: 1fr 1fr;
                margin-top: 3rem;
                gap: 2rem;
            }
        }
        @media screen and (max-width: 600px){
            .featured_wrap{
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
        }
    }
</style>

