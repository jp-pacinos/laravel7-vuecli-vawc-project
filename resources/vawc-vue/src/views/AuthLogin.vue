<script>
import { mapActions } from 'vuex'
import AuthService from '@/services/AuthService'
import Snackbar from '@/components/Snackbar'

export default {
    components: {
        Snackbar
    },

    data() {
        return {
            username: null,
            password: null,
            loading: false,
            form: true
        }
    },

    methods: {
        ...mapActions('app', ['snackbar']),

        login() {
            this.loading = true

            AuthService.login({
                email: this.username,
                password: this.password
            })
                .then(response => {
                    this.$store.dispatch('auth/saveToken', response.data.token)
                    this.$router.push({ name: 'admin.home' })
                })
                .catch(error => {
                    var message = ''

                    if (error.response) {
                        message = error.response.data.error
                    } else if (error.message) {
                        message = error.message
                    } else {
                        message = 'Something went wrong'
                    }

                    this.snackbar({
                        color: 'red',
                        text: message,
                        y: 'bottom'
                    })
                })
                .finally(() => (this.loading = false))
        }
    }
}
</script>

<template>
    <v-app id="inspire">
        <v-content>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4">
                        <v-card class="elevation-12">
                            <v-toolbar color="primary" dark flat>
                                <v-toolbar-title>
                                    Authentication
                                </v-toolbar-title>

                                <v-progress-linear
                                    :active="loading"
                                    :indeterminate="loading"
                                    absolute
                                    bottom
                                    color="yellow accent-4"
                                ></v-progress-linear>
                            </v-toolbar>

                            <v-card-text>
                                <v-form
                                    v-model="form"
                                    ref="form"
                                    @submit.prevent="login"
                                >
                                    <v-text-field
                                        v-model="username"
                                        label="Email"
                                        name="Email"
                                        prepend-icon="mdi-account"
                                        type="email"
                                        :rules="[
                                            $store.state.rules.required,
                                            $store.state.rules.email
                                        ]"
                                        required
                                    />

                                    <v-text-field
                                        v-model="password"
                                        id="password"
                                        label="Password"
                                        name="password"
                                        prepend-icon="mdi-lock"
                                        type="password"
                                        :rules="[$store.state.rules.required]"
                                        required
                                    />

                                    <div class="d-flex flex-row-reverse">
                                        <v-btn
                                            type="submit"
                                            color="primary"
                                            :disabled="!form"
                                            >Login</v-btn
                                        >
                                    </div>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>

        <!-- snackbar -->
        <Snackbar />
    </v-app>
</template>
