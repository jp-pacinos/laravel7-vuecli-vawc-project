<script>
import { mapState, mapActions } from 'vuex'
import { shortFullName } from '@/utils/fullname'
import {
    toDateFormat,
    toLongDateTimeFormat,
    toTwentyFourHourFormat
} from '@/utils/datetime'
import RecordService from '@/services/RecordService'
import DeleteDialog from '@/components/RecordsDeleteDialog'

export default {
    components: {
        DeleteDialog
    },

    props: {
        id: { type: [Number, String], required: true }
    },

    data() {
        return {
            // used in form validation
            form: true,

            // make inputs all editable
            editable: false,

            // statement date and time menu
            stDateHappenedMenu: false,
            stTimeHappenedMenu: false,

            statement: null,

            personsOption: [
                { name: 'Complainant', optionalContact: false },
                { name: 'Respondent', optionalContact: true }
            ],

            persons: [],

            personsInvolved: []
        }
    },

    methods: {
        ...mapActions('app', ['snackbar', 'progressBar']),
        ...mapActions('records', [
            'updateRecord',
            'deleteItem',
            'setViewRecordHeader'
        ]),

        addInvolved() {
            this.personsInvolved.push(this.getFreshInvolvedPersonInputs())
        },

        removeInvolved(i) {
            this.$delete(this.personsInvolved, i)
        },

        resetInvolved(i) {
            this.$set(
                this.personsInvolved,
                i,
                this.getFreshInvolvedPersonInputs()
            )
        },

        submit() {
            if (!this.$refs.form.validate()) {
                this.snackbar({
                    text: 'Please check your inputs.',
                    color: 'red',
                    y: 'bottom'
                })
                return false
            }

            this.progressBar(true)

            this.snackbar({
                text: 'Please wait...',
                timeout: 0,
                y: 'bottom'
            })

            const record = {
                statement: this.statement,
                complainant: this.persons[0],
                respondent: this.persons[1],
                otherInvolved: this.personsInvolved
            }

            RecordService.updateRecord(this.id, record)
                .then(() => {
                    this.editable = false

                    this.newHeader()

                    this.snackbar({
                        y: 'bottom',
                        color: 'primary',
                        text: 'Record updated!'
                    })
                })
                .catch((error) => {
                    this.snackbar({
                        y: 'bottom',
                        text:
                            'There was an error on saving this record. Error: ' +
                                error.message ?? 'unknown error'
                    })
                })
                .finally(() => this.progressBar(false))
        },

        getRecord() {
            this.reset()
            this.progressBar(true)

            if (this.$route.query.edit) {
                this.editable = true
                this.snackbar({
                    text: 'Editing enabled',
                    y: 'bottom'
                })
            }

            RecordService.getRecord(this.id)
                .then(({ data }) => {
                    this.set(data)
                })
                .catch((error) => {
                    this.snackbar({
                        text:
                            'There was an error: ' + error.message ??
                            'unknown error',
                        y: 'bottom'
                    })
                })
                .finally(() => this.progressBar(false))
        },

        set(data) {
            // format involved
            const involved = data.involved.map((person) => {
                return {
                    lastname: person.lastname,
                    firstname: person.firstname,
                    middlename: person.middlename,
                    suffix: person.suffix,
                    birthDate: person.birthdate,
                    birthDateMenu: false,
                    sex: person.sex,
                    contactNumber: person.contact_number,
                    type: {
                        id: person.relation.position_status,
                        name: person.relation.position
                    }
                }
            })

            this.statement = {
                title: data.title,
                dateHappened: toDateFormat(data.datetime_happened),
                timeHappened: toTwentyFourHourFormat(data.datetime_happened),
                text: data.body
            }

            for (let i = 0; i < involved.length; i++) {
                // complainant
                if (involved[i].type.id == 1) {
                    this.persons[0] = involved[i]

                // respondent
                } else if (involved[i].type.id == 2) {
                    this.persons[1] = involved[i]

                // involved
                } else if (involved[i].type.id == 3) {
                    this.personsInvolved.push(involved[i])
                }
            }

            this.newHeader(true, { created_at: data.created_at })
        },

        newHeader(rawDate = false, obj = {}) {
            this.setViewRecordHeader({
                user: shortFullName({
                    lastname: this.persons[0].lastname,
                    firstname: this.persons[0].firstname,
                    middlename: this.persons[0].middlename,
                    suffix: this.persons[0].suffix
                }),
                created_at: rawDate
                    ? toLongDateTimeFormat(obj.created_at)
                    : this.viewRecordHeader.created_at
            })
        },

        reset() {
            this.statement = {
                title: '',
                dateHappened: '',
                timeHappened: '',
                text: ''
            }

            this.persons = [
                {
                    lastname: '',
                    firstname: '',
                    middlename: '',
                    suffix: '',
                    birthDate: '',
                    birthDateMenu: false,
                    sex: '',
                    contactNumber: ''
                },
                {
                    lastname: '',
                    firstname: '',
                    middlename: '',
                    suffix: '',
                    birthDate: '',
                    birthDateMenu: false,
                    sex: '',
                    contactNumber: ''
                }
            ]

            this.personsInvolved = []
        },

        getFreshInvolvedPersonInputs() {
            return {
                lastname: '',
                firstname: '',
                middlename: '',
                suffix: '',
                birthDate: '',
                birthDateMenu: false,
                sex: '',
                contactNumber: '',
                type: {
                    id: 3,
                    name: ''
                }
            }
        }
    },

    created() {
        this.getRecord()
    },

    watch: {
        $route: 'getRecord'
    },

    computed: {
        ...mapState({
            rules: (state) => state.rules,
            vawcForm: (state) => state.vawcForm
        }),

        ...mapState('records', ['viewRecordHeader'])
    }
}
</script>

