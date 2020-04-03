<script>
import { mapState, mapActions } from 'vuex'
import DeleteDialog from '@/components/RecordsDeleteDialog'

export default {
    components: {
        DeleteDialog
    },

    beforeRouteUpdate(to, from, next) {
        this.fetchRecords({
            page: 1,
            itemsPerPage: this.tblOptions.itemsPerPage,
            sortBy: this.tblOptions.sortBy,
            sortDesc: this.tblOptions.sortDesc[0] ? 'desc' : 'asc',
            ...(to.query.lastname && {
                lastname: to.query.lastname
            })
        })

        next()
    },

    methods: {
        ...mapActions('records', [
            'fetchRecords',
            'deleteItem',
            'setViewRecordHeader'
        ]),

        viewItem(item, edit = false) {
            this.setViewRecordHeader({
                user: item.user,
                created_at: item.created_at
            })

            this.$router.push({
                name: 'admin.records.view',
                params: { id: item.id },
                ...(edit && { query: { edit: edit } })
            })
        }
    },

    watch: {
        tblOptions: {
            handler() {
                this.fetchRecords({
                    page: this.tblOptions.page,
                    itemsPerPage: this.tblOptions.itemsPerPage,
                    sortBy: this.tblOptions.sortBy,
                    sortDesc: this.tblOptions.sortDesc[0] ? 'desc' : 'asc',
                    ...(this.$route.query.lastname && {
                        lastname: this.$route.query.lastname
                    })
                })
            },
            deep: true
        }
    },

    computed: {
        ...mapState('records', [
            'tblHeaders',
            'tblLoading',
            'tblItems',
            'tblTotalItems',
            'records',
            'deleteDialog'
        ]),

        tblOptions: {
            get() {
                return this.$store.state.records.tblOptions
            },

            set(value) {
                this.$store.commit('records/SET_TABLE_OPTIONS', value)
            }
        }
    }
}
</script>

<template>
    <v-container fluid>
        <v-data-table
            :headers="tblHeaders"
            :items="tblItems"
            :options.sync="tblOptions"
            :server-items-length="tblTotalItems"
            :loading="tblLoading"
            class="elevation-1"
        >
            <template v-slot:item.actions="{ item }">
                <!-- view -->
                <v-btn icon color="blue" @click="viewItem(item)">
                    <v-icon>mdi-eye</v-icon>
                </v-btn>

                <!-- edit -->
                <v-btn icon color="success" @click="viewItem(item, true)">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>

                <!-- detele -->
                <v-btn
                    icon
                    color="red darker-3"
                    @click="deleteItem({ id: item.id })"
                >
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
            </template>
        </v-data-table>

        <!-- delete record -->
        <DeleteDialog />
    </v-container>
</template>

<style lang="scss" scoped></style>
