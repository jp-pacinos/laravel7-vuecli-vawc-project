<script>
export default {
    computed: {
        snackbarShow: {
            get() {
                return this.$store.state.app.snackbar.show
            },

            set(value) {
                this.$store.commit('app/SET_SHOW_SNACK_BAR', value)
            }
        },

        snackbar: {
            get() {
                return this.$store.state.app.snackbar
            },

            set(value) {
                this.$store.commit('app/SET_SNACK_BAR', value)
            }
        }
    }
}
</script>

<template>
    <v-snackbar
        v-model="snackbarShow"
        :bottom="snackbar.y === 'bottom'"
        :color="snackbar.color"
        :left="snackbar.x === 'left'"
        :multi-line="snackbar.mode === 'multi-line'"
        :right="snackbar.x === 'right'"
        :timeout="snackbar.timeout"
        :top="snackbar.y === 'top'"
        :vertical="snackbar.mode === 'vertical'"
    >
        {{ snackbar.text }}
        <v-btn
            dark
            text
            @click="$store.commit('app/SET_SHOW_SNACK_BAR', false)"
        >
            Close
        </v-btn>
    </v-snackbar>
</template>

<style lang="scss" scoped></style>
