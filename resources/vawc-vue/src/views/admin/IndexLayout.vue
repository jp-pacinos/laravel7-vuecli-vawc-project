<script>
import Snackbar from '@/components/Snackbar'

export default {
    components: {
        Snackbar
    },

    data: () => ({
        dialog: false,
        drawer: null,
        items: [
            {
                icon: 'mdi-file-document-box-outline',
                text: 'Records',
                to: { name: 'admin.records' }
            },
            { divider: true },
            {
                icon: 'mdi-google-spreadsheet',
                text: 'Export Records',
                to: { name: 'admin.export' }
            }
        ]
    }),

    methods: {
        logout() {
            this.$store.dispatch('auth/logout')
        },

        search(lastname) {
            this.$router
                .push({
                    name: 'admin.records',
                    query: { lastname: lastname }
                })
                .catch(() => {})
        }
    },

    created() {
        this.$store.dispatch('auth/activateToken')
    }
}
</script>

<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            app
        >
            <v-list dense>
                <template v-for="(item, i) in items">
                    <v-divider v-if="item.divider" :key="i" dark class="my-2" />
                    <v-list-item v-else :key="i" :to="item.to" link>
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            app
            color="primary"
            dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
                <span class="hidden-sm-and-down">VAWC</span>
            </v-toolbar-title>
            <v-text-field
                @keyup.enter="search($event.target.value)"
                flat
                solo-inverted
                hide-details
                clearable
                prepend-inner-icon="mdi-magnify"
                label="Search Records: complainant lastname"
                class="hidden-sm-and-down"
            />
            <v-spacer />

            <router-link :to="{ name: 'admin.account' }">
                <v-btn icon>
                    <v-icon>mdi-account-circle</v-icon>
                </v-btn>
            </router-link>

            <v-btn icon @click="logout">
                <v-icon>mdi-logout-variant</v-icon>
            </v-btn>

            <v-progress-linear
                :active="$store.state.app.progressBar"
                :indeterminate="true"
                absolute
                bottom
                color="yellow accent-4"
            ></v-progress-linear>
        </v-app-bar>

        <v-content>
            <router-view></router-view>
        </v-content>

        <!-- snackbar -->
        <Snackbar />
    </v-app>
</template>
