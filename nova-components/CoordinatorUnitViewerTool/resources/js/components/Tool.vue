<template>
    <div>
        <heading class="mb-6 booking-mb">
            <router-link :to="{name: 'coordinator-viewer-tool'}">
                          My Assignments /
            </router-link>
            {{ space.name }}
        </heading>

        <div class="flex flex-wrap space-card">
            <div class="w-full md:w-1/2 xl:w-1/4 px-4 py-3" v-for="unit in space.units">
                <div class="bg-white">
                    <h2 class="font-bold flex-auto">{{ space.name }} - {{ unit.name }}</h2>
                    <h2 class="font-bold flex-auto">
                        <router-link :to="{
                                        name: 'coordinator-booking-viewer-tool',
                                        query: {unit: unit.id}
                                      }">
                                      {{ unit.periodDate }}
                        </router-link>
                    </h2>
                    <p class="font-light flex-auto">{{ unit.code }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { parse } from 'query-string'

    export default {
        data() {
            return {
                endpoint : '/nova-vendor/coordinator-unit-viewer-tool',
                space    : '',
            }
        },

        methods : {
            initDataFromQuery() {
                const parsed = parse(location.search);

                this.getSpace(parsed.space);
            },

            getSpace(id) {
                axios.get(`${this.endpoint}/spaces/${id}`)
                     .then((r) => {
                         this.space = r.data;
                     })
            },
        },

        created() {
            this.initDataFromQuery();
        },
    }
</script>

<style>
/* Scoped Styles */
</style>
