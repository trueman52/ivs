<template>
    <ul>
        <li class="prev" :class="{ disabled: pagination.current_page <= 1 }">
            <a @click.prevent="changePage(pagination.current_page - 1)" aria-label="Previous">&nbsp;</a>
        </li>

        <li v-for="page in pages"  :key="page" :class="isCurrentPage(page) ? 'active' : ''">
            <a @click.prevent="changePage(page)">{{ page }}
            </a>
        </li>

        <li class="next" :class="{ disabled: pagination.current_page >= pagination.last_page }">
            <a @click.prevent="changePage(pagination.last_page)" aria-label="Next">&nbsp;</a>
        </li>
    </ul>
</template>

<script>
    export default {
        props:['pagination', 'offset'],
        methods: {
            isCurrentPage(page){
                return this.pagination.current_page === page
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = []

                let from = this.pagination.current_page - Math.floor(this.offset / 2)

                if (from < 1) {
                    from = 1
                }

                let to = from + this.offset -1

                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page
                }

                while (from <= to) {
                    pages.push(from)
                    from++
                }

                return pages
            }
        }
    }
</script>
