<template>
    <div class="welcome">
        <welcome-banner />
        <welcome-cards />
        <featured-sps />
        <popular-cats />
        <popular-sps />
    </div>
</template>


<script>
export default {
    data() {
        return {
            scrolled: false
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
    .welcome{
        padding: 0;
        margin: 0;
        background: #fff;
        box-sizing: border-box;
    }
    .window-scroll{
        background: #fff;
    }
</style>
