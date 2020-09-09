export const namespaced = true

export const state = {
    // select, combobox data
    sex: [
        { text: 'Male', value: 1 },
        { text: 'Female', value: 2 }
    ],

    suffix: ['Jr', 'Sr'],

    relation: ['Brother', 'Sister', 'Witness'],

    rules: {
        titleCounter: value =>
            value ? value.length <= 80 || 'Max 80 characters' : true
    }
}

export const mutations = {}

export const actions = {}

export const getters = {}
