<template>
    <section class="checkout sticky-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="checkout__left">
                        <div class="checkout__left__title">
                            <h2>Check Out & Pay</h2>
                        </div>
                        <form action="" enctype="multipart/form-data">
                            <div class="error_info" v-if="errordata">
                                <h3 v-for="error in errors">{{ error[0] }}</h3>
                            </div>
                            <div class="checkout__left__content">
                                <div class="checkout__left__content__list">
                                    <div class="checkout__left__content__list__title" v-b-toggle.business_info>
                                        <h2>Tell us about your business* 
                                            <span class="arrow-button">
                                                <a href="javascript:void(0)" v-b-toggle.business_info class=""></a>
                                            </span>
                                        </h2>
                                    </div>

                                    <b-collapse id="business_info" visible>
                                        <div class="checkout__left__content__list__content">
                                            <div class="checkout__left__content__list__content__top">
                                                <p>As bookings for this space are specially curated, please share with us what you intend to sell or promote. All applications are carefully reviewed by our team.</p>
                                                <div class="form-group__info_block d-none d-lg-block" v-if="!businessInfo">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <img src="/images/frontend/info-circle-E9248C.svg" alt="">
                                                        </div>
                                                        <div class="media-body">
                                                            <h3>For first-time customers, we’ll need a bit more information.</h3>
                                                            <h4>All data collected is for our use only, for purpose of curating your booking or understanding you. We will never share your data with third parties.</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!--info_block_top-->
                                            <div v-if="!businessInfo">
                                                <div class="form-group">
                                                    <div class="label">Age of Business <span>*</span></div>
                                                    <ul class="radio-buttons radio-buttons__text">
                                                        <li v-for="(ageSize, index) in ageSizes">
                                                            <input type="radio" v-model="businessDetail.age" :value="ageSize" :id="index">
                                                            <label :for="index">{{ ageSize }}</label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="form-group">
                                                    <div class="label">Revenue Size (in SGD) <span>*</span></div>
                                                    <ul class="radio-buttons radio-buttons__text">
                                                        <li v-for="(revenueSize, index) in revenueSizes">
                                                            <input type="radio" v-model="businessDetail.revenue" :value="revenueSize" :id="index">
                                                            <label :for="index">{{ revenueSize }}</label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="form-group">
                                                    <div class="label">Team Size <span>*</span></div>
                                                    <ul class="radio-buttons radio-buttons__text">
                                                        <li v-for="(teamSize, index) in teamSizes">
                                                            <input type="radio" v-model="businessDetail.teamSize" :value="teamSize" :id="index">
                                                            <label :for="index">{{ teamSize }}</label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="form-group">
                                                    <div class="label">Characteristics of Your Business <span>*</span></div>
                                                    <ul class="checkbox_item checkbox_item__with-image">
                                                        <li v-for="characteristic in characteristics">
                                                            <input type="checkbox" v-model="businessDetail.tags" :value="characteristic.id" :id="characteristic.id">
                                                            <label :for="characteristic.id">{{ characteristic.name }}</label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="form-group">
                                                    <div class="label">Average Ticket Size Per Item (SGD) <span>*</span></div>
                                                    <ul class="radio-buttons radio-buttons__text">
                                                        <li v-for="(ticketSize, index) in ticketSizes">
                                                            <input type="radio" v-model="businessDetail.averageTicketSize" :value="ticketSize" :id="index">
                                                            <label :for="index">{{ ticketSize }}</label>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="form-group">
                                                    <div class="label">Website</div>
                                                    <input type="text" class="form-control single_input3" v-model="businessDetail.website" placeholder="www.yourwebsite.com">
                                                </div>

                                                <div class="form-group">
                                                    <div class="label">Facebook</div>
                                                    <input type="text" class="form-control single_input3" v-model="businessDetail.facebook" placeholder="www.facebook.com/yourfacebook">
                                                </div>

                                                <div class="form-group">
                                                    <div class="label">Instagram</div>
                                                    <input type="text" class="form-control single_input3" v-model="businessDetail.instagram" placeholder="www.instagram.com/yourinstagram">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="label">Description of merchandise</div>
                                                <textarea class="form-control" v-model="description" placeholder="Clothes, food, jewellery, etc."></textarea>
                                            </div>

                                            <div class="form-group">
                                                <div class="label">Upload Files</div>
                                                <div class="form-group form-group__file-upload">
                                                    <div :class="`file_name ${file.invalidMessage} && 'has-text-danger'}`" v-for="(file, index) in files" :key="index">
                                                        {{ file.name }} 
                                                        <span v-if="file.invalidMessage">&nbsp;- {{ file.invalidMessage }}</span> 
                                                        <a href="javascript:void(0)" @click.prevent="files.splice(index, 1);uploadFiles.splice(index, 1);" class="remove_btn"><img src="/images/frontend/minus-fill-3E56C4.svg" alt=""></a>
                                                    </div>
                                                    <div class="upload_block">
                                                        <div class="upload_btn">
                                                            <a href="#" class="upload_file">Add File</a>
                                                            <input type="file" ref="files" id="imgInp" multiple @change="selectFile">
                                                        </div>
                                                        <div class="file_type">JPG, JPEG, PNG, and PDF only, up to 2MB per file.</div>
                                                    </div><!--upload_block-->
                                                </div>
                                            </div>
                                        </div><!--common_row-->
                                    </b-collapse>
                                </div>

                                <div class="checkout__left__content__list">
                                    <div class="checkout__left__content__list__title" v-b-toggle.customer_info>
                                        <h2>Customer Details
                                            <span class="arrow-button">
                                                <a href="javascript:void(0)" v-b-toggle.customer_info class=""></a>
                                            </span>
                                        </h2>
                                    </div>

                                    <b-collapse visible id="customer_info">
                                        <div class="checkout__left__content__list__content checkout__left__content__list__content__customer-info">

                                            <div class="row row20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">First Name</div>
                                                        <input type="text" id="firstName" class="form-control" v-model="user.firstName" value="Frankie">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Last Name</div>
                                                        <input type="text" id="lastName" class="form-control" v-model="user.lastName" value="Apple">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row row20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Phone number*</div>
                                                        <div class="phone_block">
                                                            <input type="text" id="num" class="form-control code_number" v-model="user.profile.countryCode" value="65">
                                                            <input type="text" id="phone" class="form-control phone_number" v-model="user.profile.phoneNumber" value="6777 8800  ">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Email</div>
                                                        <input type="text" id="email" class="form-control" v-model="user.email" disabled value="frankieapple@applesauce.com">
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
                                                        <input type="text" id="address" class="form-control" v-model="user.profile.address.street" value="Blk 300, Clementi Ave 6, 01-56">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Postal code</div>
                                                        <input type="text" id="postal_code" class="form-control" v-model="user.profile.address.postalCode" value="745600">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row row20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Company (Optional)</div>
                                                        <input type="text" id="company" class="form-control" v-model="user.profile.companyName" value="Living Zombies Pte Ltd">
                                                    </div>
                                                </div>

                                            </div>

                                        </div><!--common_row-->
                                    </b-collapse>
                                </div>

                                <div class="checkout__left__content__list">
                                    <div class="checkout__left__content__list__title">
                                        <h2>Billing Address <a href="javascript:void(0)" class="cs_rt" @click="copyCustomerDetails">Copy from customer details</a>
                                            <span class="arrow-button" v-b-toggle.billing_address><a href="javascript:void(0)"></a></span></h2>
                                    </div>

                                    <b-collapse id="billing_address" visible>
                                        <div class="checkout__left__content__list__content checkout__left__content__list__content__customer-info">

                                            <div class="row row20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">First Name</div>
                                                        <input type="text" id="billing_firstName" class="form-control" v-model="user.billing.firstName" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Last Name</div>
                                                        <input type="text" id="billing_lastName" class="form-control" v-model="user.billing.lastName" placeholder="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row row20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Phone number*</div>
                                                        <div class="phone_block">
                                                            <input type="text" id="billing_num" class="form-control code_number" v-model="user.billing.countryCode" placeholder="">
                                                            <input type="text" id="billing_phone" class="form-control phone_number" v-model="user.billing.phoneNumber" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="label">Email</div>
                                                        <input type="text" id="billing_email" class="form-control" v-model="user.billing.email" placeholder="">
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

                                        </div><!--common_row-->
                                    </b-collapse>
                                </div>

                                <div class="checkout__left__content__list checkout__left__content__list__coupon">
                                    <div class="checkout__left__content__list__coupon__title">
                                        <div class="checkout__left__content__list__coupon__title__left">Coupon Code</div>
                                        <a href="#" class="coupon_link"><span>View My Coupons</span></a>
                                    </div>
                                    <div class="checkout__left__content__list__coupon__content">
                                        <input type="text" class="form-control" v-model="couponCode" placeholder="EATBOX20">
                                        <input type="button" class="button button__primary button__agree" value="Apply" @click="checkCoupon">
                                    </div>
                                </div>

                                <div class="checkout__left__content__list checkout__left__content__list__payment">
                                    <div class="checkout__left__content__list__payment__title">
                                        <h3>Payment</h3>
                                        <p>By placing your order, you agree to Invade’s <a href="#">Terms &amp; Conditions</a> and <a href="#">Privacy Policy</a>.</p>
                                    </div>
                                    <div class="checkout__left__content__list__payment__content">
                                        <div class="checkout__left__content__list__payment__content__card">
                                            <div class="checkout__left__content__list__payment__content__card__label">
                                                <input type="radio" v-model="paymentMethod" value="1" id="p1">
                                                <label class="rd_block" for="p1">Debit / Credit Card</label>
                                            </div><!--radio_select-->
                                            <div class="checkout__left__content__list__payment__content__card__info">
                                                <p>The fastest and easiest. Order confirmation after payment is made and your chosen company confirm your order.</p>
                                            </div>
                                            <div class="checkout__left__content__list__payment__content__card__symbol"><img src="/images/frontend/credit-cards.svg" alt=""></div>
                                            <div v-if="paymentMethod == 1 && calculation" style="margin-top:10px;width:100%;float:left;height:auto;">
                                                <stripe-elements ref="elementsRef"
                                                                 :pk="publishableKey"
                                                                 :amount="calculation.grandTotal"
                                                                 @token="tokenCreated"
                                                                 @loading="loading = $event"
                                                >
                                                </stripe-elements>
                                            </div>
                                        </div>

                                        <div class="checkout__left__content__list__payment__content__card">
                                            <div class="checkout__left__content__list__payment__content__card__label">
                                                <input type="radio" v-model="paymentMethod" value="0" id="p2">
                                                <label class="rd_block" for="p2">Cheque / Bank Transfer / PayNow</label>
                                            </div><!--radio_select-->
                                            
                                            <div class="checkout__left__content__list__payment__content__card__info">
                                                <p>The fastest and easiest. Order confirmation after payment is made and your chosen company confirm your order.</p>
                                            </div>
                                            <div class="checkout__left__content__list__payment__content__card__symbol d-none d-md-block">
                                                <div class="payment_symbol"><img src="/images/frontend/cheque.svg" alt=""> <img src="/images/frontend/mobile-banking.svg" alt=""></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group__info_block d-none d-lg-block">
                                    <div class="media">
                                        <div class="media-left">
                                            <img src="/images/frontend/info-circle-E9248C.svg" alt="">
                                        </div>
                                        <div class="media-body">
                                            <h3>Your application will be reviewed upon full payment.</h3>
                                            <h4>If you have any questions or doubts, drop us a chat message.</h4>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="form-group form-group__submit d-none d-lg-block">
                                    <input type="button" class="button button__primary button__agree" value="Confirm & Pay" @click="submit">
                                </div>
                                
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-lg-5 d-none d-md-block" v-if="calculation">
                    <div class="booking_summary">
                        <div class="booking_summary__title">
                            <h3>{{ unit.name }} - {{ space.name }}</h3>
                        </div>
                        <div class="booking_summary__info">
                            <div class="booking_summary__info__single" v-for="item in calculation.periods.calculations">
                                <div class="booking_summary__info__single__top">
                                    <div class="ss_left">{{ item.date }} <span>x {{ item.quantity }} unit(s)</span></div>
                                    <div class="ss_right">SGD {{ item.total | money }}</div>
                                </div>
                                <div class="booking_summary__info__single__bottom" v-if="calculation.addOns.calculations.length > 0">
                                    <div class="booking_summary__info__single__bottom__top" v-for="item in calculation.addOns.calculations">
                                        <div class="ss_left">{{ item.name }} x {{ item.quantity }}</div>
                                        <div class="ss_right">+ SGD {{ item.total | money }}</div>
                                    </div><!--single_summary_top-->
                                </div>
                            </div>
                        </div>

                        <div class="booking_summary__discount" v-for="item in calculation.appliedDiscounts">
                            <div class="ss_left">{{ item.discount.name }} ({{ item.discount.discountValue }})</div>
                            <div class="ss_right">- SGD {{ item.beforeDiscount - item.afterDiscount | money
                                        }}</div>
                            <p>Discounts are not applicable for Add-Ons</p>
                        </div>

                        <div class="booking_summary__discount" v-if="calculation.appliedCoupon">
                            <div class="ss_left">Coupon code: {{ calculation.appliedCoupon.coupon.code }}</div>
                            <div class="ss_right">- SGD {{ calculation.appliedCoupon.beforeDiscount - calculation.appliedCoupon.afterDiscount | money}}
                            </div>
                        </div>

                        <div class="booking_summary__subtotal">
                            <div class="booking_summary__subtotal__list">
                                <div class="ss_left">Subtotal</div>
                                <div class="ss_right">SGD {{ calculation.subTotal | money }}</div>
                            </div>

                            <div class="booking_summary__subtotal__list">
                                <div class="ss_left">GST ({{ calculation.gst.percentage }}% Exclusive)</div>
                                <div class="ss_right">SGD {{ calculation.gst.amount | money}}</div>
                            </div>

                            <div class="booking_summary__subtotal__list"  v-if="calculation.securityDeposit">
                                <div class="ss_left">Security Deposit</div>
                                <div class="ss_right">SGD {{ calculation.securityDeposit | money }}</div>
                            </div>
                        </div>

                        <div class="booking_summary__total">
                            <div class="ss_left">Total Payable</div>
                            <div class="ss_right">SGD {{ calculation.grandTotal | money }}</div>
                        </div>

                    </div>
                </div>
                <div class="sticky-next d-block d-lg-none" v-if="calculation">
                    <div class="float-left">
                        <h4>SGD {{ calculation.grandTotal | money }}</h4>
                        <p>Total Payable </p>
                    </div>
                    <div class="float-right">
                        <div class="form-group form-group__submit">
                            <input type="button" class="button button__primary button__agree" value="Confirm & Pay" @click="submit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
    
    import { CollapsePlugin } from 'bootstrap-vue'
    Vue.use(CollapsePlugin)

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
    
    import { StripeElements } from 'vue-stripe-checkout';

    export default {
        props : [
            'countries',
            'ageSizes',
            'teamSizes',
            'ticketSizes',
            'revenueSizes',
            'characteristics',
            'businessInfo',
            'space',
            'unit',
            'calculationData',
            'cartData'
        ],

        components: {
            StripeElements
        },

        name : "Checkout",

        data() {
            return {
                errors         : {},
                errordata      : 0,
                couponCode     : null,
                paymentMethod  : 0,
                description    : null,
                user           : [],
                businessDetail : [],
                files          : [],
                uploadFiles    : [],
                requestData    : this.cartData,
                calculation    : this.calculationData,
                loading        : false,
                publishableKey : process.env.MIX_STRIPE_KEY, 
                token          : null,
                charge         : null,
            }
        },

        mounted() {
          this.getData();
          this.getBusiness();
        },

        methods : {
            selectFile() {
                const files = this.$refs.files.files;
                this.uploadFiles  = [ ...this.uploadFiles, ...files];
                this.files  = [ 
                    ...this.files, 
                    ..._.map(files, file => ({
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        invalidMessage: this.validate(file),
                    }))
                ];
            }, 
            
            validate(file) {
                const MAX_SIZE = 2000000;
                const allowedTypes = ["image/jpeg", "image/JPEG", "image/jpg", "image/JPG", "image/png", "image/PNG", "application/pdf"];
                if(file.size > MAX_SIZE) {
                    return `Max size: 2 Mb`;
                }
                if(!allowedTypes.includes(file.type)) {
                    return `Not an image`;
                }
                return "";
            },

            copyCustomerDetails() {
                let copiedObject = Object.assign({}, this.user);
                this.user.billing.firstName          = copiedObject.firstName;
                this.user.billing.lastName           = copiedObject.lastName;
                this.user.billing.email              = copiedObject.email;
                this.user.billing.countryCode        = copiedObject.profile.countryCode;
                this.user.billing.phoneNumber        = copiedObject.profile.phoneNumber;
                this.user.billing.companyName        = copiedObject.profile.companyName;
                this.user.billing.address.country    = copiedObject.profile.address.country;
                this.user.billing.address.street     = copiedObject.profile.address.street;
                this.user.billing.address.postalCode = copiedObject.profile.address.postalCode;
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
            },

            getBusiness() {
                axios
                .get("/web/my-business-detail")
                .then(response => {
                    this.businessDetail = response.data.business;
                    if(!this.businessDetail) {
                        this.businessDetail = Object.assign({}, this.businessDetail, {
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
            
            submit() {                
                var data = {
                  'userId'        : this.user.id,
                  'unit'          : this.unit,
                  'unitId'        : this.requestData.unitId,
                  'quantity'      : this.requestData.quantity,
                  'couponCode'    : this.couponCode,
                  'paymentMethod' : this.paymentMethod,
                  'periodUnitIds' : this.requestData.periodUnitIds,
                  'groupedAddOns' : this.requestData.groupedAddOns,
                  'adhocItems'    : JSON.stringify([]),

                  'customerDetail': {
                    'firstName'     : this.user.firstName,
                    'lastName'      : this.user.lastName,
                    'email'         : this.user.email,
                    'companyName'   : this.user.profile.companyName,
                    'contactNumber' : {
                        'code'        : this.user.profile.countryCode,
                        'number'      : this.user.profile.phoneNumber,
                    },
                    'address'       : {
                        'country'    : this.user.profile.address.country,
                        'street'     : this.user.profile.address.street,
                        'postalCode' : this.user.profile.address.postalCode,
                    }
                  },

                  'billingDetail': {
                    'firstName'     : this.user.billing.firstName,
                    'lastName'      : this.user.billing.lastName,
                    'email'         : this.user.billing.email,
                    'companyName'   : this.user.billing.companyName,
                    'contactNumber' : {
                        'code'       : this.user.billing.countryCode,
                        'number'     : this.user.billing.phoneNumber,
                    },
                    'address'        : {
                        'country'    : this.user.billing.address.country,
                        'street'     : this.user.billing.address.street,
                        'city'       : this.user.billing.address.city,
                        'state'      : this.user.billing.address.state,
                        'postalCode' : this.user.billing.address.postalCode,
                    }
                  },
                  'businessDetail': {
                    'age'               : this.businessDetail.age,
                    'revenue'           : this.businessDetail.revenue,
                    'teamSize'          : this.businessDetail.teamSize,
                    'averageTicketSize' : this.businessDetail.averageTicketSize,
                    'website'           : this.businessDetail.website,
                    'facebook'          : this.businessDetail.facebook,
                    'instagram'         : this.businessDetail.instagram,
                    'tags'              : this.tags,
                  },
                  'application': {
                    'description' : this.user.billing.firstName,
                    'attachments' : this.uploadFiles,
                  },
                };
                
                if(this.paymentMethod == 1) {
                    this.$refs.elementsRef.submit();
                } else {
                    axios
                        .post(`/web/bookings`, data)
                        .then((e) => {
                            console.log(e);
                        })
                        .catch((e) => {
                            if(e.response) {
                                if (e.response.status === 422) {
                                    this.errors    = e.response.data.errors;
                                    this.errordata = 1;
                                } 
                            }

                        })
                }
            },
            tokenCreated (token) {
              this.token = token;
              // for additional charge objects go to https://stripe.com/docs/api/charges/object
              this.charge = {
                source: token.card,
                amount: this.calculation.grandTotal,
                description: this.description
              }
              this.sendTokenToServer(this.charge);
            },
            sendTokenToServer (charge) {
                // Send to server
                const data = {
                  'userId'        : this.user.id,
                  'unit'          : this.unit,
                  'unitId'        : this.requestData.unitId,
                  'quantity'      : this.requestData.quantity,
                  'couponCode'    : this.couponCode,
                  'paymentMethod' : this.paymentMethod,
                  'charge'        : charge,
                  'periodUnitIds' : this.requestData.periodUnitIds,
                  'groupedAddOns' : this.requestData.groupedAddOns,
                  'adhocItems'    : JSON.stringify([]),

                  'customerDetail': {
                    'firstName'     : this.user.firstName,
                    'lastName'      : this.user.lastName,
                    'email'         : this.user.email,
                    'companyName'   : this.user.profile.companyName,
                    'contactNumber' : {
                        'code'        : this.user.profile.countryCode,
                        'number'      : this.user.profile.phoneNumber,
                    },
                    'address'       : {
                        'country'    : this.user.profile.address.country,
                        'street'     : this.user.profile.address.street,
                        'postalCode' : this.user.profile.address.postalCode,
                    }
                  },

                  'billingDetail': {
                    'firstName'     : this.user.billing.firstName,
                    'lastName'      : this.user.billing.lastName,
                    'email'         : this.user.billing.email,
                    'companyName'   : this.user.billing.companyName,
                    'contactNumber' : {
                        'code'       : this.user.billing.countryCode,
                        'number'     : this.user.billing.phoneNumber,
                    },
                    'address'        : {
                        'country'    : this.user.billing.address.country,
                        'street'     : this.user.billing.address.street,
                        'city'       : this.user.billing.address.city,
                        'state'      : this.user.billing.address.state,
                        'postalCode' : this.user.billing.address.postalCode,
                    }
                  },
                  'businessDetail': {
                    'age'               : this.businessDetail.age,
                    'revenue'           : this.businessDetail.revenue,
                    'teamSize'          : this.businessDetail.teamSize,
                    'averageTicketSize' : this.businessDetail.averageTicketSize,
                    'website'           : this.businessDetail.website,
                    'facebook'          : this.businessDetail.facebook,
                    'instagram'         : this.businessDetail.instagram,
                    'tags'              : this.tags,
                  },
                  'application': {
                    'description' : this.user.billing.firstName,
                    'attachments' : this.uploadFiles,
                  },
                };
                axios
                    .post(`/web/bookings`, data)
                        .then((e) => {
                        console.log(e);
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
            
            checkCoupon() {
                axios
                    .get(`/web/check-coupon?couponCode=${this.couponCode}`)
                    .then((e) => {
                        this.calculate();
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
            
            async calculate() {
                var data = {
                  'unitId'        : this.requestData.unitId,
                  'quantity'      : this.requestData.quantity,
                  'couponCode'    : this.couponCode,
                  'periodUnitIds' : this.requestData.periodUnitIds,
                  'groupedAddOns' : this.requestData.groupedAddOns,
                  'userId'        : this.user.id,
                };

                this.calculation = await axios.get('/api/calculate-booking', {
                    params : {
                        quantity      : this.requestData.quantity,
                        unitId        : this.requestData.unitId,
                        couponCode    : this.couponCode,
                        periodUnitIds : this.requestData.periodUnitIds,
                        groupedAddOns : this.requestData.groupedAddOns,
                        userId        : this.user.id,
                    }
                })
                    .then(r => {
                        axios.get('/web/cart', {
                             params: {
                               unitId      : this.requestData.id,
                               calculation : r.data,
                               requestData : data,
                             }
                           })
                        return r.data
                    })
                    .catch((e) => {
                        this.errordata = 1;
                    })
            },
        },
        
        filters : {
            /**
             * Convert cents to dollars.
             *
             * @param amount
             * @return {string}
             */
            money : (amount) => {
                return new Intl.NumberFormat('en-SG').format(amount / 100);
            },

            /**
             * Convert cents to dollars.
             *
             * @param amount
             * @return {string}
             */
            moneyWithoutFormat : (amount) => {
                return amount / 100;
            }
        }
    }
</script>