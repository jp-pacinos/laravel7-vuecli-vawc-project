const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/views/UserComplainForm.vue'),
        meta: { title: 'VAWC | Complain Form' }
    },
    {
        path: '/auth/login',
        name: 'auth.login',
        component: () => import('@/views/AuthLogin.vue'),
        meta: { requiresGuest: true, title: 'VAWC | Admin Login' }
    },
    {
        path: '/admin',
        component: () => import('@/views/admin/IndexLayout.vue'),
        children: [
            {
                path: '',
                name: 'admin.home',
                redirect: { name: 'admin.records' }
            },
            {
                path: 'records',
                name: 'admin.records',
                component: require('@/views/admin/Records.vue').default
            },
            {
                path: 'records/:id',
                name: 'admin.records.view',
                component: require('@/views/admin/RecordsView.vue').default,
                props: true
            },
            {
                path: 'export',
                name: 'admin.export',
                component: require('@/views/admin/Export.vue').default
            },
            {
                path: 'account',
                name: 'admin.account',
                component: require('@/views/admin/Account.vue').default
            }
        ],
        meta: { requiresAuth: true, title: 'VAWC | Admin' }
    }

    // { path: '*', component: '' }
]

export default routes
