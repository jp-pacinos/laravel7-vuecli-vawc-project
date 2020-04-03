import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'
import routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes
})

// document title
router.afterEach(to => {
    Vue.nextTick(() => {
        document.title = to.meta.title || 'VAWC'
    })
})

// auth guard
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        !store.state.auth.token ? next({ name: 'auth.login' }) : next()
    } else if (to.matched.some(record => record.meta.requiresGuest)) {
        // authenticated but trying to access guest sites
        store.state.auth.token ? next({ name: 'admin.home' }) : next()
    } else {
        next() // make sure to always call next()!
    }
})

export default router
