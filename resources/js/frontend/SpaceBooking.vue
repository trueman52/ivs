<template>
    <section class="booking sticky-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 sticky-left">
                    <div class="booking__left">
                        <div class="booking__left__title">
                            <h2>Booking</h2>
                        </div>
                        <form action="#">
                            <div class="booking__left__content">
                                <div class="form-group">
                                    <div class="label">Unit</div>
                                    <div class="selectbox">
                                        <multiselect v-model="selectedUnit"
                                                     label="name"
                                                     track-by="name"
                                                     placeholder="Choose a unit"
                                                     :options="space.units"
                                                     @input="calculate()"
                                                     :show-no-results="true">
                                        </multiselect>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="label">Quantity</div>
                                    <div class="quantity">
                                        <input type="text" class="form-control" v-model="quantity" placeholder="3">
                                        <input type="button" class="quantity__button quantity__button__up" @click="increment()">
                                        <input type="button" class="quantity__button quantity__button__down" @click="decrement()">
                                    </div>
                                </div>

                                <div class="form-group" v-if="canShowAvailablePeriod">
                                    <h3>Available Periods</h3>
                                    <div class="form-group__info_block d-none d-lg-block">
                                        <div class="media">
                                            <div class="media-left">
                                                <img src="/images/frontend/info-circle-E9248C.svg" alt="">
                                            </div>
                                            <div class="media-body">
                                                <h3>There are no available periods for your selected quantity.</h3>
                                                <h4>Please lower your quantity or choose another space.</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" v-if="selectedUnit">
                                    <ul class="checkbox_item">
                                        <li v-for="period in selectedUnit.periods">
                                            <div v-if="period.periodAvailable">
                                                <div v-if="period.pivot.remaining_quantity >= quantity">
                                                    <input type="checkbox" v-model="selectedPeriods" v-bind:value="period.pivot.id" :id="period.pivot.id" @click="calculate($event)">
                                                    <label class="check_box" :for="period.pivot.id">
                                                        <span>{{ period.fromDate | parseDbDate }} - {{ period.toDate | parseDbDate }}</span>
                                                        <span>SGD 1, {{ period.pivot.unit_price | money }}</span>
                                                    </label>
                                                </div>
                                                <div v-else>
                                                    <input type="checkbox" v-bind:value="period.pivot.id" :id="period.pivot.id" disabled="">
                                                    <label class="check_box" :for="period.pivot.id">
                                                        <span>{{ period.fromDate | parseDbDate }} - {{ period.toDate | parseDbDate }}</span>
                                                        <span>Not Available </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <input type="checkbox" v-bind:value="period.pivot.id" :id="period.pivot.id" disabled="">
                                                <label class="check_box" :for="period.id">
                                                    <span>{{ period.fromDate | parseDbDate }} - {{ period.toDate | parseDbDate }}</span>
                                                    <span>Not Available </span>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="booking__left__content__addons" v-if="selectedUnit">
                                    <div class="booking__left__content__addons__top">
                                        <h3>Upgrades &amp; Add-ons</h3>
                                        <p>Upgrade your units with Add-ons.</p>
                                        <p>Add-on purchased for every periods and space selected above.</p>
                                    </div>


                                    <div class="booking__left__content__addons__content">
                                        <div v-for="(addOn, key) in selectedGroupedAddOns">
                                            <h3 v-if="addOn.groupId">{{ generateAddOnGroup(addOn.groupId) }}</h3>

                                            <div class="booking__left__content__addons__content__card">
                                                <div class="booking__left__content__addons__content__card__inner">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <img :src="generateAddOn(addOn.addOnId).mediaFullUrl" alt="">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="product_info">
                                                                <h3>{{ generateAddOn(addOn.addOnId).frontendName }}</h3>
                                                                <p>{{ generateAddOn(addOn.addOnId).description }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="booking-quantity" v-if="generateAddOn(addOn.addOnId).max > 1">
                                                        <div class="booking-quantity__label">+ SGD {{ generateAddOn(addOn.addOnId).costPerUnit | money }}</div>
                                                        <div class="booking-quantity__inner">
                                                            <input type="button" class="qty-button qty-button__minus" @click="decrementAddOn($event, key)">
                                                            <input type="hidden" v-model="addOn.id">
                                                            <input type="text" class="form-control" v-model="addOn.quantity" placeholder="1">
                                                            <input type="button" class="qty-button qty-button__plus" @click="incrementAddOn($event, key)">
                                                        </div>
                                                    </div><!--product_quantity-->
                                                    <div class="booking-quantity" v-else>
                                                        <div class="booking-quantity__label">+ SGD {{ generateAddOn(addOn.addOnId).costPerUnit | money }}</div>
                                                        <ul class="checkbox_item checkbox_item__with-image checkbox_item__with-image__full">
                                                            <li>
                                                                <input type="checkbox" class="selectedAddOns" v-model="selectedAddOns" v-bind:value="addOn.id" :id="addOn.id" v-on:click="calculate($event)">
                                                                <label :for="addOn.id">&nbsp;</label>
                                                            </li>
                                                        </ul>
                                                    </div><!--product_quantity-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="booking__left__content__addons__content__shadow"></div>-->

                                    </div>

                                    <div class="form-group__info_block d-none d-lg-block" v-if="canShowAvailablePeriod">
                                        <div class="media">
                                            <div class="media-left">
                                                <img src="/images/frontend/info-circle-E9248C.svg" alt="">
                                            </div>
                                            <div class="media-body">
                                                <h3>There are no available periods for your selected quantity.</h3>
                                                <h4>Please lower your quantity or choose another space.</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group form-group__submit d-none d-lg-block">
                                        <input type="button" class="button button__primary button__agree" value="Next" @click="spaceBooking" >
                                    </div>

                                </div>

                            </div>
                            <input type="hidden" v-model="uiUpdator">
                        </form>

                    </div>
                </div>
                <div class="col-lg-5 sticky-right">
                    <div class="booking_summary" v-if="calculation">
                        <div class="booking_summary__title">
                            <h3>{{ selectedUnit.name }} - {{ space.name }}</h3>
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
                            <div class="ss_right">- SGD {{ item.beforeDiscount - item.afterDiscount | money }}</div>
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
                <div class="col-md-12  d-block d-lg-none" v-if="canShowAvailablePeriod">
                    <div class="form-group__info_block bottom_info">
                        <div class="media">
                            <div class="media-left">
                                <img src="/images/frontend/info-circle-E9248C.svg" alt="">
                            </div>
                            <div class="media-body">
                                <h3>There are no available periods for your selected quantity.</h3>
                                <h4>Please lower your quantity or choose another space.</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sticky-next d-block d-lg-none" style="" v-if="calculation">
                    <div class="float-left">
                        <h4>SGD {{ calculation.grandTotal | money }}</h4>
                        <p>Total Payable </p>
                    </div>
                    <div class="float-right">
                        <div class="form-group form-group__submit">
                            <input type="button" class="button button__primary button__agree" value="Next" @click="spaceBooking" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>

    import { debounce, flatMap, flatten, map, toArray } from 'lodash';
    import moment from 'moment';
    import Multiselect from 'vue-multiselect';

    Vue.component('multiselect', Multiselect);
    
    export default {
        props : [
            'space',
            'unit',
            'user',
            'redirectUrl',
        ],

        name : "SpaceBookings",

        data() {
            return {
                selectedUnit    : this.unit,
                selectedCoupon  : null,
                quantity        : 1,
                uiUpdator       : 1,
                selectedPeriods : [],
                coupons         : [],
                selectedAddOns  : [],
                isLoading       : false,
                calculation     : null,
                errors          : [],
                errordata       : 0,
            }
        },

        methods : {
            /**
             * Increment quantity by 1
             *
             * @return {int}
             */
            increment () {
                this.quantity++;
                this.calculate();
            },
            
            /**
             * Decrement quantity by 1
             *
             * @return {int}
             */
            decrement () {
                if(this.quantity > 1){
                    this.quantity--;
                    this.calculate();
                }
            },

            /**
             * Increment quantity by 1
             *
             * @return {int}
             */
            incrementAddOn(event, key) {
                this.selectedGroupedAddOns[key].quantity++;
                this.uiUpdator = this.makeUniqueKey();
                this.calculate();
            },
            
            /**
             * Decrement quantity by 1
             *
             * @return {int}
             */
            decrementAddOn(event, key) {
                if(this.selectedGroupedAddOns[key].quantity > 0) {
                    this.selectedGroupedAddOns[key].quantity--;
                    this.uiUpdator = this.makeUniqueKey();
                    this.calculate();
                }
            },
            
            /**
             * Prepare frontendName of grouped add-ons data.
             *
             * @return {string}
             */
            generateAddOnGroup(groupId) {
                for (var addOnGroup in this.selectedUnit.addOnGroups) {
                    if(this.selectedUnit.addOnGroups[addOnGroup].id == groupId) {
                        return this.selectedUnit.addOnGroups[addOnGroup].frontendName;
                    }
                }
            },
            
            /**
             *  Prepare an array of grouped add-ons data for API calls.
             *
             * @return {string}
             */
            generateAddOn(addOnId) {
                for (var addOnGroup in this.selectedUnit.addOnGroups) {
                    for (var addOn in this.selectedUnit.addOnGroups[addOnGroup].addOns) {
                        if(this.selectedUnit.addOnGroups[addOnGroup].addOns[addOn].id == addOnId) {
                            return {
                                frontendName : this.selectedUnit.addOnGroups[addOnGroup].addOns[addOn].frontendName,
                                description  : this.selectedUnit.addOnGroups[addOnGroup].addOns[addOn].description,
                                mediaFullUrl : this.selectedUnit.addOnGroups[addOnGroup].addOns[addOn].mediaFullUrl,
                                costPerUnit  : this.selectedUnit.addOnGroups[addOnGroup].addOns[addOn].pivot.cost_per_unit,
                                max          : this.selectedUnit.addOnGroups[addOnGroup].addOns[addOn].pivot.max,
                            };
                        }
                    }
                }
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
             * Prepare an array of period unit ids for API calls.
             *
             * @return {Array}
             */
            selectedPeriodUnitIdsApiData(periods) {
                return flatMap(periods, (period) => {
                    return period;
                })
            },

            /**
             * Prepare an array of grouped add-ons data for API calls.
             *
             * @return {string}
             */
            selectedGroupedAddOnsApiData(selectedAddOns) {
                return JSON.stringify(flatMap(this.selectedGroupedAddOns, (addOn) => {
                    if(selectedAddOns.includes(addOn.id)) {
                        return {
                          id       : addOn.id,
                          quantity : 1
                        };
                    } else {
                        return {
                          id       : addOn.id,
                          quantity : addOn.quantity
                        };
                    }
                }));
            },

            selectedAddOnsApiData(selectedAddOns) {
                return JSON.stringify(flatMap(selectedAddOns, (addOn) => {
                    return {
                        addOn
                    };
                }));
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

            updateGroupedAddOnQuantity(event, key) {
                this.selectedGroupedAddOns[key].quantity = event.target.value;
            },

            async calculate(event) {
                var selectedPeriods = this.selectedPeriods;
                var selectedAddOns  = this.selectedAddOns;
                if(event) {
                    if(event.target.className == 'selectedAddOns') {
                        if (event.target.checked) {
                            selectedAddOns.push(event.target.value);
                        } else {
                            for (var addOn in selectedAddOns) {
                                if(selectedAddOns[addOn] == event.target.value) {
                                    selectedAddOns.splice(addOn, 1);
                                }
                            }
                        }
                    } else {
                        if (event.target.checked) {
                            selectedPeriods.push(event.target.value);
                        } else {
                            for (var period in selectedPeriods) {
                                if(selectedPeriods[period] == event.target.value) {
                                    selectedPeriods.splice(period, 1);
                                }
                            }
                        }
                    }
                }

                var data = {
                  'unitId'        : this.selectedUnit.id,
                  'quantity'      : this.quantity,
                  'periodUnitIds' : this.selectedPeriodUnitIdsApiData(selectedPeriods),
                  'groupedAddOns' : this.selectedGroupedAddOnsApiData(this.selectedAddOnsApiData(selectedAddOns)),
                  'userId'        : this.user,
                };

                this.calculation = await axios.get('/api/calculate-booking', {
                    params : {
                        quantity      : this.quantity,
                        unitId        : this.selectedUnit.id,
                        periodUnitIds : this.selectedPeriodUnitIdsApiData(selectedPeriods),
                        groupedAddOns : this.selectedGroupedAddOnsApiData(this.selectedAddOnsApiData(selectedAddOns)),
                        userId        : this.user,
                    }
                })
                    .then(r => {
                        axios.get('/web/cart', {
                             params: {
                               unitId      : this.selectedUnit.id,
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
            spaceBooking() {
                window.location.href = this.redirectUrl;
            },
        },

        computed : {

            /**
             * Checks if there are add-ons to be displayed.
             *
             * @return {boolean}
             */
            canShowAddOns() {
                if (!this.selectedUnit) return false;
                
                if (this.selectedUnit.addOnGroups.length > 0) return true;
            },
            
            /**
             * Checks if there are periods to be checked.
             *
             * @return {boolean}
             */
            canShowAvailablePeriod() {
                if (!this.selectedUnit) return false;
                for (var period in this.selectedUnit.periods) {
                    if(this.selectedUnit.periods[period].pivot.remaining_quantity >= this.quantity) {
                        return false;
                    }
                }
                return true;
            },
            /**
             * Get add-ons items.
             *
             * @return {boolean}
             */
            selectedGroupedAddOns() {
                return flatMap(this.selectedUnit.addOnGroups, (group, key) => {
                    return flatMap(group.addOns, (addOn, key) => {
                        if (!key) {
                            return {
                              id       : addOn.pivot.id,
                              addOnId  : addOn.id,
                              quantity : 0,
                              groupId  : group.id
                            };
                        } else {
                            return {
                              id       : addOn.pivot.id,
                              addOnId  : addOn.id,
                              quantity : 0,
                              groupId  : 0
                            };
                        }
                    })
                })
            },

            canCalculate() {
                return this.selectedUnit &&
                    this.quantity &&
                    this.selectedPeriods.length > 0;
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
                return new Intl.NumberFormat('en-SG').format(amount / 100);
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>