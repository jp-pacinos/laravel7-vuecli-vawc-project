<script>
// import { apiClient } from '@/services'

export default {
    created() {
        // apiClient.get('/sanctum/csrf-cookie', {
        //     baseURL: baseURL
        // })
        // apiClient.get('/sanctum/csrf-cookie', {
        //     baseURL: ''
        // })
    }
}
</script>

<template>
    <v-fade-transition hide-on-leave>
        <router-view></router-view>
    </v-fade-transition>
</template>

<style lang="scss" scoped></style>
