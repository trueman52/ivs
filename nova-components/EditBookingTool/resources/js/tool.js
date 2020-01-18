import Multiselect from 'vue-multiselect'
import VModal from 'vue-js-modal'

Nova.booting((Vue, router, store) => {
    Vue.component('multiselect', Multiselect);
    Vue.use(VModal, {
        componentName : 'v-modal',
        dynamic : true,
        injectModalsContainer : true
    });

    // When user wants to edit an existing booking, we redirect them to
    // use the tool instead of using nova's default create form.
    router.beforeEach((to, from, next) => {
        if (to.params.resourceName === 'bookings' && to.name === 'edit') {
            router.push({
                'name' : 'edit-booking',
                'params': {
                    id: to.params.resourceId
                }
            });

            return;
        }

        next();
    });

    router.addRoutes([
        {
            name : 'edit-booking',
            path : '/edit-booking/:id',
            component : require('./components/Tool'),
        },
    ])
})
