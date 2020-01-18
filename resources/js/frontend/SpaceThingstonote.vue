<template>
    <section class="thingstonote sticky-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="thingstonote__left">
                        <div class="thingstonote__left__title">
                            <h2>Things to Note</h2>
                        </div>
                        <div class="thingstonote__left__note" v-if="unit.tags">
                            <ul>
                                <li v-for="tag in unit.tags">
                                    <div class="media">
                                        <div class="media-left">
                                            <i :class="tag.icon"></i>
                                        </div>
                                        <div class="media-body">
                                            <h4>{{ tag.name }}</h4>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="thingstonote__left__points">
                            <h3>Specific Pointers</h3>
                            <ul>
                                <li>Kindly the booth order e-mail confirmation for our coordinators to verify.</li>
                                <li>Vendors are required to bring your own tablecloth, decorations and extension cord.</li>
                                <li>All pre-packed food and beverage products are subjected to management approval.</li>
                                <li>Strictly NO sale of pre-loved and secondhand products are allowed.</li>
                                <li>It may take up to 3 working days to verify your cheque and have it cleared by the bank.</li>
                                <li>Eatbox Singapore reserves the right to reject vendors/products that deemed as unsuitable for the event with no explanation given.</li>
                                <li>Eatbox Singapore reserves the rights to reject any vendors who are not cooperative with no refunds and/or compensation given. </li>
                            </ul>

                            <div class="thingstonote__left__points__submit">
                                <div class="disagree_btn">
                                    <form action="#">
                                        <input type="button" class="button button__default button__disagree" @click="disagree" value="I Disagree">
                                    </form>
                                </div>
                                <div class="agree_btn">
                                    <form action="#">
                                        <input type="button" class="button button__primary button__agree" @click="thingsToNote" value="I Agree">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="booking_summary" v-if="calculation">
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
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props : [
            'space',
            'unit',
            'calculation'
        ],

        name : "SpaceThingstonote",

        methods : {
            thingsToNote() {
                window.location.href = '/spaces/' + this.unit.spaceId + '/checkout';
            },

            disagree() {
                if(confirm("Do you really disagree?")){
                    axios
                        .delete('/web/cart')
                        .then(response => {
                            window.location.href = '/spaces/' + this.unit.spaceId;
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }
                        
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
            }
        }
    }
</script>