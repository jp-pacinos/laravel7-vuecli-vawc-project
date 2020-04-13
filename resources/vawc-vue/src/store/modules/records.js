import { shortFullName } from '@/utils/fullname'
import { longDateTime } from '@/utils/datetime'
import RecordService from '@/services/RecordService'
import router from '@/router'

export const namespaced = true

export const state = {
    tblHeaders: [
        {
            text: 'Complainant LN/FN/MI',
            value: 'user'
        },
        { text: 'Description', value: 'title' },
        {
            text: 'Complain Date',
            align: 'start',
            value: 'created_at'
        },
        { text: 'Actions', value: 'actions', sortable: false }
    ],
    tblOptions: {},
    tblLoading: false,

    tblItems: [], // records
    tblTotalItems: 0,

    record: {}, // target record on view
    viewRecordHeader: {},

    deleteDialog: false
}

export const mutations = {
    SET_TABLE_OPTIONS(state, payload) {
        state.tblOptions = payload
    },

    SET_TABLE_LOADING(state, payload) {
        state.tblLoading = payload
    },

    SET_TABLE_ITEMS(state, payload) {
        state.tblItems = payload
    },

    SET_TABLE_TOTAL_ITEMS(state, payload) {
        state.tblTotalItems = payload
    },

    SET_RECORD(state, record) {
        state.record = record
    },

    VIEW_RECORD_HEADER(state, record) {
        state.viewRecordHeader = null
        state.viewRecordHeader = record
    },

    DELETE_DIALOG(state, bool) {
        state.deleteDialog = bool
    },

    TABLE_LOADING(state, bool) {
        state.table.loading = bool
    }
}

export const actions = {
    fetchRecords({ dispatch, commit }, options = {}) {
        dispatch('tableLoading', true)

        RecordService.getRecords(options)
            .then(({ data }) => {
                commit('SET_TABLE_TOTAL_ITEMS', data.total)

                commit(
                    'SET_TABLE_ITEMS',
                    data.data.map(obj => {
                        return {
                            id: obj.record_id,
                            title: obj.title,
                            user: shortFullName({
                                lastname: obj.lastname,
                                firstname: obj.firstname,
                                middlename: obj.middlename,
                                suffix: obj.suffix
                            }),
                            created_at: longDateTime(obj.created_at)
                        }
                    })
                )
            })
            .catch(error => {
                dispatch(
                    'app/snackbar',
                    {
                        text:
                            'There was an error on fetching all records. Message: ' +
                            error.message,
                        y: 'bottom',
                        timeout: 0
                    },
                    { root: true }
                )
            })
            .finally(() => {
                dispatch('tableLoading', false)
            })
    },

    tableLoading({ commit }, bool = true) {
        commit('SET_TABLE_LOADING', bool)
    },

    deleteRecord({ state, commit, dispatch }) {
        commit('DELETE_DIALOG', false)
        commit('TABLE_LOADING', true)

        dispatch(
            'app/snackbar',
            { text: 'Deleting...', y: 'bottom' },
            { root: true }
        )

        RecordService.deleteRecord(state.record.id)
            .then(() => {
                commit(
                    'SET_RECORDS_DATA',
                    state.records.data.filter(obj => obj.id !== state.record.id)
                )

                dispatch(
                    'app/snackbar',
                    { text: 'Record deleted.', y: 'bottom' },
                    { root: true }
                )

                if (state.record.redirect) router.go(-1)
            })
            .catch(error => {
                let errorMessage = `There was an error on removing that record: ${error.message}`

                dispatch(
                    'app/snackbar',
                    { text: errorMessage, y: 'bottom' },
                    { root: true }
                )
            })
            .finally(() => {
                commit('TABLE_LOADING', false)
            })
    },

    deleteItem({ commit }, payLoad) {
        commit('SET_RECORD', payLoad) // { id: record_id }
        commit('DELETE_DIALOG', true)
    },

    setViewRecordHeader({ commit }, record) {
        commit('VIEW_RECORD_HEADER', record)
    }
}

export const getters = {}
