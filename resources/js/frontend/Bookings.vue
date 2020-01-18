<template>
    <section>
        <section class="booking_list" v-if="bookings.length > 0">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="booking_list__card" v-for="booking in bookings">
                            <div class="booking_list__card__image">
                                <a href="#" :style="'background-image: url('+booking.unitImage+');'"></a>
                            </div><!--eatbox_photo-->
                            <div class="booking_list__card__content">
                                <div class="booking_list__card__content__left">
                                    <h3><a href="#">{{ booking.id }}</a></h3>
                                    <h4>{{ booking.unitName }}</h4>
                                    <ul>
                                        <li>Booking Date: <span>{{ booking.createdAt | parseDbDate }}</span></li>
                                        <li>Paid <span>${{ booking.paid | money }}</span></li>
                                        <li>Balance <span>${{ booking.outstanding | money }}</span></li>
                                    </ul>

                                </div><!--eatbox_info_left-->
                                <div class="booking_list__card__content__right">
                                    <h5 :class="booking.status">{{ booking.status }}</h5>
                                    <div class="book_now_btn"><a href="#" class="button button__primary button__view">View</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="favorite" style="background-image: url(/images/frontend/pagebg.png)" v-else>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="favorite__empty">
                            <h2>You haven’t made any bookings yet!</h2>
                            <p>Currently, there are exciting events happening around in town. Why not check those out first?</p>
                            <a href="/spaces" class="button button__primary button__goingon">See What’s Going On</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </section>

</template>

<script>

    import moment from 'moment';

    export default {
        props : [
        ],

        data() {
            return {
              editMode   : false,
              query      : "",
              queryFiled : "name",
              bookings   : [],
            };
        },

        watch: {
          query: function(newQ, old) {
            this.getData();
          }
        },

        mounted() {
          this.getData();
        },

        methods: {
          getData() {
            axios
              .get("/web/bookings")
              .then(response => {
                  this.bookings = response.data.bookings;
              })
              .catch(e => {
                  console.log(e);
              });
          }
        },

        filters : {
            parseDbDate : (date) => {
                if (!date) return '';

                return moment(date).format('DD MMM, YYYY, hh:mm A')
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
    };
</script>