import { apiClient } from '@/services'

export default {
    generate(options = {}) {
        var query = `?type=${options.type}&start=${options.dateRange[0]}&end=${options.dateRange[1]}`

        return apiClient.get('/exports/records' + query, {
            responseType: 'blob'
        })
    }
}
