import Cookies from 'js-cookie'
import AuthService from '@/services/AuthService'
import { apiClient } from '@/services'

export const namespaced = true

export const state = {
    token: Cookies.get('token')
}

export const mutations = {
    SET_TOKEN(state, token) {
        state.token = token
        Cookies.set('token', token, { expires: 2 })
    },

    REMOVE_TOKEN(state) {
        state.token = null
        Cookies.remove('token')
    }
}

export const actions = {
    saveToken({ commit, dispatch }, token) {
        commit('SET_TOKEN', token)
        dispatch('activateToken')
    },

    activateToken({ state, commit }) {
        apiClient.defaults.headers.common[
            'Authorization'
        ] = `Bearer ${state.token}`

        apiClient.interceptors.response.use(
            response => response, // simply return the response
            error => {
                if (error.response.status === 401) {
                    // if we catch a 401 error
                    // force a log out
                    commit('REMOVE_TOKEN')
                    location.reload()
                }
                return Promise.reject(error) // reject the Promise, with the error as the reason
            }
        )
    },

    logout({ commit, dispatch }) {
        commit('REMOVE_TOKEN')
        dispatch('app/progressBar', true, { root: true })
        AuthService.logout().finally(() => location.reload())
    }
}

export const getters = {}
