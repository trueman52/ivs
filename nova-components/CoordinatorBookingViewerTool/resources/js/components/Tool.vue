<template>
    <div>
        <heading class="mb-6 booking-mb" v-if="unit">
            <router-link :to="{name: 'coordinator-viewer-tool'}">
                          My Assignments /
            </router-link>
            <router-link :to="{
                            name: 'coordinator-unit-viewer-tool',
                            query: {space: unit.spaceId}
                          }">
                          {{ unit.space.name }} /
            </router-link>
            {{ unit.periodDate }}
        </heading>

        <div class="flex flex-wrap space-card" v-if="unit">
            <div class="w-full md:w-1/2 xl:w-1/4 px-4 py-3" v-for="booking in bookings">
                <div class="bg-white">
                    <h2 class="font-bold flex-auto">{{ booking.customer.fullName }} - {{ booking.customer.profileContactNumber }}</h2>
                    <p class="font-light flex-auto" v-for="bookingAddOn in booking.bookingAddOns">{{ bookingAddOn.groupedAddOn.addOn.backendName }} x {{ bookingAddOn.quantity }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { parse } from 'query-string'

    export default {
        data() {
            return {
                endpoint : '/nova-vendor/coordinator-booking-viewer-tool',
                unit     : '',
                bookings : [],
            }
        },

        methods : {
            initDataFromQuery() {
                const parsed = parse(location.search);

                this.getUnit(parsed.unit);

                this.getBookings(parsed.unit);
            },

            getUnit(id) {
                axios.get(`${this.endpoint}/units/${id}`)
                     .then((r) => {
                         this.unit = r.data;
                     })
            },
            
            getBookings(id) {
                axios.get(`${this.endpoint}/bookings/${id}`)
                     .then((r) => {
                         this.bookings = r.data;
                     })
            },
        },

        created() {
            this.initDataFromQuery();
        },
    }
</script>

<style>
/* Scoped Styles */
</style>
