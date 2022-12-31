<template>
    <div class="sec_nav hidden-sm-and-down" v-if="pgScrollPosition > 50">
        <div class="sec_nav_wrap">
            <div class="title">Categories</div>
            <!-- <div class="body">this is the space holding the categories l;ist items</div> -->
            <div class="cats">
                <router-link class="primary--text" v-for="cat in categories" :key="cat.id" :to="cat.path">{{ cat.title }}</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: [
               {id:1, title:'Cat1', path: '/'},
               {id:2, title:'Cat2', path: '/'},
               {id:3, title:'Cat3', path: '/'},
               {id:4, title:'Cat4', path: '/'},
               {id:5, title:'Cat5', path: '/'},
            ]
        }
    },
    computed:{
        pgScrollPosition(){
            return this.$store.getters.pgScrollPosition
        }
    }
}
</script>
<style lang="scss" scoped>
    .sec_nav{
        margin-top: 60px;
        width: 100vw;
        background: #fff;
        z-index: 100;
        position: fixed;
        border: 1px solid #fff;
        border-top: 0;
        box-shadow: 0 2px 5px rgba(153,21,78,.2);
        padding: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        // height: 4rem;

        .sec_nav_wrap{
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            // height: 100%;
            margin: 0 auto;

            .cats{
                margin: 0 auto;
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: space-around;
                align-items: center;
                padding: 5px 10px;
            }
        }
    }
    a{
        text-decoration: none;
    }
</style>