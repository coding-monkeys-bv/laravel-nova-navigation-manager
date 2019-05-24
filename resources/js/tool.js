Nova.booting((Vue, router) => {

    Vue.config.devtools = true
    
    router.addRoutes([
        {
            name: 'navigation',
            path: '/navigation',
            component: require('./components/Tool'),
        },
    ])
})
