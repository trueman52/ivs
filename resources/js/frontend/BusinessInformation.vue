<template>
    <section class="profile_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="sidebar">
                        <ul>
                            <li><a href="/profile">Company <br/>Details</a></li>
                            <li class="active"><a href="/business">Business <br/>Information</a></li>
                            <li><a href="/bank">Bank <br/>Account</a></li>
                        </ul>
                    </div><!--left_side_menu-->
                </div>
                
                <div class="col-lg-6">
                    <div class="business-information">
                        <div class="business-information__title">
                            <h2>Let us know about your business</h2>
                            <p>We use this data for booking processing and analytics. We would never share it with others users.</p>
                            <p><a href="#">Learn More</a></p>
                        </div>
                        
                        <div class="error_info" v-if="errordata">
                            <h3 v-for="error in errors">{{ error[0] }}</h3>
                        </div>

                        <div class="business-information__content">
                            <div class="form-group">
                                <div class="label">Age of Business <span>*</span></div>
                                <ul class="radio-buttons radio-buttons__text">
                                    <li v-for="(ageSize, index) in ageSizes">
                                        <input type="radio" v-model="business.age" :value="ageSize" :id="index">
                                        <label :for="index">{{ ageSize }}</label>
                                    </li>
                                </ul>

                            </div>

                            <div class="form-group">
                                <div class="label">Revenue Size (in SGD) <span>*</span></div>
                                <ul class="radio-buttons radio-buttons__text">
                                    <li v-for="(revenueSize, index) in revenueSizes">
                                        <input type="radio" v-model="business.revenue" :value="revenueSize" :id="index">
                                        <label :for="index">{{ revenueSize }}</label>
                                    </li>
                                </ul>

                            </div>

                            <div class="form-group">
                                <div class="label">Team Size <span>*</span></div>
                                <ul class="radio-buttons radio-buttons__text">
                                    <li v-for="(teamSize, index) in teamSizes">
                                        <input type="radio" v-model="business.teamSize" :value="teamSize" :id="index">
                                        <label :for="index">{{ teamSize }}</label>
                                    </li>
                                </ul>

                            </div>

                            <div class="form-group">
                                <div class="label">Characteristics of Your Business <span>*</span></div>
                                <ul class="checkbox_item checkbox_item__with-image">
                                    <li v-for="characteristic in characteristics">
                                        <input type="checkbox" v-model="tags" :value="characteristic.id" :id="characteristic.id">
                                        <label :for="characteristic.id">{{ characteristic.name }}</label>
                                    </li>
                                </ul>

                            </div>

                            <div class="form-group">
                                <div class="label">Average Ticket Size Per Item (SGD) <span>*</span></div>
                                <ul class="radio-buttons radio-buttons__text">
                                    <li v-for="(ticketSize, index) in ticketSizes">
                                        <input type="radio" v-model="business.averageTicketSize" :value="ticketSize" :id="index">
                                        <label :for="index">{{ ticketSize }}</label>
                                    </li>
                                </ul>
                            </div>

                            <div class="form-group">
                                <div class="label">Website</div>
                                <input type="text" class="form-control single_input3" v-model="business.website" placeholder="www.yourwebsite.com">
                            </div>

                            <div class="form-group">
                                <div class="label">Facebook</div>
                                <input type="text" class="form-control single_input3" v-model="business.facebook" placeholder="www.facebook.com/yourfacebook">
                            </div>

                            <div class="form-group">
                                <div class="label">Instagram</div>
                                <input type="text" class="form-control single_input3" v-model="business.instagram" placeholder="www.instagram.com/yourinstagram">
                            </div>

                            <div class="form-group submit_group form-group__gaptop20">
                                <input type="button" class="button button__primary button__agree" @click="businessInformation" value="Agree and Save">
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
            'ageSizes',
            'teamSizes',
            'ticketSizes',
            'revenueSizes',
            'characteristics',
            'tags',
        ],

        name : "BusinessInformation",

        data() {
            return {
                errors    : {},
                errordata : 0,
                business  : [],
            }
        },

        mounted() {
          this.getData();
        },

        methods : {
            businessInformation() {
                axios
                    .put(`/web/my-business-detail`, {
                        age               : this.business.age,
                        revenue           : this.business.revenue,
                        teamSize          : this.business.teamSize,
                        averageTicketSize : this.business.averageTicketSize,
                        website           : this.business.website,
                        facebook          : this.business.facebook,
                        instagram         : this.business.instagram,
                        tags              : this.tags,
                    })
                    .then((e) => {
                        window.location.href = '/business';
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
                .get("/web/my-business-detail")
                .then(response => {
                    this.business = response.data.business;
                    if(!this.business) {
                        this.business = Object.assign({}, this.business, {
                            age               : null,
                            revenue           : null,
                            teamSize          : null,
                            averageTicketSize : null,
                            website           : null,
                            facebook          : null,
                            instagram         : null,
                            tags              : [],
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