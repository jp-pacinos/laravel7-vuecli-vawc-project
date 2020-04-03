<script>
import AccountService from '@/services/AccountService'

export default {
    data() {
        return {
            id: '',
            username: 'admin@admin.com',
            password_previous: '',
            password_confirmation: '',
            password: '',
            form: true,
            loading: false
        }
    },

    methods: {
        update() {
            if (!this.$refs.form.validate()) {
                this.$store.dispatch('app/snackbar', {
                    text: 'Please check your inputs.',
                    color: 'red',
                    y: 'bottom'
                })

                return false
            }

            this.$store.dispatch('app/snackbar', {
                text: 'Please wait...',
                timeout: 0,
                y: 'bottom'
            })

            const user = {
                email: this.username,
                password_previous: this.password_previous,
                password_confirmation: this.password_confirmation,
                password: this.password
            }

            AccountService.update(this.id, user)
                .then(() => {
                    this.$store.dispatch('app/snackbar', {
                        text: 'Your account information has been updated.',
                        color: 'primary',
                        y: 'bottom'
                    })

                    this.password = ''
                    this.password_previous = ''
                    this.password_confirmation = ''
                    this.$refs.form.resetValidation()
                })
                .catch(error => {
                    let message = ''

                    if (error.response.data.message) {
                        message = error.response.data.message
                    } else if (error.message) {
                        message = 'There was an error: ' + error.message
                    } else {
                        message = 'Something went wrong'
                    }

                    this.$store.dispatch('app/snackbar', {
                        text: message,
                        color: 'red',
                        y: 'bottom'
                    })
                })
        }
    },

    created() {
        this.$store.dispatch('app/progressBar', true)

        AccountService.getAccount()
            .then(({ data }) => {
                this.id = data.id
                this.username = data.email
            })
            .catch(error => {
                this.$store.dispatch('app/snackbar', {
                    text: 'There was an error: ' + error.message,
                    color: 'red',
                    y: 'bottom'
                })
            })
            .finally(() => {
                this.$store.dispatch('app/progressBar', false)
            })
    }
}
</script>

<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
            <v-col cols="12">
                <h2 color="blue" class="font-weight-regular text-center">
                    Account Setting
                </h2>
            </v-col>
            <v-col cols="12" sm="8" md="5">
                <v-card class="elevation-0">
                    <v-card-text>
                        <v-form
                            v-model="form"
                            ref="form"
                            @submit.prevent="update"
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
                                v-model="password_previous"
                                id="prevpassword"
                                label="Previous Password"
                                name="prevpassword"
                                prepend-icon="mdi-lock"
                                type="password"
                                :rules="[$store.state.rules.required]"
                                required
                            />

                            <v-divider class="my-5"></v-divider>

                            <v-text-field
                                v-model="password"
                                id="password"
                                label="New Password"
                                name="password"
                                prepend-icon="mdi-lock"
                                type="password"
                                :rules="[
                                    $store.state.rules.required,
                                    value =>
                                        value
                                            ? value.length >= 8 ||
                                              'Minimum of 8 characters'
                                            : true
                                ]"
                                required
                            />

                            <v-text-field
                                v-model="password_confirmation"
                                id="repassword"
                                label="Re-enter Password"
                                name="repassword"
                                prepend-icon="mdi-lock"
                                type="password"
                                :rules="[
                                    $store.state.rules.required,
                                    value =>
                                        this.password == value ||
                                        'Passwords does not match.'
                                ]"
                                required
                            />

                            <div class="d-flex flex-row-reverse">
                                <v-btn type="submit" color="primary"
                                    >Update</v-btn
                                >
                            </div>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style lang="scss" scoped></style>
