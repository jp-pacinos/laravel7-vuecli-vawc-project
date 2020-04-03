export const namespaced = true

export const state = {
    progressBar: false,

    snackbar: {
        color: '',
        mode: '',
        show: false,
        text: "Hello, I'm a snackbar",
        timeout: 6000,
        x: null,
        y: 'top'
    }
}

export const mutations = {
    SET_PROGRESS_BAR(state, value) {
        state.progressBar = value
    },

    SET_SNACK_BAR(state, value) {
        state.snackbar = value
    },

    SET_SHOW_SNACK_BAR(state, value) {
        state.snackbar.show = value
    }
}

export const actions = {
    snackbar({ commit }, value) {
        const options = {
            color: value.color ? value.color : '',
            mode: value.mode ? value.mode : '',
            text: value.text ? value.text : '',
            timeout: value.timeout ? value.timeout : 6000,
            show: true,
            x: value.x ? value.x : null,
            y: value.y ? value.y : 'top'
        }

        commit('SET_SNACK_BAR', options)
    },

    progressBar({ commit }, bool) {
        commit('SET_PROGRESS_BAR', bool)
    }
}

export const getters = {}
