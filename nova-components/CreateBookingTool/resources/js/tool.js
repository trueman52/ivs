import Multiselect from 'vue-multiselect'
import VModal from 'vue-js-modal'

Nova.booting((Vue, router, store) => {
    // register globally
    Vue.component('multiselect', Multiselect);
    Vue.use(VModal, {
        componentName: 'v-modal',
        dynamic: true,
        injectModalsContainer: true
    });

    // When user wants to create a new booking, we redirect them to
    // use the tool instead of using laravel's default create form.
    router.beforeEach((to, from, next) => {
        if (to.path === '/resources/bookings/new') {
            router.push({'name' : 'create-booking-tool'});

            return;
        }

        next();
    });

    router.addRoutes([
        {
            name: 'create-booking-tool',
            path: '/create-booking-tool',
            component: require('./components/Tool'),
        },
    ]);
})
