<template>
    <div class="login_wrapper__inner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login_container">
                        <div class="error_info" v-if="errordata">
                            <h3 v-for="error in errors">{{ error[0] }}</h3>
                        </div>
                        <div class="login_container__block">
                            <div class="login_container__block__title">
                                <h2>Forget Password?</h2>
                                <p>Enter email address and we'll send a link to reset your password</p>
                            </div><!--login_tittle-->
                            <form>
                                <div class="login_container__block__form">
                                    <div class="form-group">
                                        <div class="label label__forgot">Email*</div>
                                        <input type="text" class="form-control single_input3" v-model="email" placeholder="Invade@gmail.com">
                                    </div>

                                    <div class="form-group form-group__submit">
                                        <input type="button" class="button button__primary button__reset" @click="forgetPassword" value="Reset">
                                    </div>

                                </div>
                            </form>
                        </div><!--login_block-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : [
        ],

        name : "ForgetPassword",

        data() {
            return {
                email     : '',
                errors    : {},
                errordata : 0
            }
        },

        methods : {
            forgetPassword() {
                axios
                    .post(`/forget/password`, {
                        email : this.email,
                    })
                    .then((e) => {
                        window.location.href = '/forget/checkEmail';
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