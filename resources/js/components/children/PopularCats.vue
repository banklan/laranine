<template>
    <div class="popular_cats">
        <div class="section_title">
            Popular Categories
        </div>
        <div class="popular_wrap">
            <div v-for="cat in cats" :key="cat.id" class="cat">
                {{ cat.name }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            cats: []
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getCats(){
            this.isLoading = true
            axios.get(this.api + '/get_popular_cats/').then((res) => {
                this.isLoading = false
                this.cats = res.data
            })
        }
    },
    mounted() {
        this.getCats()
    },
}
</script>

<style lang="scss" scoped>
    .popular_cats{
        // min-height: 32rem;
        margin: 0 auto;
        padding: 1.2rem 1rem 2.6rem;
        width: 100vw;
        .popular_wrap {
            margin: 0 auto;
            width: 75%;
            background: #fff;
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 1.5rem;
            // padding: 2rem 1rem;
            .cat{
                border: 1px solid #99154E;
                background: #fefefe;
                color: #99154E;
                border-radius: 5px;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: .8rem .5rem;
                margin: 0 auto;
                transition: all .4s ease;
                cursor: pointer;

                &:hover{
                    background: #700031;
                    color: #fff;
                    box-shadow: 0 3px 4px rgba(0,0,0,.65);
                    font-weight: 400;
                }
            }
        }
        @media screen and (max-width: 960px){
            .popular_wrap{
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 2rem;
            }
        }
        @media screen and (max-width: 728px){
            .popular_wrap{
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }
        @media screen and (max-width: 600px){
            .popular_wrap{
                display: grid;
                grid-template-columns: 1fr;
            }
        }
    }
</style>


