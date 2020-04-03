import { apiClient } from '@/services'

export default {
    submit(data) {
        return apiClient.post('/guest/complains/create', data)
    }
}
