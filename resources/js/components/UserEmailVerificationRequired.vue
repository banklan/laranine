<template>
    <v-container>
        <v-row justify="center" align="center" class="mt-5">
            <v-col cols="12" md="10">
                <div class="wrap">
                    <div class="subj font-weight-normal mt-3">
                        Email Verification Required!
                    </div>
                    <div class="svg">
                        <img src="/assets/svgs/verify_email.svg" alt="">
                    </div>
                    <div class="sub mt-5">
                        Thank you for signing up at hozumarts.
                    </div>
                    <div class="body">
                        <p>A verification email has been sent to your email address <strong>{{ email }}</strong></p>
                        <!-- <p>A verification email has been sent to your email address <strong>flexinalbo14@gmail.com</strong></p> -->
                        <p>Kindly click on the link in the email to activate your account.</p>
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
            email: this.$route.query.q
        }
    },
}
</script>
<style lang="scss" scoped>
    .wrap{
        margin: 0 auto;
        text-align: center;
        padding: 3rem 1.5rem;

        .subj{
            background: #baefed;
            padding: 1.5rem;
            font-size: 1.3rem;
            border: none;
            border:1px solid #baefed;
            box-shadow: 0 2px 2px rgba(0,0,0,0.12);
        }
        .svg{
            padding: 1.5rem 2rem;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            img{
                height: 10rem;
            }
        }
        .sub{
            font-size: 1.2rem;
            color: #2e2e2e;
        }
        .body{
            margin-top: 2rem;
            font-size: 1.2rem;
        }
    }

</style>
