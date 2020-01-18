<template>
    <section class="account_block">
        <div class="container">
            <div class="row">
                <div class="offset-lg-4 col-lg-4">
                    <div class="account_block__reset">
                        <div class="error_info" v-if="errordata">
                            <h3 v-for="error in errors">{{ error[0] }}</h3>
                        </div>
                        <h2>Reset Password</h2>
                        <form>
                            <input type="hidden" :value="token">
                            <div class="form-group">
                                <div class="label">Email</div>
                                <input type="text" class="form-control" v-model="email" placeholder="frankieapple@applesauce.com"/>
                            </div>
                            <div class="form-group">
                                <div class="label label__account">New Password</div>
                                <input type="password" class="form-control" v-model="password" placeholder="••••••••">
                            </div>

                            <div class="form-group">
                                <div class="label label__account">Confirm Password</div>
                                <input type="password" class="form-control" v-model="password_confirmation" placeholder="••••••••">
                            </div>

                            <div class="form-group form-group__submit">
                                <input type="button" class="button button__primary button__reset" @click="resetPassword"  value="Reset">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props : [
            'token'
        ],

        name : "ResetPassword",

        data() {
            return {
                email                 : '',
                password              : '',
                password_confirmation : '',
                errors                : {},
                errordata             : 0
            }
        },

        methods : {
            resetPassword() {
                axios
                    .post(`/password/reset`, {
                        token                 : this.token,
                        email                 : this.email,
                        password              : this.password,
                        password_confirmation : this.password_confirmation,
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