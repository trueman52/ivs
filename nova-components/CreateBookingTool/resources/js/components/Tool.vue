<template>
    <div>
        <heading class="mb-6">Create Booking</heading>

        <!-- Customer details-->
        <div class="bg-30 p-4 text-90">
            Search Existing Customer
        </div>
        <div class="bg-white">
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label for="searchCustomer" class="inline-block text-80 pt-2 leading-tight">
                        Search Customer
                    </label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <multiselect v-model="selectedCustomer"
                                 label="email"
                                 track-by="email"
                                 placeholder="Search customers' email"
                                 open-direction="bottom"
                                 :options="customers"
                                 :multiple="false"
                                 :searchable="true"
                                 :loading="isLoading"
                                 :clear-on-select="true"
                                 :close-on-select="true"
                                 :options-limit="300"
                                 :max-height="600"
                                 :show-no-results="true"
                                 @search-change="searchCustomers">
                        <template slot="singleLabel" slot-scope="props">
                            <div class="option__title">
                                {{ props.option.email }}
                                <span class="text-sm text-80">(ID: #{{ props.option.id }})</span>
                            </div>
                        </template>
                        <template slot="option" slot-scope="props">
                            <div class="option__title">
                                {{ props.option.email }} <span class="text-sm">(ID: #{{ props.option.id }})</span>
                            </div>
                        </template>
                    </multiselect>
                    <p class="text-sm text-80 mt-2">Type in 3 or more characters to perform search.</p>
                </div>
            </div>
        </div>
        <!-- End customer details -->

        <!-- Booking details -->
        <div class="bg-30 p-4 text-90">Booking Details</div>

        <div class="bg-white">
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Space</label>
                </div>
                <div class="w-1/2 py-6 px-8">
                    <multiselect v-model="selectedSpace"
                                 @select="getCoupons"
                                 label="name"
                                 track-by="name"
                                 :options="spaces"
                                 :searchable="true"
                                 :loading="isLoading"
                                 :clear-on-select="true"
                                 :close-on-select="true"
                                 :max-height="600"
                                 :show-no-results="true"
                                 @search-change="searchSpaces">
                        <template slot="singleLabel" slot-scope="props">
                            <div class="option__title">
                                {{ props.option.name }}
                                <span class="text-sm text-80">(ID: #{{ props.option.id }})</span>
                            </div>
                        </template>
                        <template slot="option" slot-scope="props">
                            <div class="option__title">
                                {{ props.option.name }}
                                <span class="text-sm">(ID: #{{ props.option.id }})</span>
                            </div>
                        </template>
                    </multiselect>
                    <p class="text-sm text-80 mt-2">Type in 3 or more characters to perform search.</p>
                </div>
            </div>

            <div v-if="selectedSpace">
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight">Unit</label>
                    </div>
                    <div class="w-1/2 py-6 px-8">
                        <multiselect v-model="selectedUnit"
                                     label="name"
                                     track-by="name"
                                     placeholder="Choose a unit"
                                     :options="selectedSpace.units"
                                     :show-no-results="true">
                        </multiselect>
                    </div>
                </div>

                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight">Quantity</label>
                    </div>
                    <div class="w-1/2 py-6 px-8">
                        <input class="w-full form-control form-input form-input-bordered" v-model="quantity">
                    </div>
                </div>
            </div>

            <div v-if="selectedUnit">
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight">Period</label>
                    </div>
                    <div class="w-1/2 py-6 px-8">
                        <multiselect v-model="selectedPeriods"
                                     :custom-label="periodLabel"
                                     track-by="id"
                                     placeholder="Select one or more periods"
                                     :options="selectedUnit.periods"
                                     :multiple="true"
                                     :hide-selected="true"
                                     :close-on-select="false"
                                     :clear-on-select="false"
                                     :preserve-search="true"
                                     :show-no-results="true">
                            <template slot="option" slot-scope="props">
                                <div class="option__title">
                                    {{ props.option.fromDate | parseDbDate }} - {{ props.option.toDate | parseDbDate }}
                                </div>
                            </template>
                        </multiselect>
                    </div>
                </div>
            </div>

            <div v-if="canShowAddOns">
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight">Add add-on</label>
                    </div>
                    <div class="w-3/4 py-6 px-8 self-end">
                        <div class="-mx-2 flex flex-wrap pb-3"
                             v-for="(addOn, key) in selectedGroupedAddOns"
                             :key="addOn.key">
                            <div class="px-2 w-1/3">
                                <multiselect v-model="selectedGroupedAddOns[key].addOn"
                                             label="frontendName"
                                             track-by="id"
                                             placeholder="Select an add-on"
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
                                       @change="updateGroupedAddOnQuantity($event, key)">
                            </div>

                            <div class="px-2 w-1/3">
                                <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                                     @click="removeAddOnRow(key)">
                                    Remove
                                </div>
                            </div>
                        </div>

                        <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                             @click="insertAddOnRow">Add add-on
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="coupons.length > 0">
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight">Add coupon</label>
                    </div>
                    <div class="w-1/2 py-6 px-8">
                        <multiselect v-model="selectedCoupon"
                                     :custom-label="couponLabel"
                                     track-by="id"
                                     placeholder="Choose a coupon"
                                     :options="coupons"
                                     :close-on-select="true"
                                     :clear-on-select="false"
                                     :show-no-results="true">
                            <template slot="option" slot-scope="props">
                                <div class="option__title">{{ props.option.data.title }}</div>
                            </template>
                        </multiselect>
                    </div>
                </div>
            </div>

            <div class="flex border-b border-40">
                <div class="w-1/5 py-6 px-8">
                    <label class="inline-block text-80 pt-2 leading-tight">Add adhoc item</label>
                </div>
                <div class="w-4/5 py-6 px-8 -mx-2 self-end">
                    <div v-if="adhocItems.length > 0">
                        <div v-for="(item, key) in adhocItems" :key="item.key" class="flex flex-wrap pb-3">
                            <div class="px-2 w-1/4">
                                <input class="w-full form-control form-input form-input-bordered px-2 w-1/3"
                                       placeholder="Item name"
                                       type="text"
                                       @change="updateAdhocItem($event.target.value, key, 'name')">
                            </div>
                            <div class="px-2 w-1/4">
                                <input class="w-full form-control form-input form-input-bordered px-2 w-1/3"
                                       placeholder="S$"
                                       type="text"
                                       @change="updateAdhocItem($event.target.value, key, 'amount')">
                            </div>
                            <div class="px-2 w-1/4">
                                <input class="w-full form-control form-input form-input-bordered"
                                       placeholder="Qty"
                                       type="text"
                                       @change="updateAdhocItem($event.target.value, key, 'quantity')">
                            </div>
                            <div class="px-2 w-1/4">
                                <div class="text-primary font-semibold p-2 cursor-pointer inline-block"
                                     @click="removeAdhocRow(key)">
                                    Remove
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-primary font-semibold p-2 cursor-pointer inline-block" @click="insertAdhocRow">
                        Add adhoc item
                    </div>
                </div>
            </div>
        </div>
        <!-- End booking details -->

        <div class="mt-6 btn btn-primary btn-default"
             :class="{
                'cursor-disabled': !canCalculate,
                'btn-disabled' : !canCalculate
            }"
             @click="calculate">
            Confirm booking
        </div>

        <div role="mt-7 alert" v-if="errors.length > 0">
            <div class="bg-danger text-white font-bold rounded-t px-4 py-2 mt-8">
                Validation error
            </div>
            <div class="border border-t-0 border-danger rounded-b bg-red-100 px-4 py-3 bg-white">
                Please fix the following before proceeding.

                <ul class="list-disc">
                    <li v-for="error in errors">
                        {{ error }}
                    </li>
                </ul>
            </div>
        </div>

        <v-modal name="confirm"
                 height="auto"
                 :adaptive="true"
                 :scrollable="true">
            <div class="p-8">
                <h2 class="text-2xl font-normal">Confirm booking for customer</h2>

                <div class="mt-4" v-if="selectedCustomer">
                    <div class="border border-30">
                        <div class="bg-30 p-4 text-90">
                            Customer details
                        </div>
                        <div class="p-4 text-sm">
                            <div class="flex flex-wrap">
                                <td class="pt-2 w-1/3">First name</td>
                                <td class="pt-2 w-2/3">{{ selectedCustomer.firstName }}</td>
                            </div>
                            <div class="flex flex-wrap">
                                <td class="pt-2 w-1/3">Last name</td>
                                <td class="pt-2 w-2/3">{{ selectedCustomer.lastName }}</td>
                            </div>
                            <div class="flex flex-wrap">
                                <td class="pt-2 w-1/3">Email</td>
                                <td class="pt-2 w-2/3">{{ selectedCustomer.email }}</td>
                            </div>
                            <div class="flex flex-wrap">
                                <td class="pt-2 w-1/3">Contact number</td>
                                <td class="pt-2 w-2/3"></td>
                            </div>
                        </div>
                    </div>
                </div>

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
                    <button @click="$modal.hide('confirm');"
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
    import { debounce, flatMap, flatten, map, toArray } from 'lodash';
    import moment from 'moment';

    const endpoint = '/nova-vendor/create-booking-tool';

    export default {
        data() {
            return {
                selectedCustomer : null,
                selectedSpace : null,
                selectedUnit : null,
                selectedCoupon : null,
                quantity : 1,
                selectedPeriods : [],
                selectedGroupedAddOns : [],
                adhocItems : [],
                customers : [],
                spaces : [],
                coupons : [],
                isLoading : false,
                calculation : null,
                errors : [],
            }
        },

        methods : {
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
            selectedPeriodUnitIdsApiData() {
                return flatMap(this.selectedPeriods, (period) => {
                    return period.pivot.id;
                })
            },

            /**
             * Prepare an array of grouped add-ons data for API calls.
             *
             * @return {string}
             */
            selectedGroupedAddOnsApiData() {
                return JSON.stringify(flatMap(this.selectedGroupedAddOns, (addOn) => {
                    return {
                        id : addOn.addOn.pivot.id,
                        quantity : addOn.quantity,
                    };
                }));
            },

            /**
             * Prepare an array of added ad-hoc items data for API calls.
             *
             * @return {string}
             */
            addedAdhocItemsApiData() {
                let adhocItems = map(this.adhocItems, (item) => {
                    // map all properties when trying to convert the
                    // amounts to cents, the original object gets mutated.
                    let duplicate = {...item}

                    // convert amount to cents
                    duplicate.amount *= 100;

                    return duplicate;
                });

                return JSON.stringify(adhocItems);
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
             * Return the label used by coupon's select.
             *
             * @param {object} data
             * @return {string}
             */
            couponLabel({data}) {
                return data.title;
            },

            /**
             * Search for existing customers in database.
             *
             * @param string keyword
             */
            searchCustomers : debounce(function (keyword) {
                if (!this.canSearch(keyword)) return;

                this.isLoading = true;

                axios.get(`${endpoint}/customers`, {
                    params : {keyword}
                })
                    .then(r => {
                        this.customers = r.data.users;
                    })
                    .catch((e) => {
                        this.$toasted.error('Unable to retrieve customers');
                    })
                    .then(() => {
                        this.isLoading = false;
                    })
            }, 300),

            /**
             * Search for spaces in database, and retrieve all
             * other information related to space.
             *
             * @param event
             */
            searchSpaces : debounce(function (keyword) {
                if (!this.canSearch(keyword)) return;

                this.isLoading = true;

                axios.get(`${endpoint}/spaces`, {
                    params : {keyword}
                })
                    .then(r => {
                        this.spaces = r.data.spaces;
                    })
                    .catch(() => {
                        this.$toasted.error('Unable to retrieve spaces');
                    })
                    .then(() => {
                        this.isLoading = false;
                    })
            }),

            /**
             * Get all usable coupons for this space.
             */
            getCoupons(space) {
                axios.get(`${endpoint}/coupons`, {
                    params : {
                        spaceId : space.id
                    }
                })
                    .then(r => {
                        this.coupons = r.data.coupons;
                    })
                    .catch(() => {
                        this.$toasted.error('Unable to retrieve coupons');
                    })
            },

            /**
             * Checks if we can begin searching.
             */
            canSearch(string) {
                return string.length >= 3;
            },

            /**
             * Add a new row to insert new add-on.
             */
            insertAddOnRow() {
                this.selectedGroupedAddOns.push({key : this.makeUniqueKey()});
            },

            /**
             * Remove adhoc items by index.
             */
            removeAddOnRow(key) {
                this.$delete(this.selectedGroupedAddOns, key);
            },

            /**
             * Remove adhoc items by index.
             */
            removeAdhocRow(key) {
                this.$delete(this.adhocItems, key);
            },

            /**
             * Add a new row to insert new adhoc item.
             */
            insertAdhocRow() {
                this.adhocItems.push({key : this.makeUniqueKey()});
            },

            /**
             * Update adhoc item's properties
             *
             * @param {mixed} value
             * @param {int} key
             * @param {string} prop
             */
            updateAdhocItem(value, key, prop) {
                this.adhocItems[key][prop] = value;
            },


            updateGroupedAddOnQuantity(event, key) {
                this.selectedGroupedAddOns[key].quantity = event.target.value;
            },

            async calculate() {
                let couponCode = '';

                if (!!this.selectedCoupon) {
                    couponCode = this.selectedCoupon.code;
                }

                this.calculation = await axios.get('/api/calculate-booking', {
                    params : {
                        quantity : this.quantity,
                        unitId : this.selectedUnit.id,
                        periodUnitIds : this.selectedPeriodUnitIdsApiData(),
                        groupedAddOns : this.selectedGroupedAddOnsApiData(),
                        couponCode,
                        adhocItems : this.addedAdhocItemsApiData(),
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

            save() {
                let couponCode = '';

                if (!!this.selectedCoupon) {
                    couponCode = this.selectedCoupon.code;
                }

                axios.post(`${endpoint}/bookings`, {
                    userId : this.selectedCustomer.id,
                    quantity : this.quantity,
                    unitId : this.selectedUnit.id,
                    periodUnitIds : this.selectedPeriodUnitIdsApiData(),
                    groupedAddOns : this.selectedGroupedAddOnsApiData(),
                    couponCode,
                    adhocItems : this.addedAdhocItemsApiData(),
                })
                    .then(r => {
                        this.$router.push({
                            name : 'detail',
                            params : {
                                resourceName : 'bookings',
                                resourceId : r.data.booking.id
                            }
                        })
                    })
                    .catch(e => {
                        this.$toasted.error(e.response.data.message)
                    })
            }
        },

        computed : {
            /**
             * Checks if there are spaces to choose from.
             *
             * @return {boolean}
             */
            hasSpaceResults() {
                return this.spaces.length > 0;
            },

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
             * Get add-ons as selectable options.
             *
             * @return {Array}
             */
            selectableAddOns() {
                return flatMap(this.selectedUnit.addOnGroups, (group) => {
                    return group.addOns;
                })
            },

            canCalculate() {
                return this.selectedCustomer &&
                    this.selectedSpace &&
                    this.selectedUnit &&
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
                return new Intl.NumberFormat('en-SG', {
                    style : 'currency',
                    currency : 'SGD'
                }).format(amount / 100);
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
