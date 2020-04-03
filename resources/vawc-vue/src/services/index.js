import axios from 'axios'

// export const baseURL = process.env.BASE_URL
//     ? 'http://app.vawc'
//     : 'http://127.0.0.1:8000'

export const apiClient = axios.create({
    baseURL: '/api', //`${baseURL}/api`,
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json'
    }
})
