<template>
    <div v-if="breakdown">
        <h1 class="flex-no-shrink text-90 font-normal text-2xl">Pricing breakdown</h1>

        <card class="p-4 mt-4 text-sm capitalize w-3/5">
            <!-- periods and add-ons -->
            <div class="flex flex-wrap border-b border-60 py-2"
                 v-if="breakdown.periods"
                 v-for="period in breakdown.periods.calculations">
                <div class="w-2/3">
                    <div class="p-1">
                        {{ period.date }} x {{ period.quantity }}
                    </div>

                    <div class="p-1" v-for="addOn in breakdown.addOns.calculations">
                        {{ addOn.name }} x {{ addOn.quantity }}
                    </div>
                </div>
                <div class="w-1/3">
                    <div class="p-1 text-right">
                        SGD {{ period.total | money }}
                    </div>

                    <div class="p-1 text-right" v-for="addOn in breakdown.addOns.calculations">
                        SGD {{ addOn.total / breakdown.periods.calculations.length | money }}
                    </div>
                </div>
            </div>
            <!-- end periods and add-ons -->

            <!-- ad-hoc items-->
            <div class="flex flex-wrap border-b border-60 py-2"
                 v-if="breakdown.adhocItems"
                 v-for="adhocItem in breakdown.adhocItems.calculations">
                <div class="w-2/3">
                    <div class="p-1">
                        {{ adhocItem.item.name }} x {{ adhocItem.item.quantity }}
                    </div>
                </div>
                <div class="w-1/3">
                    <div class="p-1 text-right">
                        SGD {{ adhocItem.total | money }}
                    </div>
                </div>
            </div>
            <!-- end ad-hoc items -->

            <!-- discounts and coupons -->
            <div class="border-b border-60 py-2" v-if="breakdown.appliedDiscounts || breakdown.appliedCoupon">
                <div class="flex flex-wrap"
                     v-if="breakdown.appliedDiscounts"
                     v-for="appliedDiscount in breakdown.appliedDiscounts">
                    <div class="w-2/3">
                        <div class="p-1">
                            {{ appliedDiscount.discount.name }}
                        </div>
                    </div>
                    <div class="w-1/3">
                        <div class="p-1 text-right">
                            - SGD {{ appliedDiscount.beforeDiscount - appliedDiscount.afterDiscount | money }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap" v-if="breakdown.appliedCoupon">
                    <div class="w-2/3">
                        <div class="p-1">
                            Coupon code: {{ this.getOriginalCouponInformation().code }}
                            ({{ this.getCouponDiscount() }})
                        </div>
                    </div>
                    <div class="w-1/3">
                        <div class="p-1 text-right">
                            - SGD {{ breakdown.appliedCoupon.beforeDiscount - breakdown.appliedCoupon.afterDiscount | money }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- end discounts and coupons -->

            <!-- subtotal and gst -->
            <div class="border-b border-60 py-2">
                <div class="flex flex-wrap">
                    <div class="w-2/3 p-1">Subtotal</div>
                    <div class="w-1/3 p-1 text-right">SGD {{ breakdown.subTotal | money }}</div>
                </div>

                <div class="flex flex-wrap p-1">
                    <div class="w-2/3">GST ({{ breakdown.gst.percentage }}% Exclusive)</div>
                    <div class="w-1/3 text-right">SGD {{ breakdown.gst.amount | money }}</div>
                </div>
            </div>
            <!-- end subtotal and gst -->

            <!-- security deposit -->
            <div class="border-b border-60 py-2" v-if="breakdown.securityDeposit">
                <div class="flex flex-wrap">
                    <div class="w-2/3 p-1">Security deposit (Refundable)</div>
                    <div class="w-1/3 p-1 text-right">SGD {{ breakdown.securityDeposit | money }}</div>
                </div>
            </div>
            <!-- end security deposit -->

            <!-- total -->
            <div class="py-2">
                <div class="flex flex-wrap">
                    <div class="w-2/3 p-1">Total payable</div>
                    <div class="w-1/3 p-1 text-right">SGD {{ breakdown.grandTotal | money }}</div>
                </div>
                <div class="flex flex-wrap">
                    <div class="w-2/3 p-1">Amount paid</div>
                    <div class="w-1/3 p-1 text-right">SGD {{ booking.paid | money }}</div>
                </div>
                <div class="flex flex-wrap">
                    <div class="w-2/3 p-1">Balance</div>
                    <div class="w-1/3 p-1 text-right">SGD {{ booking.outstanding | money }}</div>
                </div>
            </div>
            <!-- end total -->

        </card>
    </div>
</template>

<script>
    const endpoint = '/nova-vendor/breakdown-pricing-breakdown-resource-tool';

    export default {
        props : ['resourceName', 'resourceId', 'field'],

        data() {
            return {
                breakdown : null,
                booking : null,
            }
        },

        methods : {
            getBreakdown() {
                axios.get(`/web/bookings/${this.resourceId}`)
                    .then(r => {
                        this.booking = r.data.booking;
                        this.breakdown = r.data.booking.data.calculations;
                    })
                    .catch(() => {
                        this.$toasted.error('Unable to retrieve breakdown information.')
                    });
            },

            getOriginalCouponInformation() {
                return this.breakdown.appliedCoupon.coupon;
            },

            getCouponDiscount() {
                let prefix = '';
                let suffix = '';

                if (this.getOriginalCouponInformation().data.rate_type === 'fixed') {
                    prefix = '$';
                }
                else {
                    suffix = '%';
                }

                return `${prefix}${this.getOriginalCouponInformation().data.rate}${suffix}`;
            }
        },

        filters: {
            /**
             * Convert cents to dollars.
             *
             * @param amount
             * @return {string}
             */
            money : (amount) => {
                return new Intl.NumberFormat('en-SG', {
                    style : 'currency',
                    currency : 'SGD'
                }).format(amount / 100);
            }
        },

        mounted() {
            this.getBreakdown();
        },
    }
</script>
