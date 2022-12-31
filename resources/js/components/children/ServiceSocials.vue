<template>
    <div class="service_contacts mt-8">
        <v-card light min-height="200" elevation="12" raised class="mx-auto">
            <v-card-title class="justify-center subtitle-1 primary white--text">Socials</v-card-title>
            <v-card-text color="dark_text--text">
                <table class="table table-condensed table-borderless mt-3">
                    <thead></thead>
                    <tbody>
                        <tr v-if="service.website">
                            <th width="10%"><i class="uil uil-globe"></i></th>
                            <td><a class="text-lowercase" :href="`www.${service.website }`" target="_blank">www.{{ service.website }}</a></td>
                        </tr>
                        <tr v-if="service.facebook">
                            <th><i class="uit uil-facebook-f"></i></th>
                            <td><a class="text-lowercase" :href="`https://facebook.com/${service.facebook}`" target="_blank">https://www.facebook.com/{{ service.facebook }}</a></td>
                        </tr>
                        <tr v-if="service.twitter">
                            <th><i class="uit uil-twitter-alt"></i></th>
                            <td><a class="text-lowercase" :href="`https://twitter.com/${service.twitter}`" target="_blank">https://www.twitter.com/{{ service.twitter }}</a></td>
                        </tr>
                        <tr v-if="service.instagram">
                            <th><i class="uil uil-instagram"></i></th>
                            <td><a class="text-lowercase" :href="`https://instagram.com/${service.instagram}`" target="_blank">https://www.instagram.com/{{ service.instagram }}</a></td>
                        </tr>
                        <tr v-if="service.linkedin">
                            <th><i class="uil uil-linkedin-alt"></i></th>
                            <td><a class="text-lowercase" :href="`https://linkedin.com/${service.linkedin}`" target="_blank">https://www.linkedin.com/{{ service.linkedin }}</a></td>
                        </tr>
                        <tr v-if="service.youtube">
                            <th><i class="uil uil-youtube"></i></th>
                            <td><a class="text-lowercase" :href="`https://twitter.com/${service.youtube}`" target="_blank">https://www.youtube.com/{{ service.youtube }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    props: ['service']
}
</script>

<style lang="scss" scoped>
 .v-card{
    .v-card__text{
        font-size: .9rem;
        color: #2b2b2b;

        i{
            color: #99154E;
            font-size: 1rem;
        }
     }

 }
 table tr{
    line-height: 1rem;
    & > th{
        white-space: nowrap !important;
    }
    td {
        a{
            color: #2b2b2b;
            text-decoration: none;
        }
    }
 }

</style>
