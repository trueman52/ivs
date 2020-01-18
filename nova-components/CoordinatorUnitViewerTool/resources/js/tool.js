Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'coordinator-unit-viewer-tool',
      path: '/coordinator-unit-viewer-tool',
      component: require('./components/Tool'),
    },
  ])
})
