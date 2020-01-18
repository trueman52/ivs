<template>
    <div>
        <heading class="mb-6">Edit Booking</heading>

        <card class="flex flex-col" v-if="booking">
            <!-- Space -->
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Space</label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <input class="w-full form-control form-input form-input-bordered px-2 w-1/3"
                           disabled="disabled"
                           :value="booking.spaceName">
                </div>
            </div>
            <!-- End space -->

            <!-- Unit -->
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Unit</label>
                </div>

                <div class="w-1/2 py-6 px-8">
                    <input class="w-full form-control form-input form-input-bordered px-2 w-1/3"
                           disabled="disabled"
                           :value="booking.unitName">
                </div>
            </div>
            <!-- End unit -->

            <!-- Quantity -->
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Quantity</label>
                </div>

                <div class="w-1/2 py-6 px-8">
                    <input class="w-full form-control form-input form-input-bordered px-2 w-1/3" v-model="quantity">
                </div>
            </div>
            <!-- End quantity -->

            <!-- Periods -->
            <div class="flex border-b border-40" v-if="unit">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Period</label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <multiselect v-model="selectedPeriods"
                                 :custom-label="periodLabel"
                                 track-by="id"
                                 :options="unit.periods"
                                 :multiple="true"
                                 :hide-selected="true"
                                 :close-on-select="false"
                                 :clear-on-select="false">
                        <template slot="option" slot-scope="props">
                            <div class="option__title">
                                {{ props.option.fromDate | parseDbDate }} - {{ props.option.toDate | parseDbDate }}
                            </div>
                        </template>
                    </multiselect>
                </div>
            </div>
            <!-- End periods -->

            <!-- Add-ons -->
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Add-ons</label>
                </div>
                <div class="w-3/4 py-6 px-8 self-end">
                    <!-- Show booking add-ons -->
                    <div class="-mx-2 flex flex-wrap pb-3"
                         v-for="(bookingAddOn, key) in bookingAddOns"
                         :key="bookingAddOn.id">
                        <div class="px-2 w-1/3">
                            <input class="w-full form-control form-input form-input-bordered"
                                   disabled="disabled"
                                   :value="`${bookingAddOn.addOn.frontendName} (${$options.filters.money(bookingAddOn.purchasedAt)})`">
                        </div>

                        <div class="px-2 w-1/3">
                            <input class="w-full form-control form-input form-input-bordered"
                                   v-model="bookingAddOn.quantity">
                        </div>

                        <div class="px-2 w-1/3">
                            <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                                 @click="removeBookingAddOn(key)">
                                Remove
                            </div>
                        </div>
                    </div>
                    <!-- End show booking add-ons -->

                    <!-- Show new add-ons -->
                    <div class="-mx-2 flex flex-wrap pb-3"
                         v-for="(addOn, key) in newAddOns"
                         :key="addOn.key">
                        <div class="px-2 w-1/3">
                            <multiselect v-model="newAddOns[key].addOn"
                                         label="frontendName"
                                         track-by="id"
                                         :options="selectableAddOns"
                                         :clear-on-select="false"
                                         :show-no-results="true">
                                <template slot="option" slot-scope="props">
                                    <div class="option__title">
                                        {{ props.option.frontendName }}
                                    </div>
                                </template>
                            </multiselect>
                        </div>

                        <div class="px-2 w-1/3">
                            <input class="w-full form-control form-input form-input-bordered"
                                   v-model="addOn.quantity">
                        </div>

                        <div class="px-2 w-1/3">
                            <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                                 @click="removeNewAddOn(key)">
                                Remove
                            </div>
                        </div>
                    </div>
                    <!-- End show new add-ons -->
                    <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                         @click="insertAddOnRow">Add add-on
                    </div>
                </div>
            </div>
            <!-- End add-ons -->

            <!-- Coupon -->
            <div class="flex border-b border-40" v-if="bookingCoupon">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Booking coupon</label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <div class="flex flex-wrap -mx-2">
                        <div class="w-1/2 px-2">
                            <input :value="bookingCoupon.data.coupon_details.data.title"
                                   disabled="disabled"
                                   class="w-full form-control form-input form-input-bordered">
                        </div>
                        <div class="w-1/2 px-2">
                            <div class="text-primary font-semibold p-2 cursor-pointer"
                                 @click="removeBookingCoupon()">
                                Remove
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex border-b border-40" v-if="selectableCoupons.length > 0 && !bookingCoupon">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Add coupon</label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <multiselect v-model="selectedCoupon"
                                 :custom-label="couponLabel"
                                 track-by="id"
                                 :options="selectableCoupons"
                                 :close-on-select="true"
                                 :clear-on-select="false"
                                 :show-no-results="true">
                        <template slot="option" slot-scope="props">
                            <div class="option__title">{{ props.option.data.title }}</div>
                        </template>
                    </multiselect>
                </div>
            </div>
            <!-- End coupon -->

            <!-- Ad-hoc items -->
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Ad-hoc items</label>
                </div>
                <div class="w-3/4 py-6 px-8 self-end">
                    <!-- Show booking ad-hoc items  -->
                    <div class="-mx-2 flex flex-wrap pb-3"
                         v-for="(bookingAdhocItem, key) in bookingAdhocItems"
                         :key="bookingAdhocItem.id">
                        <div class="px-2 w-1/4">
                            <input class="w-full form-control form-input form-input-bordered"
                                   placeholder="Item name"
                                   v-model="bookingAdhocItem.name">
                        </div>

                        <div class="px-2 w-1/4">
                            <input class="w-full form-control form-input form-input-bordered"
                                   disabled="disabled"
                                   placeholder="S$"
                                   :value="bookingAdhocItem.amount / 100">
                        </div>

                        <div class="px-2 w-1/4">
                            <input class="w-full form-control form-input form-input-bordered"
                                   placeholder="Qty"
                                   v-model="bookingAdhocItem.quantity">
                        </div>

                        <div class="px-2 w-1/4">
                            <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                                 @click="removeBookingAdhocItems(key)">
                                Remove
                            </div>
                        </div>
                    </div>
                    <!-- End show booking add-ons -->

                    <!-- Show new add-ons -->
                    <div class="-mx-2 flex flex-wrap pb-3"
                         v-if="newAdhocItems.length > 0"
                         v-for="(item, key) in newAdhocItems" :key="item.key">
                        <div class="px-2 w-1/4">
                            <input class="w-full form-control form-input form-input-bordered px-2 w-1/3"
                                   placeholder="Item name"
                                   type="text"
                                   @change="updateNewAdhocItem($event.target.value, key, 'name')">
                        </div>
                        <div class="px-2 w-1/4">
                            <input class="w-full form-control form-input form-input-bordered px-2 w-1/3"
                                   placeholder="S$"
                                   type="text"
                                   @change="updateNewAdhocItem($event.target.value, key, 'amount')">
                        </div>
                        <div class="px-2 w-1/4">
                            <input class="w-full form-control form-input form-input-bordered"
                                   placeholder="Qty"
                                   type="text"
                                   @change="updateNewAdhocItem($event.target.value, key, 'quantity')">
                        </div>
                        <div class="px-2 w-1/4">
                            <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                                 @click="removeAdhocRow(key)">
                                Remove
                            </div>
                        </div>
                    </div>
                    <!-- End show new add-ons -->

                    <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                         @click="insertAdhocRow">Add ad-hoc item
                    </div>
                </div>
            </div>
            <!-- End ad-hoc items -->

            <!-- Notify customer option -->
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Notify customer</label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <input type="checkbox" v-model="notifyCustomer">
                </div>
            </div>
            <!-- End otify customer option -->

            <!-- Internal notes -->
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Internal notes</label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <textarea cols="30" rows="10" v-model="internalNotes"></textarea>
                </div>
            </div>
            <!-- End internal notes -->
        </card>

        <button class="mt-6 btn btn-primary btn-default" @click="calculate">
            Confirm changes
        </button>

        <v-modal name="confirm"
                 height="auto"
                 :adaptive="true"
                 :scrollable="true">
            <div class="p-8">
                <h2 class="text-2xl font-normal">Confirm booking for customer</h2>

                <div class="mt-4" v-if="calculation">
                    <div class="border border-30">
                        <div class="bg-30 p-4 text-90">
                            Booking summary
                        </div>
                        <div class="p-4 text-sm -my-4">
                            <!--  periods and add-ons -->
                            <div class="py-4 border-b border-30"
                                 v-for="item in calculation.periods.calculations">
                                <div class="flex flex-wrap">
                                    <div class="pt-2 w-1/2">{{ item.date }} x {{ item.quantity }} unit(s)</div>
                                    <div class="pt-2 w-1/2 text-right">{{ item.total | money
                                        }}
                                    </div>
                                </div>
                                <div class="flex flex-wrap" v-for="item in calculation.addOns.calculations">
                                    <div class="pt-2 w-1/2">{{ item.name }} x {{ item.quantity }}</div>
                                    <div class="pt-2 w-1/2 text-right">{{ item.total | money
                                        }}
                                    </div>
                                </div>
                            </div>
                            <!--  end periods and add-ons -->

                            <!-- discounts and coupon -->
                            <div class="py-4 border-b border-30" v-for="item in calculation.appliedDiscounts">
                                <div class="flex flex-wrap">
                                    <div class="pt-2 w-1/2">{{ item.discount.name }} ({{ item.discount.discountValue }})</div>
                                    <div class="pt-2 w-1/2 text-right">- {{ item.beforeDiscount - item.afterDiscount | money
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div class="py-4 border-b border-30" v-if="calculation.appliedCoupon">
                                <div class="flex flex-wrap">
                                    <div class="pt-2 w-1/2">Coupon code: {{ calculation.appliedCoupon.coupon.code }}</div>
                                    <div class="pt-2 w-1/2 text-right">
                                        - {{ calculation.appliedCoupon.beforeDiscount - calculation.appliedCoupon.afterDiscount | money}}
                                    </div>
                                </div>
                            </div>
                            <!-- end discounts and coupon -->

                            <!-- ad-hoc items -->
                            <div class="py-4 border-b border-30" v-for="item in calculation.adhocItems.calculations">
                                <div class="flex flex-wrap">
                                    <div class="pt-2 w-1/2">{{ item.item.name }} x {{ item.item.quantity }}</div>
                                    <div class="pt-2 w-1/2 text-right">{{ item.total | money }}
                                    </div>
                                </div>
                            </div>
                            <!-- end ad-hoc items -->


                            <!-- Subtotal subtotal and gst -->
                            <div class="py-4 border-b border-30">
                                <div class="flex flex-wrap">
                                    <div class="pt-2 w-1/2">Subtotal</div>
                                    <div class="pt-2 w-1/2 text-right">{{ calculation.subTotal | money }}</div>

                                    <div class="pt-2 w-1/2">GST ({{ calculation.gst.percentage }}%)</div>
                                    <div class="pt-2 w-1/2 text-right">
                                        {{ calculation.gst.amount | money}}
                                    </div>
                                </div>
                            </div>
                            <!-- end subtotal and gst -->

                            <!-- security deposit -->
                            <div class="py-4 border-b border-30" v-if="calculation.securityDeposit">
                                <div class="flex flex-wrap">
                                    <div class="pt-2 w-1/2">Security deposit (refundable)</div>
                                    <div class="pt-2 w-1/2 text-right">{{ calculation.securityDeposit | money }}</div>
                                </div>
                            </div>
                            <!-- end security deposit -->

                            <!-- grand total -->
                            <div class="py-4 border-b border-30">
                                <div class="flex flex-wrap">
                                    <div class="pt-2 w-1/2">Total payable</div>
                                    <div class="pt-2 w-1/2 text-right">{{ calculation.grandTotal | money }}</div>
                                </div>
                            </div>
                            <!-- end grand total -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-30 p-4 text-90">
                <div class="flex items-center">
                    <button @click="$modal.hide('confirm')"
                            class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6">
                        Cancel
                    </button>
                    <button type="button"
                            class="btn btn-default btn-primary inline-flex items-center relative mr-3"
                            @click="save">
                        Save changes
                    </button>
                </div>
            </div>
        </v-modal>
    </div>
</template>

<script>
    const endpoint = '/nova-vendor/edit-booking-tool';

    import { concat, find, flatMap, flatten, map, toArray } from 'lodash';

    export default {
        mounted() {
            this.initDefaultState()
        },

        data() {
            return {
                calculation : null,

                // Booking related data.
                unit : null,
                booking : null,
                quantity : 0,
                selectedPeriods : [],
                selectedCoupon : null,
                bookingAdhocItems : [],
                bookingAddOns : [],
                bookingCoupon : null,
                notifyCustomer : false,
                internalNotes : '',

                // Selectable data.
                selectableAddOns : [],
                selectableCoupons : [],

                // New additions for this booking.
                newAddOns : [],
                newAdhocItems : [],
            }
        },

        methods : {
            removeBookingCoupon() {
                this.bookingCoupon = null;
            },

            save() {
                let couponCode = '';

                if (this.bookingCoupon) {
                    couponCode = this.bookingCoupon.data.coupon_details.code;
                }

                if (!!this.selectedCoupon) {
                    couponCode = this.selectedCoupon.code;
                }

                axios.put(`${endpoint}/bookings/${this.booking.id}`, {
                    unitId : this.booking.unitId,
                    booking : this.booking,
                    quantity : this.quantity,
                    periodUnitIds : this.selectedPeriodUnitIdsApiData(),
                    groupedAddOns : this.addOnsApiData(),
                    adhocItems : this.adhocItemsApiData(),
                    notifyCustomer: this.notifyCustomer,
                    internalNotes: this.internalNotes,
                    couponCode
                })
                    .then(r => {
                        this.$modal.hide('confirm');

                        this.$toasted.success('Booking has been updated.');

                        this.$router.push({
                            name: 'detail',
                            params: {
                                resourceName: 'bookings',
                                resourceId: this.booking.id,
                            }
                        })
                    })
                    .catch((e) => {
                        this.errors = flatten(toArray(e.response.data.errors));

                        this.$toasted.error('Unable to perform update on booking.');
                    })
            },

            async calculate() {
                let couponCode = '';

                if (this.bookingCoupon) {
                    couponCode = this.bookingCoupon.data.coupon_details.code;
                }

                if (!!this.selectedCoupon) {
                    couponCode = this.selectedCoupon.code;
                }

                this.calculation = await axios.get(`${endpoint}/calculate-booking`, {
                    params : {
                        unitId : this.booking.unitId,
                        booking : this.booking,
                        quantity : this.quantity,
                        periodUnitIds : this.selectedPeriodUnitIdsApiData(),
                        groupedAddOns : this.addOnsApiData(),
                        adhocItems : this.adhocItemsApiData(),
                        couponCode
                    }
                })
                    .then(r => {
                        return r.data
                    })
                    .catch((e) => {
                        this.errors = flatten(toArray(e.response.data.errors));
                        this.$toasted.error('Unable to perform calculation on booking.');
                    })

                if (this.calculation) {
                    this.$modal.show('confirm');
                }
            },

            /**
             * Prepare an array of period unit ids for API calls.
             *
             * @return {Array}
             */
            selectedPeriodUnitIdsApiData() {
                return flatMap(this.selectedPeriods, (period) => {
                    return period.pivot.id;
                })
            },

            /**
             * Prepare an array of containing booking add-ons and newly added add-ons.
             *
             * @return {String}
             */
            addOnsApiData() {
                let bookingAddOns = flatMap(this.bookingAddOns, (bookingAddOn) => {
                    return {
                        id : bookingAddOn.addOn.id,
                        quantity : bookingAddOn.quantity,
                    }
                });

                let newAddOns = flatMap(this.newAddOns, (addOn) => {
                    return {
                        id : addOn.addOn.id,
                        quantity : addOn.quantity
                    }
                });

                return JSON.stringify(bookingAddOns.concat(newAddOns));
            },

            /**
             * Prepare an array of period unit ids for API calls.
             *
             * @return {String}
             */
            adhocItemsApiData() {
                let joined = concat(this.bookingAdhocItems, this.newAdhocItems);

                return JSON.stringify(map(joined, (item) => {
                    // map all properties when trying to convert the
                    // amounts to cents, the original object gets mutated.
                    let duplicate = {...item}

                    // convert amount to cents
                    duplicate.amount *= 100;

                    return duplicate;
                }));
            },

            /**
             * Update adhoc item's properties
             *
             * @param {mixed} value
             * @param {int} key
             * @param {string} prop
             */
            updateNewAdhocItem(value, key, prop) {
                this.newAdhocItems[key][prop] = value;
            },

            /**
             * Return the label used by period's select.
             *
             * @param {object}
             * @return {string}
             */
            periodLabel({fromDate, toDate}) {
                return `${this.$options.filters.parseDbDate(fromDate)} - ${this.$options.filters.parseDbDate(toDate)}`;
            },

            /**
             * Get information related to the booking.
             *
             * @param id
             */
            getBooking() {
                return axios.get(`${endpoint}/bookings/${this.$route.params.id}`)
            },

            /**
             * Get add-ons, periods, and the unit's information.
             *
             * @param id
             */
            getUnitInformation(id) {
                return axios.get(`${endpoint}/units/${id}`);
            },

            /**
             * Remove booking add-on
             */
            removeBookingAddOn(key) {
                this.$delete(this.bookingAddOns, key);
            },

            /**
             * Remove newly added add-on
             */
            removeNewAddOn(key) {
                this.$delete(this.newAddOns, key);
            },

            /**
             * Add a new row to add-ons
             */
            insertAddOnRow() {
                this.newAddOns.push({key : this.makeUniqueKey()});
            },

            /**
             * Add a new row to adhoc items
             */
            insertAdhocRow() {
                this.newAdhocItems.push({key : this.makeUniqueKey()});
            },

            /**
             * Return the label used by coupon's select.
             *
             * @param {object} data
             * @return {string}
             */
            couponLabel({data}) {
                return data.title;
            },

            /**
             * Generates a unique key so that we delete
             * a generated row without an issue. (used by
             * add-ons and adhoc items.
             *
             * @return {string}
             */
            makeUniqueKey() {
                return moment().valueOf()
            },

            /**
             * Generates the available coupons we can use for the booking.
             *
             * @param {int} spaceId
             */
            getCoupons() {
                return axios.get(`${endpoint}/coupons`, {
                    params : {
                        spaceId : this.booking.spaceId,
                        bookingId : this.booking.id,
                    }
                })
            },

            /**
             * Remove adhoc items by index.
             */
            removeAdhocRow(key) {
                console.log(key);
                this.$delete(this.newAdhocItems, key);
            },

            /**
             * Remove booking adhoc items by index.
             */
            removeBookingAdhocItems(key) {
                this.$delete(this.bookingAdhocItems, key);
            },

            /**
             * Initialize the default states.
             *
             * @return {Promise<void>}
             */
            async initDefaultState() {
                let bookingRes = await this.getBooking();

                // Set our booking details.
                this.booking = bookingRes.data.booking;
                this.bookingAddOns = this.booking.bookingAddOns;
                this.bookingCoupon = this.booking.usedCoupon;

                // Set available coupons we can use.
                let couponRes = await this.getCoupons();

                this.selectableCoupons = couponRes.data.coupons;

                // Since booking periods's quantity is the same
                // throughout, we will just take the first period's quantity.
                this.quantity = this.booking.bookingPeriods[0].quantity;

                let unitRes = await this.getUnitInformation(this.booking.unitId);

                // Set unit's data.
                this.unit = unitRes.data.unit;

                // Set the add-ons we can select.
                this.selectableAddOns = flatMap(this.unit.addOnGroups, group => group.addOns);

                // Get the booking period's id and find the matching unit period's id.
                this.selectedPeriods = map(this.booking.bookingPeriods, (bookingPeriod) => {
                    return find(this.unit.periods, {id : bookingPeriod.periodId})
                });

                this.bookingAdhocItems = this.booking.adhocItems;
            }
        },

        filters : {
            parseDbDate : (date) => {
                if (!date) return '';

                return moment(date).format('DD MMM YYYY')
            },

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
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
