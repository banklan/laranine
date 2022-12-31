<template>
    <div class="service_mover">
        <v-card color="#99154E" dark min-height="100" elevation="3" raised class="mx-auto">
            <v-card-text>
                <div class="pf text-center subtitle-1">Prime Mover</div>
                <template v-if="service">
                    <router-link class="user" :to="{name: 'UserProfile'}">
                        <v-avatar>
                            <v-img v-if="authUser.picture" :src="authUser.picture" contain height="100"></v-img>
                            <v-img v-else src="/assets/shared/avatar.jpg" contain height="100"></v-img>
                        </v-avatar>
                        <div class="identity">
                            <div class="name">{{ service.user && service.user.fullname }}</div>
                            <div class="position">{{ service.user && service.position}}</div>
                        </div>
                    </router-link>
                    <table class="table table-borderless table-condensed mt-3">
                        <thead></thead>
                        <tbody style="color: white">
                            <tr >
                                <th width="15%"><i class="uil uil-phone"></i></th>
                                <td>{{ service.phone }}</td>
                            </tr>
                            <tr>
                                <th><i class="uil uil-envelope-alt"></i></th>
                                <td>{{ service.email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template v-else>
                    <div class="text-center pb-6">
                        <p class="white--text">You have not created a service for your account. </p>
                    </div>
                </template>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['service'],
    computed:{
        authUser(){
            return this.$store.getters.authUser
        }
    }
}
</script>

<style lang="scss" scoped>
 .v-card{
    .v-card__text{
         color: #fff;
         font-size: .9rem;

         .pf{
             padding: 7px 5px 10px;
         }
     }
    .user{
        display: flex;
        align-items: center;
        gap: 1rem;
        .identity{
            display: flex;
            flex-direction: column;
            justify-content: center;
            .name{
                font-size: 1rem;
                color: #fff;
            }
            .position{
                font-size: .85rem;
                font-style: italic;
                color: #e4e4e4
            }
        }
    }
    .infom{
        display: flex;
        gap: 1rem;
        margin: .8rem auto;
    }
 }
 table tr{
    line-height: 1rem;
    & > th{
        white-space: nowrap !important;
    }
 }
</style>
