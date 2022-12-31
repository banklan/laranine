<template>
    <v-container>
        <v-row justify="center" class="profile_pg">
            <v-col cols="12" md="6">
                <v-skeleton-loader type="card" v-if="isLoading" elevation="12" min-width="50" min-height="400"></v-skeleton-loader>
                <div class="profile_card" v-else>
                    <div class="prof_avatar">
                        <img :src="authUser.picture" height="200">
                        <v-img v-if="authUser.picture" :scr="authUser.picture" alt="avatar"></v-img>
                        <v-img v-if="authUser.picture" height="250" :scr="`/assets/profile/${authUser.picture}`" alt="user-picture"></v-img>
                    </div>
                    <div class="contents">
                        <!-- <h1>{{ authUser.fullname }}</h1>
                        <h1>{{authUser.service }}</h1>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quo cum esse repudiandae tempora earum! Necessitatibus, aliquam! A porro non optio obcaecati! Inventore nihil eius ea vitae, alias cum recusandae facilis deleniti laudantium, doloremque accusamus officia voluptate veniam quas eveniet magnam nam rem. Aut impedit, sed quibusdam aliquid consequatur ratione odio. -->
                    </div>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
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
        authHeaders(){
            let conf = {
                headers: {
                    Authorization: `Bearer ${this.authUser.token}`,
                },
            }
            return conf
        },
    }
}
</script>

<style lang="scss" scoped>
    .profile_pg{
        margin-top: 6rem;

        .profile_card{
            border: 1px solid #fff;
            box-shadow: 0 2px 2px 1px rgba(0,0,0,.13);
            padding: 0 1.2rem;
            background: #fff;
            box-sizing: border-box;
            margin: 0 auto;

            .prof_avatar{
                margin: 0 auto;
                height: 15rem;
                width: 15rem;
                border: 1px solid #000;
                border-radius: 6px;
                .v-image{
                    border-radius: 50%;
                    width: 15rem;
                    aspect-ratio: 1/1;
                }
            }
        }
    }
</style>
