<template>
    <div class="login_wrapper__inner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login_container">
                        <div class="login_container__block">
                            <div class="login_container__block__title">
                                <h2>Check Your Mail</h2>
                                <p>We just emailed you with the instructions to reset your password.</p>
                            </div><!--login_tittle-->
                            <div class="login_container__block__form">
                                <div class="form-group form-group__submit">
                                    <a :href="url" class="button button__primary button__reset">Back to Home</a>
                                </div>
                            </div>
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
            'url'
        ],

        name : "ForgetPasswordCheckEmail",
    }
</script>