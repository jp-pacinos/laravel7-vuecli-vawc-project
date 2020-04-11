<script>
import { mapState, mapActions } from 'vuex'
import Snackbar from '@/components/Snackbar'
import ComplainService from '@/services/ComplainFormService'

export default {
    components: {
        Snackbar
    },

    data() {
        return {
            // animation
            lazyDateTimeHappened: false,
            lazyStatement: false,
            lazyPersonsInvolved: false,

            // in statement date and time menu
            stDateHappenedMenu: false,
            stTimeHappenedMenu: false,

            // used in form validation
            form: true,

            statement: {
                title: '',
                dateHappened: '',
                timeHappened: '',
                text: ''
            },

            personsOption: [
                {
                    title: 'Personal na detalye',
                    subtitle: 'Personal Details',
                    optionalContact: false
                },

                {
                    title: 'Detalye ng Inirereklamo',
                    subtitle: 'Respondent Details',
                    optionalContact: true
                }
            ],

            persons: [
                {
                    lastname: '',
                    firstname: '',
                    middlename: '',
                    suffix: '',
                    birthDate: '',
                    birthDateMenu: false,
                    sex: 2,
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
            ],

            personsInvolved: [],

            agreement: false
        }
    },

    methods: {
        ...mapActions('app', ['snackbar']),

        addInvolvedPerson() {
            this.personsInvolved.push(this.getFreshInvolvedPersonInputs())
        },

        removeInvolvedPerson(i) {
            this.$delete(this.personsInvolved, i)
        },

        resetInvolvedPerson(i) {
            this.$set(
                this.personsInvolved,
                i,
                this.getFreshInvolvedPersonInputs()
            )
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
                    name: ''
                }
            }
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

            this.snackbar({
                text: 'Please wait...',
                timeout: 0,
                y: 'bottom'
            })

            const data = {
                statement: this.statement,
                complainant: this.persons[0],
                respondent: this.persons[1],
                otherInvolved: this.personsInvolved
            }

            ComplainService.submit(data)
                .then(() => {
                    this.snackbar({
                        text: 'Your complain has been submitted successfully!',
                        color: 'primary',
                        timeout: 15000,
                        mode: 'multi-line',
                        y: 'bottom'
                    })

                    this.$refs.form.reset()
                    this.personsInvolved = []
                    sessionStorage.removeItem('saved')
                })
                .catch(error => {
                    this.snackbar({
                        mode: 'multi-line',
                        text:
                            'There was an error on submitting your complain, please consider to refresh the page if this happened again. Error message: ' +
                                error.message ?? 'unknown',
                        y: 'bottom'
                    })
                })
        },

        saveInputs() {
            sessionStorage.setItem(
                'saved',
                JSON.stringify({
                    statement: this.statement,
                    persons: this.persons,
                    involved: this.personsInvolved
                })
            )
        },

        loadInputs() {
            const data = JSON.parse(sessionStorage.getItem('saved'))

            if (!data) return

            this.statement = data.statement
            this.persons = data.persons
            this.personsInvolved = data.involved
            this.$refs.form.resetValidation()
        }
    },

    created() {
        window.addEventListener('beforeunload', event => {
            // Cancel the event as stated by the standard.
            event.preventDefault()
            // the absence of a returnValue property on the event will guarantee the browser unload happens
            delete event['returnValue']

            this.saveInputs()
        })
    },

    computed: {
        ...mapState({
            rules: state => state.rules,
            vawcForm: state => state.vawcForm
        })
    },

    mounted() {
        this.loadInputs()
    }
}
</script>

