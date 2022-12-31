<template>
    <v-card light elevation="4" min-height="400" class="mt-5">
        <v-img v-if="serv" :src="serv.image" alt="Featured Images" aspect-ratio="1" height="250" transition="scale-transition"></v-img>
        <v-img v-else src="/assets/shared/no-image.png" alt="Featured Images" aspect-ratio="1" height="250" transition="scale-transition"></v-img>
        <v-card-title class="subtitle-1 justify-center mt-2 text-truncate">{{ serv.title | capFirstLetter}}</v-card-title>
        <v-card-text class="text-center body-1 grey--text darken-4 mt-n3">{{ serv.location.state }} | {{ serv && serv.category.name }} </v-card-text>
        <v-card-actions class="justify-space-around mt-n2">
            <v-chip v-if="serv.is_approved" class="ma-2" color="#045a9f" text-color="white">
                <v-avatar left><i class="uil uil-check-circle"></i></v-avatar>
                <!-- V -->
            </v-chip>
            <v-rating v-model="serv.ratings" readonly dense color="primary_variant"></v-rating>
        </v-card-actions>
        <v-card-actions class="mt-3 mb-0 px-3 primary white--text py-3 align-center">
            <v-avatar>
                <v-img v-if="serv" :src="serv.user.picture" height="50" alt="Author"></v-img>
                <v-img v-else src="/assets/shared/user7.jpg" height="50" alt="AUthor"></v-img>
            </v-avatar>
            <div class="ml-3">{{ serv && serv.user.fullname }}</div>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    props: ['serv'],
}
</script>

<style lang="scss" scoped>
    .v-card{
        width: 100%;
        cursor: pointer;
        transition: all .4s ease !important;

        &:hover{
            box-shadow: 0 5px 5px 8px rgba(0, 0, 0, .25) !important;
        }

        i{
            font-size: 1.5rem !important;
        }
    }
</style>
