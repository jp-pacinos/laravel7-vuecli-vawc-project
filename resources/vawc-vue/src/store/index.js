import Vue from 'vue'
import Vuex from 'vuex'
import * as app from './modules/app'
import * as rules from './modules/rules'
import * as auth from './modules/auth'
import * as records from './modules/records'
import * as account from './modules/account'
import * as vawcForm from './modules/vawcForm'

Vue.use(Vuex)

export default new Vuex.Store({
    strict: true, // dev

    modules: {
        app,
        rules,
        auth,
        records,
        account,
        vawcForm
    }
})
