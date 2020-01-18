<template>
    <section class="varify_email" style="background-image: url(/images/frontend/pagebg.png);">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-7">
                    <div class="section_left">
                        <div class="varify_email__title">
                            <h2>Please verify your email</h2>
                        </div>
                        <div class="varify_email__content">
                            <div class="varify_email__content__inner">
                                <p>In order to proceed with your order, we need to confirm that it’s really you and you want to book this booking with your registered email address.</p>
                                <div class="form-group">
                                    <div class="label">Please enter the 6-digit verification code</div>
                                    <input type="text" class="form-control single_input text-center" v-model="code"  maxlength="6" name="quantity" placeholder="">
                                </div>
                                <div class="form-group form-group__submit">
                                    <input type="button" class="button button__primary button__full" @click="verifyEmail"  value="Continue">
                                </div>
                                <p class="resend-email"><span>Haven’t received the code?</span> <a :href="url">Resend email</a></p>
                            </div><!--verify_inner-->
                        </div><!--verify_section-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props : [
            'token',
            'expiresAt',
            'url'
        ],

        name : "VerifyEmail",

        data() {
            return {
                code      : '',
                errors    : {},
                errordata : 0
            }
        },

        methods : {
            verifyEmail() {
                axios
                    .post(`/email/verify`, {
                        token     : this.token,
                        code      : this.code,
                        expiresAt : this.expiresAt
                    })
                    .then((e) => {
                        window.location.href = '/profile';
                    })
                    .catch((e) => {
                        if(e.response) {
                            if (e.response.status === 422) {
                                this.errors    = e.response.data.errors;
                                this.errordata = 1;
                            } 
                        }
                        
                    })
            },
        }
    }
</script>