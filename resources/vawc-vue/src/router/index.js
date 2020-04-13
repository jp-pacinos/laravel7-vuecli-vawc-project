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
    const loggedIn = store.state.auth.token

    if (to.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        next({ name: 'auth.login' })
    } else if (
        to.matched.some(record => record.meta.requiresGuest) &&
        loggedIn
    ) {
        // authenticated but trying to access guest sites
        next({ name: 'admin.home' })
    }

    next() // make sure to always call next()!
})

export default router
