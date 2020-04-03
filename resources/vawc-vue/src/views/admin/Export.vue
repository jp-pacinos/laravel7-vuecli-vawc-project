<script>
import { mapActions } from 'vuex'
import ExportService from '@/services/ExportService'

export default {
    data: () => ({
        items: [
            { value: 1, text: 'Excel Workbook (*.xlsx)' },
            { value: 2, text: 'CVS (*.csv)' },
            { value: 3, text: 'TSV (*.tsv)' },
            { value: 4, text: 'OpenDocument Spreadsheet (*.ods)' },
            { value: 5, text: 'Excel 97-2003 Workbook (*.xls)' },
            { value: 6, text: 'HTML (*.html)' }
        ],

        btnLoading: false,
        validationForm: true,

        type: null,
        dates: []
    }),

    methods: {
        ...mapActions('app', ['snackbar']),

        download(type) {
            if (!this.$refs.form.validate()) {
                return false
            }

            this.btnLoading = true

            this.snackbar({
                text: 'Please wait...',
                timeout: 4000,
                y: 'bottom'
            })

            ExportService.generate({
                type: type,
                dateRange: this.dates
            })
                .then(res => this.forceFileDownload(res, type))
                .catch(error => {
                    this.snackbar({
                        text: 'There was an error: ' + error.message,
                        y: 'bottom',
                        timeout: 0
                    })
                })
                .finally(() => (this.btnLoading = false))
        },

        forceFileDownload(response, type) {
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute(
                'download',
                'VAWC Records ' + this.dateRangeText + '.' + this.filetype(type)
            )
            document.body.appendChild(link)
            link.click()
        },

        filetype(type) {
            switch (type) {
                case 1:
                    return 'xlsx'
                case 2:
                    return 'csv'
                case 3:
                    return 'tsv'
                case 4:
                    return 'ods'
                case 5:
                    return 'xls'
                case 6:
                    return 'html'
            }
        }
    },

    computed: {
        dateRangeText() {
            return this.dates.join(' to ')
        }
    }
}
</script>

<template>
    <v-container fluid class="px-5">
        <h2 class="headline font-weight-medium py-3 mb-sm-5">
            Export Records
        </h2>

        <v-form
            v-model="validationForm"
            ref="form"
            @submit.prevent="download(type)"
        >
            <v-row align="center" justify="center">
                <v-col cols="12" md="4">
                    <v-select
                        v-model="type"
                        :items="items"
                        label="Available Format"
                        hint="Format of export. .cvs, .tsv only supports one sheet"
                        prepend-inner-icon="mdi-file-compare"
                        :rules="[$store.state.rules.required]"
                        class="mb-sm-4"
                    ></v-select>
                    <v-text-field
                        v-model="dateRangeText"
                        label="Date Filled (range)"
                        prepend-inner-icon="mdi-calendar-blank-outline"
                        hint="This will filter the records created in the selected date range. Use the calendar to select date range"
                        class="mb-sm-4"
                        :rules="[$store.state.rules.required]"
                        readonly
                    ></v-text-field>

                    <v-btn type="submit" color="primary" :loading="btnLoading">
                        Export Now
                    </v-btn>
                </v-col>
                <v-col cols="12" md="6">
                    <v-date-picker
                        color="primary"
                        v-model="dates"
                        range
                        full-width
                        landscape
                    ></v-date-picker>
                    <v-subheader>
                        Note: Select the oldest date first and follow with
                        latest date. Use navigation select more dates.
                    </v-subheader>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>

<style lang="scss" scoped></style>
