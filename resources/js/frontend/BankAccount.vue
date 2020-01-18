<template>
    <section class="profile_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="sidebar">
                        <ul>
                            <li><a href="/profile">Company <br/>Details</a></li>
                            <li><a href="/business">Business <br/>Information</a></li>
                            <li class="active"><a href="/bank">Bank <br/>Account</a></li>
                        </ul>
                    </div><!--left_side_menu-->
                </div>
                
                <div class="col-lg-6">
                    <div class="bankprofile" v-if="bank">
                        <div class="bankprofile__title">
                            <h2>Verify your bank account</h2>
                            <p>Your bank account details are used in issuing security deposit or payment refunds to you. <a href="#">Find out more</a></p>
                        </div>
                        <div class="bankprofile__content">
                            <h3>{{ bank.bankName }}</h3>
                            <h4>{{ bank.accountType }} {{ bank.accountNumber }}</h4>
                            <p>{{ bank.holderName }}</p>
                            <a href="javascript:void(0)" @click="removeBank" class="remove_btn2"><img @click="removeBank" src="/images/frontend/garbage-bin-4059C1.svg" alt=""></a>
                        </div><!--bank_single-->
                    </div>

                    <div class="bankprofile" v-else>
                        <div class="bankprofile__title">
                            <h2>Verify your bank account</h2>
                            <p>Your bank account details are used in issuing security deposit or payment refunds to you. <a href="#">Find out more</a></p>
                        </div>

                        <div class="bankprofile__addaccount">
                            <a href="/bank/create" class="button button__primary button__addaccount">Add a Bank Account</a>
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
                bank : [],
            }
        },

        mounted() {
          this.getData();
        },

        methods : {
            removeBank() {
                axios
                    .delete(`/web/my-bank`)
                    .then((e) => {
                        window.location.href = '/bank';
                    })
                    .catch((e) => {
                        console.log(e);
                    })
            },

            getData() {
                axios
                .get("/web/my-bank")
                .then(response => {
                    this.bank = response.data.bank;
                })
                .catch(e => {
                  console.log(e);
                });
            },
        }
    }
</script>