<template>
    <v-container fluid class="px-sm-5">
        <v-row align="start" justify="space-between">
            <v-col cols="12" md="6">
                <h2 class="headline font-weight-medium">
                    Showing Records for
                    {{ viewRecordHeader.user ? viewRecordHeader.user : null }}
                </h2>
                <v-subheader class="pl-0">
                    Filled date:
                    {{
                        viewRecordHeader.created_at
                            ? viewRecordHeader.created_at
                            : 'Loading...'
                    }}
                </v-subheader>
            </v-col>

            <v-col cols="12" md="auto">
                <v-btn
                    color="grey"
                    depressed
                    dark
                    small
                    @click="$router.go(-1)"
                >
                    <v-icon dark left>mdi-arrow-left</v-icon>Back
                </v-btn>

                <v-btn
                    v-if="!editable"
                    @click="
                        ;[
                            snackbar({
                                text: 'Editing enabled',
                                y: 'bottom'
                            }),
                            (editable = true)
                        ]
                    "
                    class="mx-2"
                    color="blue darken-2"
                    depressed
                    dark
                    small
                >
                    <v-icon dark left>mdi-pencil</v-icon> Edit mode
                </v-btn>
                <v-btn
                    v-else
                    @click="submit"
                    class="mx-2"
                    color="success"
                    depressed
                    dark
                    small
                >
                    <v-icon dark left>mdi-check</v-icon> Save
                </v-btn>

                <v-btn
                    @click="deleteItem({ id: id, redirect: true })"
                    color="red"
                    depressed
                    dark
                    small
                >
                    <v-icon dark left>mdi-delete</v-icon> Delete
                </v-btn>
            </v-col>
        </v-row>

        <v-form ref="form" v-model="form">
            <v-container>
                <!-- statement row -->
                <v-row>
                    <v-col cols="12">
                        <h3 class="title">Statement</h3>
                        <v-divider class="my-2"></v-divider>

                        <v-row>
                            <v-col cols="12" sm="6" md="3">
                                <v-menu
                                    v-model="stDateHappenedMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="statement.dateHappened"
                                            label="Date Happened"
                                            prepend-icon="mdi-calendar-blank-outline"
                                            :rules="[rules.required]"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="statement.dateHappened"
                                        @input="stDateHappenedMenu = false"
                                        :readonly="!editable"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="12" sm="6" md="3">
                                <v-menu
                                    ref="stTimeHappened"
                                    v-model="stTimeHappenedMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="statement.timeHappened"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="statement.timeHappened"
                                            label="Time"
                                            prepend-icon="mdi-clock-outline"
                                            :rules="[rules.required]"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="stTimeHappenedMenu"
                                        v-model="statement.timeHappened"
                                        full-width
                                        :readonly="!editable"
                                        @click:minute="
                                            $refs.stTimeHappened.save(
                                                statement.timeHappened
                                            )
                                        "
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                        </v-row>

                        <v-text-field
                            v-model="statement.title"
                            label="Title"
                            prepend-icon="mdi-message-outline"
                            :rules="[
                                rules.required,
                                vawcForm.rules.titleCounter
                            ]"
                            :readonly="!editable"
                            :filled="!editable"
                            :clearable="editable"
                            counter
                            maxlength="80"
                        ></v-text-field>

                        <v-textarea
                            v-model="statement.text"
                            solo
                            auto-grow
                            name="statement"
                            label="Statement"
                            rows="10"
                            counter
                            prepend-icon="mdi-message-outline"
                            :rules="[rules.required]"
                            :readonly="!editable"
                            :filled="!editable"
                            required
                        >
                        </v-textarea>
                    </v-col>
                </v-row>
                <!-- end statement row -->

                <!-- complainant respondent row -->
                <v-row justify="space-around">
                    <v-col
                        v-for="(person, i) in persons"
                        :key="i"
                        cols="12"
                        sm="5"
                    >
                        <h3 class="title mb-5">
                            {{ personsOption[i].name }} Details
                        </h3>

                        <v-row no-gutters>
                            <v-col cols="12" md="5">
                                <v-text-field
                                    v-model="persons[i].lastname"
                                    label="Last name"
                                    prepend-inner-icon="mdi-account-outline"
                                    :rules="[rules.required]"
                                    :readonly="!editable"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="persons[i].firstname"
                                    label="First name"
                                    :rules="[rules.required]"
                                    :readonly="!editable"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field
                                    v-model="persons[i].middlename"
                                    label="Middlename (optional)"
                                    :readonly="!editable"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-combobox
                                    v-model="persons[i].suffix"
                                    :items="vawcForm.suffix"
                                    label="Suffix (optional)"
                                    prepend-inner-icon="mdi-account-arrow-left-outline"
                                    auto-select-first
                                    cache-items
                                    :clearable="editable"
                                    :readonly="!editable"
                                    open-on-clear
                                >
                                </v-combobox>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-select
                                    v-model="persons[i].sex"
                                    prepend-inner-icon="mdi-gender-male-female"
                                    label="Sex"
                                    :rules="[rules.required]"
                                    :items="vawcForm.sex"
                                    :readonly="!editable"
                                    required
                                ></v-select>
                            </v-col>

                            <v-spacer></v-spacer>

                            <v-col cols="12" md="6">
                                <v-menu
                                    v-model="persons[i].birthDateMenu"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="persons[i].birthDate"
                                            label="Date of birth (optional)"
                                            prepend-inner-icon="mdi-calendar-blank-outline"
                                            readonly
                                            :clearable="editable"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="persons[i].birthDate"
                                        @input="
                                            persons[i].birthDateMenu = false
                                        "
                                        :readonly="!editable"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="persons[i].contactNumber"
                                    :label="
                                        'Contact number' +
                                        (personsOption[i].optionalContact
                                            ? ' (optional)'
                                            : '')
                                    "
                                    prepend-inner-icon="mdi-phone-outline"
                                    :rules="[
                                        personsOption[i].optionalContact
                                            ? true
                                            : rules.required
                                    ]"
                                    :readonly="!editable"
                                    required
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <!-- end complainant respondent row -->

                <v-divider class="my-5"></v-divider>

                <!-- other involved -->
                <v-row justify="space-around">
                    <v-col cols="11">
                        <h3 class="mt-5 text-center title">
                            Other Persons Involved
                        </h3>
                    </v-col>
                </v-row>

                <v-row align="center" justify="space-around">
                    <template v-if="personsInvolved.length > 0">
                        <v-col
                            v-for="(person, i) in personsInvolved"
                            :key="i"
                            cols="12"
                            sm="5"
                        >
                            <div v-if="editable" class="d-flex justify-end">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn
                                            icon
                                            color="blue"
                                            v-on="on"
                                            @click="resetInvolved(i)"
                                        >
                                            <v-icon>mdi-autorenew</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Reset field</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn
                                            icon
                                            color="red"
                                            v-on="on"
                                            @click="removeInvolved(i)"
                                        >
                                            <v-icon
                                                >mdi-account-remove-outline</v-icon
                                            >
                                        </v-btn>
                                    </template>
                                    <span>Remove</span>
                                </v-tooltip>
                            </div>

                            <v-row no-gutters>
                                <v-col cols="12" md="5">
                                    <v-text-field
                                        v-model="personsInvolved[i].lastname"
                                        label="Last name"
                                        prepend-inner-icon="mdi-account-outline"
                                        :rules="[rules.required]"
                                        :readonly="!editable"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="personsInvolved[i].firstname"
                                        label="First name"
                                        :rules="[rules.required]"
                                        :readonly="!editable"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="3">
                                    <v-text-field
                                        v-model="personsInvolved[i].middlename"
                                        label="Middlename (optional)"
                                        :readonly="!editable"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-combobox
                                        v-model="personsInvolved[i].suffix"
                                        :items="vawcForm.suffix"
                                        label="Suffix (optional)"
                                        prepend-inner-icon="mdi-account-arrow-left-outline"
                                        auto-select-first
                                        cache-items
                                        :clearable="editable"
                                        :readonly="!editable"
                                        open-on-clear
                                    >
                                    </v-combobox>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="personsInvolved[i].sex"
                                        prepend-inner-icon="mdi-gender-male-female"
                                        label="Sex"
                                        :rules="[rules.required]"
                                        :items="vawcForm.sex"
                                        :readonly="!editable"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-combobox
                                        v-model="personsInvolved[i].type.name"
                                        :items="vawcForm.relation"
                                        :rules="[rules.required]"
                                        label="Relation"
                                        prepend-inner-icon="mdi-account-settings"
                                        auto-select-first
                                        cache-items
                                        :clearable="editable"
                                        :readonly="!editable"
                                        open-on-clear
                                        hint="The relation of the person: brother, sister or witness etc."
                                        required
                                    >
                                    </v-combobox>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-menu
                                        v-model="
                                            personsInvolved[i].birthDateMenu
                                        "
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                v-model="
                                                    personsInvolved[i].birthDate
                                                "
                                                label="Date of birth (optional)"
                                                prepend-inner-icon="mdi-calendar-blank-outline"
                                                :clearable="editable"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="
                                                personsInvolved[i].birthDate
                                            "
                                            @input="
                                                personsInvolved[
                                                    i
                                                ].birthDateMenu = false
                                            "
                                            :readonly="!editable"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="
                                            personsInvolved[i].contactNumber
                                        "
                                        label="Contact number (optional)"
                                        prepend-inner-icon="mdi-phone-outline"
                                        :readonly="!editable"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-col>
                    </template>

                    <template v-else>
                        <v-col cols="12">
                            <p class="text-center">No others involved.</p>
                        </v-col>
                    </template>
                </v-row>

                <div v-if="editable" class="my-5 d-flex justify-center">
                    <v-btn
                        @click="addInvolved"
                        class="mx-2"
                        color="blue darken-2"
                        dark
                        small
                    >
                        <v-icon dark left>mdi-plus</v-icon> Add Person
                    </v-btn>
                </div>
                <!-- end other involved -->
            </v-container>
        </v-form>

        <!-- delete record -->
        <DeleteDialog />
    </v-container>
</template>

<style lang="scss" scoped></style>
