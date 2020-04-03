import { apiClient } from '@/services'

export default {
    getAccount() {
        return apiClient.get('/user')
    },

    update(id, user) {
        return apiClient.post(`/user/account/${id}`, user)
    }
}
