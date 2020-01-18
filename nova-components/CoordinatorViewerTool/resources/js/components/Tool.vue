<template>
    <div>
        <heading class="mb-6">My Assignments</heading>

        <div class="flex flex-wrap">
            <div class="relative h-9 mb-3 md:mb-6">
                <input class="appearance-none form-control form-input w-full md:w-search pl-search"
                       placeholder="Search"
                       type="search"
                       v-model="search"
                />
            </div>
        </div>

        <div class="flex flex-wrap space-card">
            <div class="w-full md:w-1/2 xl:w-1/4 px-4 py-3" v-for="space in spaces">
                <div class="bg-white">
                    <h2 class="font-bold flex-auto">
                        <router-link :to="{
                                        name: 'coordinator-unit-viewer-tool',
                                        query: {space: space.id}
                                      }">
                                      {{ space.name }} - [{{ space.code }}]
                        </router-link>
                    </h2>
                    <p class="font-light flex-auto">{{ space.displayDate }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { debounce } from 'lodash';

    export default {
        data() {
            return {
                endpoint : '/nova-vendor/coordinator-viewer-tool',
                spaces         : [],
                search         : '',
            }
        },

        mounted() {
            this.getSpaces();

            this.debouncedGetSpaces = debounce(this.getSpaces, 500)
        },

        watch : {
            search() {
                this.debouncedGetSpaces();
            }
        },

        methods : {
            getSpaces(to = `${this.endpoint}/spaces`) {
                axios.get(to, {
                    params : {
                        search : this.search
                    }
                })
                     .then((r) => {
                         this.spaces         = r.data;
                     });
            },
        }
    }
</script>

<style>
/* Scoped Styles */
</style>
