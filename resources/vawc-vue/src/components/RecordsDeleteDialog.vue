<script>
import { mapActions } from 'vuex'

export default {
    computed: {
        deleteDialog: {
            get() {
                return this.$store.state.records.deleteDialog
            },

            set(value) {
                this.$store.commit('records/DELETE_DIALOG', value)
            }
        }
    },

    methods: mapActions('records', ['deleteRecord'])
}
</script>

<template>
    <v-dialog v-model="deleteDialog" max-width="390">
        <v-card>
            <v-card-title class="headline">
                Delete this complain record?
            </v-card-title>

            <v-card-text>
                This will permanently delete the record in the database.
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="gray darken-1" text @click="deleteDialog = false">
                    Cancel
                </v-btn>

                <v-btn color="red darken-1" text @click="deleteRecord">
                    Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style lang="scss" scoped></style>
