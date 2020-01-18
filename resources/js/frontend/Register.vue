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
                    </div>
                    <div class="login">
                        <div class="login__tittle">
                            <h2>Sign Up</h2>
                            <p>You need to have an account before you can check out. Please complete the form below.</p>
                        </div><!--login_tittle-->
                        <div class="login__social">
                            <ul>
                                <li class="login__social__fb"><a href="/social/facebook">Log in with Facebook</a></li>
                                <li class="login__social__google"><a href="/social/google">Log in with Google</a></li>
                            </ul>
                        </div><!--social_login-->
                        <div class="login__mid">
                            <h3>Or sign up with email</h3>
                        </div>
                        <div class="login__form sign-up">
                            <form>
                                <div class="row row10">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="label">First Name</div>
                                            <input type="text" class="form-control" v-model="firstName" placeholder="Frankie"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="label">Last Name</div>
                                            <input type="text" class="form-control" v-model="lastName" placeholder="Apple"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="label">Email*</div>

                                    <input type="text" class="form-control" v-model="email" placeholder="frankieapple@applesauce.com"/>

                                </div><!--form_single-->
                                <div class="form-group">
                                    <div class="label">Password*</div>

                                    <input type="password" class="form-control" v-model="password" placeholder="••••••••••••••"/>

                                </div><!--form_single-->

                                <div class="form-group">
                                    <div class="label">Phone number*</div>
                                    <div class="phone_block">
                                        <input type="text" class="form-control code_number" v-model="code" placeholder="65">
                                        <input type="text" class="form-control phone_number" v-model="number" placeholder="6777 8800  ">
                                    </div>
                                </div>

                                <div class="form-group submit_group">
                                    <div class="sn_info">By clicking Sign Up, I agree to the <a href="#">Terms of Use</a>.</div>
                                    <input type="button" class="button button__primary button__submit_next" @click="register" value="Sign Up">

                                </div><!--form_single-->
                            </form>
                            <div class="form_bottom">
                                <p>Already have an account? <a href="/login">Log In</a></p>
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
            'logoImage'
        ],

        name : "SignUp",

        data() {
            return {
                firstName : '',
                lastName  : '',
                email      : '',
                password   : '',
                code       : '',
                number     : '',
                errors     : {},
                errordata  : '',
            }
        },

        methods : {
            register() {
                axios
                    .post(`/register`, {
                        firstName : this.firstName,
                        lastName  : this.lastName,
                        email     : this.email,
                        password  : this.password,
                        code      : this.code,
                        number    : this.number,
                    })
                    .then((e) => {
                        window.location.href = '/login';
                    })
                    .catch((e) => {
                        if (e.response.status === 422) {
                            this.errors    = e.response.data.errors;
                            this.errordata = 1;
                        }
                    })
            },
        }
    }
</script>