<template>
    <v-app id="inspire">
        <v-content class="fill-height grey lighten-4">
            <v-form v-model="form" ref="form">
                <v-container>
                    <v-row justify="center">
                        <v-col cols="12" sm="9">
                            <v-card
                                outlined
                                class="py-4 px-3"
                                style="border-top: 8px solid #2196f3"
                            >
                                <v-card-title class="headline mb-3">
                                    <h1 class="font-weight-light blue--text">
                                        VAWC FORM
                                    </h1>
                                </v-card-title>
                                <v-card-subtitle class="subtitle-1">
                                    Violence against wowmen and children form
                                </v-card-subtitle>
                            </v-card>
                        </v-col>

                        <v-col
                            v-for="(person, i) in persons"
                            :key="i"
                            cols="12"
                            sm="9"
                        >
                            <v-card outlined class="py-4 px-3">
                                <v-card-title class="font-weight-regular">
                                    {{ personsOption[i].title }}
                                    <p class="ml-1 mb-0 red--text">*</p>
                                </v-card-title>
                                <v-card-subtitle class="pb-0">
                                    {{ personsOption[i].subtitle }}
                                </v-card-subtitle>
                                <v-row class="px-4">
                                    <v-col cols="12" md="7">
                                        <v-row no-gutters>
                                            <v-col cols="12" md="6">
                                                <v-text-field
                                                    v-model="
                                                        persons[i].firstname
                                                    "
                                                    label="First name"
                                                    :rules="[rules.required]"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                                <v-text-field
                                                    v-model="
                                                        persons[i].middlename
                                                    "
                                                    label="Middle name (optional)"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="7">
                                                <v-text-field
                                                    v-model="
                                                        persons[i].lastname
                                                    "
                                                    label="Last name"
                                                    :rules="[rules.required]"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="5">
                                                <v-combobox
                                                    v-model="persons[i].suffix"
                                                    :items="vawcForm.suffix"
                                                    label="Suffix (optional)"
                                                    prepend-inner-icon="mdi-account-arrow-left-outline"
                                                    auto-select-first
                                                    cache-items
                                                    clearable
                                                >
                                                </v-combobox>
                                            </v-col>
                                            <v-col cols="12" md="7">
                                                <v-menu
                                                    v-model="
                                                        persons[i].birthDateMenu
                                                    "
                                                    :close-on-content-click="
                                                        false
                                                    "
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            on
                                                        }"
                                                    >
                                                        <v-text-field
                                                            v-model="
                                                                persons[i]
                                                                    .birthDate
                                                            "
                                                            label="Date of birth (optional)"
                                                            prepend-inner-icon="mdi-calendar-blank-outline"
                                                            readonly
                                                            clearable
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="
                                                            persons[i].birthDate
                                                        "
                                                        @input="
                                                            persons[
                                                                i
                                                            ].birthDateMenu = false
                                                        "
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-col>
                                            <v-col cols="12" md="5">
                                                <v-select
                                                    v-model="persons[i].sex"
                                                    prepend-inner-icon="mdi-gender-male-female"
                                                    label="Sex"
                                                    :rules="[rules.required]"
                                                    :items="vawcForm.sex"
                                                    required
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="12" md="7">
                                                <v-text-field
                                                    v-model="
                                                        persons[i].contactNumber
                                                    "
                                                    :label="
                                                        'Contact number' +
                                                            (personsOption[i]
                                                                .optionalContact
                                                                ? ' (optional)'
                                                                : '')
                                                    "
                                                    prepend-inner-icon="mdi-phone-outline"
                                                    :rules="[
                                                        personsOption[i]
                                                            .optionalContact
                                                            ? true
                                                            : rules.required
                                                    ]"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>

                        <v-col cols="12" sm="9">
                            <v-lazy
                                v-model="lazyDateTimeHappened"
                                :options="{
                                    threshold: 0.5
                                }"
                                transition="fade-transition"
                                :min-height="lazyStatement ? 0 : 200"
                            >
                                <v-card outlined class="py-4 px-3">
                                    <v-card-title class="font-weight-regular">
                                        Kailan ang pangyayari
                                        <p class="ml-1 mb-0 red--text">*</p>
                                    </v-card-title>
                                    <v-card-subtitle class="pb-0">
                                        Petsa at oras ng pangyayari
                                    </v-card-subtitle>
                                    <v-row class="px-4">
                                        <v-col cols="12" sm="6" md="4">
                                            <v-menu
                                                v-model="stDateHappenedMenu"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                            >
                                                <template
                                                    v-slot:activator="{ on }"
                                                >
                                                    <v-text-field
                                                        v-model="
                                                            statement.dateHappened
                                                        "
                                                        label="Year, Month, Day"
                                                        prepend-icon="mdi-calendar-blank-outline"
                                                        :rules="[
                                                            rules.required
                                                        ]"
                                                        readonly
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="
                                                        statement.dateHappened
                                                    "
                                                    @input="
                                                        stDateHappenedMenu = false
                                                    "
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="4">
                                            <v-menu
                                                ref="stTimeHappened"
                                                v-model="stTimeHappenedMenu"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                :return-value.sync="
                                                    statement.timeHappened
                                                "
                                                transition="scale-transition"
                                                offset-y
                                                max-width="290px"
                                                min-width="290px"
                                            >
                                                <template
                                                    v-slot:activator="{ on }"
                                                >
                                                    <v-text-field
                                                        v-model="
                                                            statement.timeHappened
                                                        "
                                                        label="Time"
                                                        prepend-icon="mdi-clock-outline"
                                                        :rules="[
                                                            rules.required
                                                        ]"
                                                        readonly
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-time-picker
                                                    v-if="stTimeHappenedMenu"
                                                    v-model="
                                                        statement.timeHappened
                                                    "
                                                    full-width
                                                    @click:minute="
                                                        $refs.stTimeHappened.save(
                                                            statement.timeHappened
                                                        )
                                                    "
                                                ></v-time-picker>
                                            </v-menu>
                                        </v-col>
                                    </v-row>
                                </v-card>
                            </v-lazy>
                        </v-col>

                        <v-col cols="12" sm="9">
                            <v-lazy
                                v-model="lazyStatement"
                                :options="{
                                    threshold: 0.5
                                }"
                                transition="fade-transition"
                                :min-height="lazyPersonsInvolved ? 0 : 200"
                            >
                                <v-card outlined class="py-4 px-3">
                                    <v-card-title class="font-weight-regular">
                                        Iyong Salaysay
                                        <p class="ml-1 mb-0 red--text">*</p>
                                    </v-card-title>
                                    <v-card-subtitle>
                                        Your Statement
                                    </v-card-subtitle>
                                    <div class="px-4">
                                        <v-text-field
                                            v-model="statement.title"
                                            label="Short Description"
                                            prepend-icon="mdi-message-outline"
                                            :rules="[
                                                rules.required,
                                                vawcForm.rules.titleCounter
                                            ]"
                                            clearable
                                            counter
                                            maxlength="80"
                                        ></v-text-field>

                                        <v-textarea
                                            v-model="statement.text"
                                            auto-grow
                                            name="statement"
                                            label="Statement"
                                            placeholder="Your statement"
                                            rows="12"
                                            counter
                                            :rules="[rules.required]"
                                            required
                                        >
                                        </v-textarea>
                                    </div>
                                </v-card>
                            </v-lazy>
                        </v-col>

                        <v-col cols="12" sm="9">
                            <v-lazy
                                v-model="lazyPersonsInvolved"
                                :options="{
                                    threshold: 0.5
                                }"
                                transition="fade-transition"
                            >
                                <v-card outlined class="py-4 px-3">
                                    <v-card-title class="font-weight-regular">
                                        Mga tao na kasama
                                    </v-card-title>
                                    <v-card-subtitle class="pb-0">
                                        Other persons involved
                                    </v-card-subtitle>

                                    <v-row class="px-4">
                                        <v-col cols="12" sm="8">
                                            <v-scroll-y-transition group>
                                                <div
                                                    v-for="(person,
                                                    i) in personsInvolved"
                                                    :key="i"
                                                    class="d-inline-block mx-auto"
                                                >
                                                    <v-row>
                                                        <v-col cols="10">
                                                            <v-row no-gutters>
                                                                <v-col
                                                                    cols="12"
                                                                    md="6"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .firstname
                                                                        "
                                                                        label="First name"
                                                                        :rules="[
                                                                            rules.required
                                                                        ]"
                                                                        required
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    md="6"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .middlename
                                                                        "
                                                                        label="Middle name (optional)"
                                                                        required
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    md="7"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .lastname
                                                                        "
                                                                        label="Last name"
                                                                        :rules="[
                                                                            rules.required
                                                                        ]"
                                                                        required
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    md="5"
                                                                >
                                                                    <v-combobox
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .suffix
                                                                        "
                                                                        :items="
                                                                            vawcForm.suffix
                                                                        "
                                                                        label="Suffix (optional)"
                                                                        prepend-inner-icon="mdi-account-arrow-left-outline"
                                                                        auto-select-first
                                                                        cache-items
                                                                        clearable
                                                                    >
                                                                    </v-combobox>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    md="7"
                                                                >
                                                                    <v-menu
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .birthDateMenu
                                                                        "
                                                                        :close-on-content-click="
                                                                            false
                                                                        "
                                                                        :nudge-right="
                                                                            40
                                                                        "
                                                                        transition="scale-transition"
                                                                        offset-y
                                                                        min-width="290px"
                                                                    >
                                                                        <template
                                                                            v-slot:activator="{
                                                                                on
                                                                            }"
                                                                        >
                                                                            <v-text-field
                                                                                v-model="
                                                                                    personsInvolved[
                                                                                        i
                                                                                    ]
                                                                                        .birthDate
                                                                                "
                                                                                label="Date of birth (optional)"
                                                                                prepend-inner-icon="mdi-calendar-blank-outline"
                                                                                readonly
                                                                                clearable
                                                                                v-on="
                                                                                    on
                                                                                "
                                                                            ></v-text-field>
                                                                        </template>
                                                                        <v-date-picker
                                                                            v-model="
                                                                                personsInvolved[
                                                                                    i
                                                                                ]
                                                                                    .birthDate
                                                                            "
                                                                            @input="
                                                                                personsInvolved[
                                                                                    i
                                                                                ].birthDateMenu = false
                                                                            "
                                                                        ></v-date-picker>
                                                                    </v-menu>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    md="5"
                                                                >
                                                                    <v-select
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .sex
                                                                        "
                                                                        prepend-inner-icon="mdi-gender-male-female"
                                                                        label="Sex"
                                                                        :rules="[
                                                                            rules.required
                                                                        ]"
                                                                        :items="
                                                                            vawcForm.sex
                                                                        "
                                                                        required
                                                                    ></v-select>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    md="7"
                                                                >
                                                                    <v-text-field
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .contactNumber
                                                                        "
                                                                        label="Contact number (optional)"
                                                                        prepend-inner-icon="mdi-phone-outline"
                                                                        required
                                                                    ></v-text-field>
                                                                </v-col>
                                                                <v-col
                                                                    cols="12"
                                                                    md="5"
                                                                >
                                                                    <v-combobox
                                                                        v-model="
                                                                            personsInvolved[
                                                                                i
                                                                            ]
                                                                                .type
                                                                                .name
                                                                        "
                                                                        :items="
                                                                            vawcForm.relation
                                                                        "
                                                                        :rules="[
                                                                            rules.required
                                                                        ]"
                                                                        label="Relation"
                                                                        prepend-inner-icon="mdi-account-settings"
                                                                        auto-select-first
                                                                        cache-items
                                                                        clearable
                                                                        open-on-clear
                                                                        hint="The relation of the person: brother, sister or witness etc."
                                                                        required
                                                                    >
                                                                    </v-combobox>
                                                                </v-col>
                                                            </v-row>
                                                        </v-col>

                                                        <v-col cols="2">
                                                            <v-tooltip bottom>
                                                                <template
                                                                    v-slot:activator="{
                                                                        on
                                                                    }"
                                                                >
                                                                    <v-btn
                                                                        icon
                                                                        color="blue"
                                                                        v-on="
                                                                            on
                                                                        "
                                                                        class="d-block mb-3"
                                                                        @click="
                                                                            resetInvolvedPerson(
                                                                                i
                                                                            )
                                                                        "
                                                                    >
                                                                        <v-icon
                                                                            >mdi-autorenew</v-icon
                                                                        >
                                                                    </v-btn>
                                                                </template>
                                                                <span
                                                                    >Reset
                                                                    field</span
                                                                >
                                                            </v-tooltip>

                                                            <v-tooltip bottom>
                                                                <template
                                                                    v-slot:activator="{
                                                                        on
                                                                    }"
                                                                >
                                                                    <v-btn
                                                                        icon
                                                                        color="red"
                                                                        v-on="
                                                                            on
                                                                        "
                                                                        class="d-block"
                                                                        @click="
                                                                            removeInvolvedPerson(
                                                                                i
                                                                            )
                                                                        "
                                                                    >
                                                                        <v-icon
                                                                            >mdi-account-remove-outline</v-icon
                                                                        >
                                                                    </v-btn>
                                                                </template>
                                                                <span
                                                                    >Remove</span
                                                                >
                                                            </v-tooltip>
                                                        </v-col>
                                                    </v-row>
                                                </div>
                                            </v-scroll-y-transition>
                                        </v-col>
                                    </v-row>

                                    <div class="px-4">
                                        <v-btn
                                            @click="addInvolvedPerson"
                                            class="mt-3 mb-3"
                                            color="blue darken-2"
                                            depressed
                                            dark
                                            small
                                        >
                                            <v-icon dark left>mdi-plus</v-icon>
                                            Add Person
                                        </v-btn>
                                    </div>
                                </v-card>
                            </v-lazy>
                        </v-col>

                        <v-col cols="12" sm="9">
                            <v-lazy
                                v-model="lazyPersonsInvolved"
                                :options="{
                                    threshold: 0.5
                                }"
                                min-height="200"
                                transition="fade-transition"
                            >
                                <v-card outlined class="py-4 px-3">
                                    <v-card-subtitle class="pb-0">
                                        Legal notice
                                    </v-card-subtitle>
                                    <v-checkbox
                                        v-model="agreement"
                                        :rules="[rules.required]"
                                        color="primary"
                                        class="mx-4"
                                    >
                                        <template v-slot:label>
                                            I agree to the&nbsp;
                                            <a
                                                href="#"
                                                class="mx-1"
                                                @click.stop.prevent="
                                                    dialog = true
                                                "
                                            >
                                                Terms of Service
                                            </a>
                                            &nbsp;and&nbsp;
                                            <a
                                                href="#"
                                                class="mx-1"
                                                @click.stop.prevent="
                                                    dialog = true
                                                "
                                            >
                                                Privacy Policy </a
                                            >*
                                        </template>
                                    </v-checkbox>
                                    <v-card-actions class="pl-4">
                                        <v-btn @click="submit" color="primary"
                                            >Submit</v-btn
                                        >
                                    </v-card-actions>
                                </v-card>
                            </v-lazy>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
        </v-content>

        <Snackbar />
    </v-app>
</template>

<style lang="scss" scoped></style>
