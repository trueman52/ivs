<template>
    <section class="profile_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="sidebar">
                        <ul>
                            <li class="active"><a href="/profile">Company <br/>Details</a></li>
                            <li><a href="/business">Business <br/>Information</a></li>
                            <li><a href="/bank">Bank <br/>Account</a></li>
                        </ul>
                    </div><!--left_side_menu-->
                </div>
                
                <div class="col-lg-6">
                    <div class="customer_info">
                        <div class="error_info" v-if="errordata">
                            <h3 v-for="error in errors">{{ error[0] }}</h3>
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="customer_info__title">
                                        <h3>Customer Details</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">First Name</div>
                                        <input type="text" class="form-control" v-model="user.firstName" placeholder="Frankie">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Last Name</div>
                                        <input type="text" class="form-control" v-model="user.lastName" placeholder="Apple">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Phone number*</div>
                                        <div class="phone_block">
                                            <input type="text" class="form-control code_number" v-model="user.profile.countryCode" placeholder="65">
                                            <input type="text" class="form-control phone_number" v-model="user.profile.phoneNumber" placeholder="6777 8800">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Email</div>
                                        <input type="text" class="form-control" v-model="user.email" disabled placeholder="frankieapple@applesauce.com">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Country</div>
                                        <div class="selectbox">
                                            <select v-model="user.profile.address.country" v-chosen="user.profile.address.country" class="option-select" tabindex="1">
                                                <option v-for="(country, key) in countries" :value="key">{{country}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Address</div>
                                        <input type="text" class="form-control" v-model="user.profile.address.street" placeholder="Blk 300, Clementi Ave 6, 01-56">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Postal code</div>
                                        <input type="text" class="form-control" v-model="user.profile.address.postalCode" placeholder="745600">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Company (Optional)</div>
                                        <input type="text" class="form-control" v-model="user.profile.companyName" placeholder="Living Zombies Pte Ltd">
                                    </div>
                                </div>

                            </div>

                            <div class="row row20">
                                <div class="col-md-12">
                                    <div class="devider"></div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-12">
                                    <div class="customer_info__title">
                                        <h3>Billing Address</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">First Name</div>
                                        <input type="text" class="form-control" v-model="user.billing.firstName" placeholder="Frankie">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Last Name</div>
                                        <input type="text" class="form-control" v-model="user.billing.lastName" placeholder="Apple">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Phone number*</div>
                                        <div class="phone_block">
                                            <input type="text" class="form-control code_number" v-model="user.billing.countryCode" placeholder="65">
                                            <input type="text" class="form-control phone_number" v-model="user.billing.phoneNumber" placeholder="6777 8800">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Email</div>
                                        <input type="text" class="form-control" v-model="user.billing.email" placeholder="frankieapple@applesauce.com">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Country</div>
                                        <div class="selectbox">
                                            <select v-model="user.billing.address.country" v-chosen="user.billing.address.country" class="option-select" tabindex="1">
                                                <option v-for="(country, key) in countries" :value="key">{{country}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Address</div>
                                        <input type="text" class="form-control" v-model="user.billing.address.street" placeholder="Blk 300, Clementi Ave 6, 01-56">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">City (Optional)</div>
                                        <input type="text" class="form-control" v-model="user.billing.address.city" placeholder="Kuala Lumpur">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">State (Optional)</div>
                                        <input type="text" class="form-control" v-model="user.billing.address.state" placeholder="Kembangan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="label">Postal code</div>
                                        <input type="text" class="form-control" v-model="user.billing.address.postalCode" placeholder="745600">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="label">Company (Optional)</div>
                                        <input type="text" class="form-control" v-model="user.billing.companyName" placeholder="Living Zombies Pte Ltd">
                                    </div>
                                </div>
                            </div>

                            <div class="row row20">
                                <div class="col-md-12">
                                    <div class="form-group submit_group">
                                        <input type="button" class="button button__primary button__full" @click="profile" value="Save">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script>
    import chosen from '../../../public/js/frontend/chosen.jquery.js';

    Vue.directive('chosen', {
        inserted: function(el, binding, vnode) {
            $(el).chosen().change(function(event, change) {
                if (Array.isArray(binding.value)) {
                    var selected = binding.value;
                    if (change.hasOwnProperty('selected')) {
                        selected.push(change.selected);
                    } else {
                        selected.splice(selected.indexOf(change.deselected), 1);
                    }
                } else {
                    var keys = binding.expression.split('.');
                    var pointer = vnode.context;
                    while (keys.length > 1)
                        pointer = pointer[keys.shift()];
                    pointer[keys[0]] = change.selected;
                }
            });
        },
        componentUpdated: function(el, binding) {
            $(el).trigger("chosen:updated");
        }
    });

    export default {
        props : [
            'countries',
        ],

        name : "Profile",

        data() {
            return {
                errors    : {},
                errordata : 0,
                user      : [],
            }
        },

        mounted() {
          this.getData();
        },

        methods : {
            profile() {
                
                var data = {
                  'user': {
                    'firstName'  : this.user.firstName,
                    'lastName'   : this.user.lastName,
                  },
                  'profile': {
                    'code'       : this.user.profile.countryCode,
                    'number'     : this.user.profile.phoneNumber,
                    'companyName': this.user.profile.companyName,
                  },
                  'profileAddress': {
                    'country'    : this.user.profile.address.country,
                    'street'     : this.user.profile.address.street,
                    'postalCode' : this.user.profile.address.postalCode,
                  },
                  'billingDetail': {
                    'firstName'  : this.user.billing.firstName,
                    'lastName'   : this.user.billing.lastName,
                    'email'      : this.user.billing.email,
                    'code'       : this.user.billing.countryCode,
                    'number'     : this.user.billing.phoneNumber,
                    'companyName': this.user.billing.companyName,
                  },
                  'billingDetailAddress': {
                    'country'    : this.user.billing.address.country,
                    'street'     : this.user.billing.address.street,
                    'city'       : this.user.billing.address.city,
                    'state'      : this.user.billing.address.state,
                    'postalCode' : this.user.billing.address.postalCode,
                  },
                };
                axios
                    .put(`/web/me`, data)
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
            getData() {
                axios
                .get("/web/me")
                .then(response => {
                    this.user = response.data.user;
                    if(!this.user.profile) {
                        this.user.profile = Object.assign({}, this.user.profile, {
                            countryCode : null,
                            phoneNumber : null,
                            companyName : null,
                        });
                    }
                    if(!this.user.profile.address) {
                        this.user.profile.address = Object.assign({}, this.user.profile.address, {
                            country    : 'SG', 
                            street     : null,
                            postalCode : null,
                        });
                    }
                    if(!this.user.billing) {
                        this.user.billing = Object.assign({}, this.user.billing, {
                            firstName   : null, 
                            lastName    : null,
                            countryCode : null,
                            phoneNumber : null,
                            email       : null,
                            companyName : null,
                        });
                        this.user.billing.address = Object.assign({}, this.user.billing.address, {
                            country    : 'SG', 
                            street     : null,
                            city       : null,
                            state      : null,
                            postalCode : null,
                        });
                    }
                })
                .catch(e => {
                  console.log(e);
                });
            }
        }
    }
</script>