import { apiClient } from '@/services'

export default {
    login(user) {
        return apiClient.post('/login', user)
    },

    logout() {
        return apiClient.post('/logout')
    }
}
