import AuthService from '@/services/AuthService'
import Cookies from 'js-cookie'
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

    activateToken({ state }) {
        apiClient.defaults.headers.common[
            'Authorization'
        ] = `Bearer ${state.token}`
    },

    logout({ commit }) {
        commit('REMOVE_TOKEN')
        AuthService.logout()
    }
}

export const getters = {}
