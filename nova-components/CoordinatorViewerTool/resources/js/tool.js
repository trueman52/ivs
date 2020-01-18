Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'coordinator-viewer-tool',
      path: '/coordinator-viewer-tool',
      component: require('./components/Tool'),
    },
  ])
})