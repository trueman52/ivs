<template>
    <section class="profile_wrapper">
        <div class="container">
            <div class="row">
                <div class="offset-lg-3 col-lg-3">
                    <div class="error_info" v-if="errordata">
                        <h3 v-for="error in errors">{{ error[0] }}</h3>
                    </div>
                    <div class="profile_wrapper__changepassword">
                        <h2>Change Password</h2>
                        <form>
                            <div class="form-group">
                                <div class="label label__account">Old Password</div>
                                <input type="password" class="form-control" placeholder="••••••••" v-model="old_password" >
                            </div>

                            <div class="form-group">
                                <div class="label label__account">New Password</div>
                                <input type="password" class="form-control" placeholder="••••••••" v-model="password">
                            </div>
                            <div class="form-group">
                                <div class="label label__account">Confirm Password</div>
                                <input type="password" class="form-control" placeholder="••••••••" v-model="password_confirmation">
                            </div>

                            <div class="form-group form-group__submit">
                                <input type="button" class="button button__primary button__reset" @click="forgetPassword" value="Reset">
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
        ],

        name : "Account",

        data() {
            return {
                old_password          : '',
                password              : '',
                password_confirmation : '',
                errors                : {},
                errordata             : 0
            }
        },

        methods : {
            forgetPassword() {
                axios
                    .put(`/account`, {
                        old_password          : this.old_password,
                        password              : this.password,
                        password_confirmation : this.password_confirmation,
                    })
                    .then((e) => {
                        window.location.href = '/account';
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