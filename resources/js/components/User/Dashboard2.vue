<template>
    <div class="dashboard">
        <auth-pageheader :title="`Welcome, ${authUser.first_name}`" />
        <!-- <div class="dash_banner">
            <div class="title font-weight-light">
                Welcome, {{ authUser.first_name }}
            </div>
        </div> -->
        <section class="user_info">
            <div class="details">
                <div class="panel_title">Account</div>
                <div class="panel_body">
                    <table class="table table-condensed">
                        <thead></thead>
                        <tbody>
                            <tr>
                                <th style="border-top: none" width="25%">Name</th>
                                <td style="border-top: none">{{ authUser.fullname }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ authUser.email }}</td>
                            </tr>
                            <tr>
                                <th>Phone No:</th>
                                <td>{{ authUser.phone }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel_action">
                    <v-btn text color="blue darken-1" :to="{name: 'UserAccount'}">Go To Profile</v-btn>
                    <v-btn text color="blue darken-1"><i class="uil uil-edit"></i></v-btn>
                    <!-- <router-link to="/profile">Go To Profile</router-link> -->
                </div>
            </div>
            <v-skeleton-loader type="article" v-if="isLoading" elevation="12" min-width="50" min-height="50"></v-skeleton-loader>
            <div class="service" v-else>
                <div class="panel_title">Services</div>
                <v-spacer></v-spacer>
                <div class="panel_body">
                    <div v-if="authUserService">{{ authUserService }}</div>
                    <div v-else>
                        <div class="warning--text no_service">There is no service set up for your account.</div>
                        <div class="set_up">
                            <v-btn text color="primary" :to="{name: 'CreateService'}">Create Service</v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            // service: null
        }
    },
    computed: {
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        authUserService(){
            return this.$store.getters.authUserService
        }
    },
    methods: {
        // getService(){
        //     this.isLoading = true
        //     axios.get(this.api + '/auth/get_auth_service', this.authHeaders).then((res) => {
        //         this.isLoading = false
        //         this.service = res.data
        //         console.log(res.data)
        //     })
        // }
    },
    mounted() {
        // this.getService()
    },
}

</script>
<style lang="scss" scoped>
.dashboard{
    margin: 3rem auto 0;
    width: 100vw;
    height: 100%;
    box-sizing: border-box;
    // background: #e4ebf1;
    background: #f3f2f1;
    .dash_banner{
        margin: 5rem auto 1.5rem;
        width: 60%;
        background: linear-gradient(135deg, #0e4d76, #10527e, #0d4060, #0c3c5a);
        border: none;
        box-shadow: 0 3px 3px rgba(rgb(32, 4, 16), 0.18);
        padding: .8rem 1.5rem;
        color: #fff;
        border-radius: 12px;

        .title{
            font-size: 1.3rem !important;
            text-align: center;
            font-weight: 400  !important;

        }
    }
    .user_info{
        width: 68%;
        margin: 1rem auto;
        padding: 1.5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        height: 100%;
        // & > div{
        //     border: 1px solid #dce1e5;
        //     background: #fff;
        //     border-radius: 8px;
        //     padding: .8rem;
        //     transition: all .4s ease;
        //     cursor: pointer;

        //     &:hover{
        //         box-shadow: 0 4px 4px 2px rgba(0,0,0,0.22);
        //     }
        // }
        .details{
            min-height: 1rem;
            border: 1px solid #dce1e5;
            background: #fff;
            border-radius: 8px;
        }
    }
}
.panel_title{
    text-align: left;
    font-size: 1.1rem;
    color: #2e2e2e;
    padding: .5rem .8rem 0.5rem;
    border-bottom: 1px solid #dadada;
}
.panel_body{
    margin-top: .5rem;

    .no_service{
        margin: .8rem .6rem 1.2rem;
    }
    .set_up{
        text-align: center;
    }
}
.panel_action{
    margin: 1rem auto 1.5rem;
    text-align: center;

    .v-btn i{
        font-size: .8rem;
        color: #10527e;
    }
}
a{
    text-decoration: none !important;
}
</style>
