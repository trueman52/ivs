Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'coordinator-booking-viewer-tool',
      path: '/coordinator-booking-viewer-tool',
      component: require('./components/Tool'),
    },
  ])
})
