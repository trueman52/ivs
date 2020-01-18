<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="login_wrapper__top"><a href="/spaces"><img :src="logoImage" alt=""/></a></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="login_container">
                    <div class="error_info" v-if="errordata">
                        <h3 v-for="error in errors">{{ error[0] }}</h3>
                        <h3 v-if="loginerror">{{ loginerror }}</h3>
                        <p v-if="logintoken"><a :href="logintoken">Resend Verification email</a></p>
                    </div>
                    <div class="login">
                        <div class="login__tittle">
                            <h2>Log In</h2>
                            <p>Welcome back!</p>
                        </div><!--login_tittle-->
                        <div class="login__social">
                            <ul>
                                <li class="login__social__fb"><a href="/social/facebook">Log in with Facebook</a></li>
                                <li class="login__social__google"><a href="/social/google">Log in with Google</a></li>
                            </ul>
                        </div><!--social_login-->
                        <div class="login__mid">
                            <h3>Or log in with email</h3>
                        </div>
                        <div class="login__form">
                            <form>
                                <div class="form-group">
                                    <div class="label">Email*</div>

                                    <input type="text" class="form-control" v-model="email" placeholder="frankieapple@applesauce.com"/>

                                </div><!--form_single-->
                                <div class="form-group">
                                    <div class="label">Password*</div>

                                    <input type="password" class="form-control" v-model="password" placeholder="••••••••••••••"/>

                                </div><!--form_single-->

                                <div class="form-group submit_group">
                                    <div class="form_half float-right"><a href="/forget/password">Forgot password?</a></div>
                                    <div class="form_half"><input type="button" class="button button__primary button__submit_next" @click="login" value="Log in"></div>
                                </div><!--form_single-->
                            </form>
                            <div class="form_bottom">
                                <p>Don’t have an account? <a href="/register" class="btn-link btn-link__account">Create an account</a></p>
                            </div><!--form_single-->
                        </div>
                    </div><!--login_block-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : [
            'logoImage',
            'redirectUrl',
        ],

        name : "Login",

        data() {
            return {
                email      : '',
                password   : '',
                errors     : {},
                errordata  : 0,
                loginerror : '',
                logintoken : '',
            }
        },

        methods : {
            login() {
                axios
                    .post(`/login`, {
                        email    : this.email,
                        password : this.password,
                    })
                    .then((e) => {
                        if (e.data.status === 'error') {
                            this.loginerror = e.data.errors;
                            this.logintoken = e.data.token;
                            this.errordata = 1;
                        } else {
                            window.location.href = this.redirectUrl;
                        }
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