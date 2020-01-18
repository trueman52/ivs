<template>
    <section class="profile_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-md-block">
                    <div class="sidebar">
                        <ul>
                            <li><a href="/profile">Company <br/>Details</a></li>
                            <li><a href="/business">Business <br/>Information</a></li>
                            <li class="active"><a href="/bank">Bank <br/>Account</a></li>
                        </ul>
                    </div><!--left_side_menu-->
                </div>
                
                <div class="col-lg-6">
                    <div class="bankprofile">
                        <div class="bankprofile__title">
                            <h2>Verify your bank account</h2>
                            <p>Your bank account details are used in issuing security deposit or payment refunds to you. <a href="#">Find out more</a></p>
                        </div>
                        <div class="error_info" v-if="errordata">
                            <h3 v-for="error in errors">{{ error[0] }}</h3>
                        </div>
                        <div class="bankprofile__addbank">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation"><a href="#binsingpore" class="active" role="tab" data-toggle="tab"><span class="d-none d-md-block">BANKS IN SINGAPORE</span><span class="d-block d-md-none">SINGAPORE</span></a></li>
                                <li role="presentation"><a href="#binothers" role="tab" data-toggle="tab"><span class="d-none d-md-block">BANKS IN OTHER COUNTRIES</span><span class="d-block d-md-none">INTERNATIONAL BANKS</span></a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="binsingpore">
                                    <div class="form-group">
                                        <div class="label">Bank Name *</div>
                                        <input type="text" class="form-control" v-model="bank.bankName" placeholder="DBS">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Bank Code*</div>
                                        <input type="text" class="form-control" v-model="bank.bankCode" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <ul class="radio-buttons radio-buttons__inline">
                                            <li>
                                                <input type="radio" v-model="bank.accountType" value="Current" id="ac1">
                                                <label for="ac1">Current</label>
                                            </li>
                                            <li>
                                                <input type="radio" v-model="bank.accountType" value="Savings" id="ac2">
                                                <label for="ac2">Savings</label>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Branch Code*</div>
                                        <input type="text" class="form-control" v-model="bank.branchCode" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Account Number*</div>
                                        <input type="text" class="form-control" v-model="bank.accountNumber" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Account Holder Name*</div>
                                        <input type="text" class="form-control" v-model="bank.holderName" placeholder="">
                                    </div>

                                    <div class="form-group form-group__info">
                                        <p>Please make sure your name and bank information match your bank account. You may be charged a penalty fee if transfer won’t go through. Upon saving, the old account (if any) will be replaced this account in our platforms. </p>
                                        <h3>By submitting this form, I agree to the <a href="#">Terms of Use</a>.</h3>
                                    </div>

                                    <div class="form-group submit_group">
                                        <input type="button" class="button button__primary button__agree" @click="bankAccount" value="Agree and Save">
                                    </div>

                                </div>

                                <div class="tab-pane" id="binothers">
                                    <div class="form-group">
                                        <div class="label">Bank Name *</div>
                                        <input type="text" class="form-control" v-model="bank.bankName" placeholder="DBS">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">BIC/Swift Code*</div>
                                        <input type="text" class="form-control" v-model="bank.bankCode" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Account Type*</div>
                                        <input type="text" class="form-control" v-model="bank.accountType" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Branch Code*</div>
                                        <input type="text" class="form-control" v-model="bank.branchCode" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Account Number or IBAN*</div>
                                        <input type="text" class="form-control" v-model="bank.accountNumber" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Account Holder Name*</div>
                                        <input type="text" class="form-control" v-model="bank.holderName" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <div class="label">Address used in Bank Account*</div>
                                        <input type="text" class="form-control" v-model="bank.accountAddress" placeholder="">
                                    </div>

                                    <div class="form-group form-group__info">
                                        <p>Please make sure your name and bank information match your bank account. You may be charged a penalty fee if transfer won’t go through. Upon saving, the old account (if any) will be replaced this account in our platforms. </p>
                                        <h3>By submitting this form, I agree to the <a href="#">Terms of Use</a>.</h3>
                                    </div>

                                    <div class="form-group submit_group">
                                        <input type="button" class="button button__primary button__agree" @click="bankAccount" value="Agree and Save">
                                    </div>

                                </div>
                            </div>
                        </div>
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

        name : "BankAccount",

        data() {
            return {
                errors    : {},
                errordata : 0,
                bank      : [],
            }
        },

        mounted() {
          this.getData();
        },

        methods : {
            bankAccount() {
                axios
                    .post(`/web/my-bank`, {
                        bankName       : this.bank.bankName,
                        bankCode       : this.bank.bankCode,
                        accountType    : this.bank.accountType,
                        accountNumber  : this.bank.accountNumber,
                        branchCode     : this.bank.branchCode,
                        holderName     : this.bank.holderName,
                        location       : this.bank.location,
                        accountAddress : this.bank.accountAddress,
                    })
                    .then((e) => {
                        window.location.href = '/bank';
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
            getData() {
                axios
                .get("/web/my-bank")
                .then(response => {
                    this.bank = response.data.bank;
                    if(!this.bank) {
                        this.bank = Object.assign({}, this.business, {
                            bankName       : null,
                            bankCode       : null,
                            accountType    : null,
                            accountNumber  : null,
                            branchCode     : null,
                            holderName     : null,
                            location        : null,
                            accountAddress : null,
                        });
                    }
                })
                .catch(e => {
                  console.log(e);
                });
            },
        }
    }
</script>