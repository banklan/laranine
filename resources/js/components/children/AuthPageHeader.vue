<template>
    <div class="page_header">
        <!-- <span v-if="back"><v-btn text dark :to="{name: 'UserAccount'}"><i class="uil uil-arrow-left"></i></v-btn></span> -->
        <div v-if="back"><router-link :to="{name: `${back}`}"><i class="uil uil-arrow-left"></i></router-link></div>
        <h3>{{ title }}</h3>
    </div>
</template>

<script>
export default{
    props: ['title', 'back']
}
</script>

<style lang="scss" scoped>
    .page_header{
        width: 50%;
        margin: 2rem auto 0;
        background: linear-gradient(135deg, rgb(141 19 71), rgb(163 4 72 / 90%), rgb(165 8 76 / 92%));
        box-shadow: 0 3px 3px 2px rgba(0, 0, 0, .15);
        border-radius:4px;
        color: #fff;
        text-align: center;
        display: flex;
        align-items: center;
        padding: .8rem 1.2rem;

        h3{
            color: #fff;
            font-size: 1.1rem;
            line-height: 1.5;
            margin: 0 auto;
        }

        a{
            color: #fff;
            justify-self: flex-start;
            transition: all .4s ease;
            i{
                color: #fff;
                font-size: 1.1rem;
            }
            // &:hover{
            //     margin-left: -15px;
            //     h3{
            //         margin-left: 0;
            //     }
            // }
        }
    }
</style>
