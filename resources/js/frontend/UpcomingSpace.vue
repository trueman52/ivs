<template>
    <div class="tab-pane active show" id="upcoming-space">
        <div class="row">
            <div class="col-md-4 col-lg-3" v-for="upcoming in spaces">
                <div class="spaces__content__card">
                    <div class="spaces__content__card__image">
                        <a :href="'/spaces/' + upcoming.id" :style="'background-image: url('+upcoming.mediaFirstFullUrl+');'"></a>
                        <div class="rating_star">
                            <a href="javascript:void(0)" v-if="userFavourites(upcoming.id) >= 0" :favourite="userFavourites(upcoming.id)" :id="upcoming.id">
                                <img class="favourite active" src="/images/frontend/favourite-checked-fill-E9248C.svg" alt=""/>
                                <img class="unfavourite" src="/images/frontend/favourite-unchecked-FFFFFF.svg" alt="">
                            </a>
                            <a href="javascript:void(0)" v-else :id="upcoming.id">
                               <img class="favourite" src="/images/frontend/favourite-checked-fill-E9248C.svg" alt=""/>
                                <img class="unfavourite active" src="/images/frontend/favourite-unchecked-FFFFFF.svg" alt="">
                            </a>
                        </div>
                    </div><!--sp_event_photo-->
                    <div class="spaces__content__card__content">
                        <h5 v-if="upcoming.address">{{ upcoming.address.country }}</h5>
                        <h3><a :href="'/spaces/' + upcoming.id">{{ upcoming.name }}</a></h3>
                        <h4>{{ upcoming.displayDate }}</h4>
                        <p v-if="upcoming.address">{{ upcoming.address.street + ' ' + upcoming.address.postalCode }}</p>
                    </div><!--sp_event_info-->
                    <div class="spaces__content__card__action">
                        <a href="javascript:void(0)" v-for="tag in upcoming.tags" :class="tag.name.replace(' ', '_').toLowerCase()">{{ tag.name }}</a>
                    </div><!--sp_event_bottom-->
                </div><!--spaces_event_single-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="pagination text-center">
                    <pagination
                        v-if="pagination.last_page > 1"
                        :pagination="pagination"
                        :offset="5"
                        @paginate="getData()"
                        >
                    </pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props : [
        'country',
        'favourites',
    ],

    data() {
        return {
          editMode   : false,
          query      : "",
          queryFiled : "name",
          spaces     : [],
          pagination : {
            current_page : 1
          }
        };
    },

    mounted() {
      this.getData();
    },

    methods: {
        userFavourites(spaceId) {
            var data = [this.favourites];
            var index = data.findIndex(function(item, i){
                item = item.trim().split(':');
                if(item[0].replace('{', '').replace(/['"]+/g, '') == spaceId) {
                    return item[1].replace('}', '');
                }
            });
            return index;
        },
        getData() {
            axios
            .get("/web/spaces?upcoming=1&country=" + this.country + "&page=" + this.pagination.current_page)
            .then(response => {
                this.spaces     = response.data.spaces.data;
                this.pagination = response.data.spaces;
            })
            .catch(e => {
                console.log(e);
            });
        }
    }
};
</script>