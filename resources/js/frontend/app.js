
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('home-page-banner', require('./HomePageBanner.vue').default);
Vue.component('home-page-event', require('./HomePageEvent.vue').default);
Vue.component('upcoming-space', require('./UpcomingSpace.vue').default);
Vue.component('past-space', require('./PastSpace.vue').default);
Vue.component('space-details', require('./SpaceDetails.vue').default);
Vue.component('pagination', require('./partial/PaginationComponent.vue').default);
Vue.component('register', require('./Register.vue').default);
Vue.component('login', require('./Login.vue').default);
Vue.component('forget-password', require('./ForgetPassword.vue').default);
Vue.component('forget-password-checkemail', require('./ForgetPasswordCheckemail.vue').default);
Vue.component('reset-password', require('./ResetPassword.vue').default);
Vue.component('verify-email', require('./VerifyEmail.vue').default);
Vue.component('account', require('./Account.vue').default);
Vue.component('profile', require('./Profile.vue').default);
Vue.component('business-information', require('./BusinessInformation.vue').default);
Vue.component('bank-account', require('./BankAccount.vue').default);
Vue.component('bank-account-form', require('./BankAccountForm.vue').default);
Vue.component('empty-favourite', require('./EmptyFavourite.vue').default);
Vue.component('favourites', require('./Favourites.vue').default);
Vue.component('space-booking', require('./SpaceBooking.vue').default);
Vue.component('space-thingstonote', require('./SpaceThingstonote.vue').default);
Vue.component('checkout', require('./Checkout.vue').default);
Vue.component('bookings', require('./Bookings.vue').default);

const app = new Vue({
    el: '#app'
});
