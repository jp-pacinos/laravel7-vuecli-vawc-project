import { apiClient } from '@/services'

export default {
    getRecords(options = {}) {
        return apiClient.get(
            '/records?' + new URLSearchParams(options).toString()
        )
    },

    getRecord(id) {
        return apiClient.get('/records/' + id)
    },

    updateRecord(id, newRecord) {
        return apiClient.post('/records/' + id, {
            ...newRecord,
            _method: 'PATCH'
        })
    },

    deleteRecord(id) {
        return apiClient.post('/records/' + id, { _method: 'DELETE' })
    }
}
