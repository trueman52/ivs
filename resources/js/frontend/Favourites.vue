<template>
    <section class="spaces">
        <div class="container">
            <div class="spaces__content">
                <div class="row">
                    <div class="col-md-4 col-lg-3" v-for="space in spaces">
                        <div class="spaces__content__card">
                            <div class="spaces__content__card__image">
                                <a :href="'/spaces/' + space.space.id" :style="'background-image: url('+space.space.media[0].fullUrl+');'"></a>
                                <div class="rating_star">
                                    <a href="javascript:void(0)" :favourite="space.id" :id="space.space.id">
                                        <img class="favourite active" src="/images/frontend/favourite-checked-fill-E9248C.svg" alt=""/>
                                        <img class="unfavourite" src="/images/frontend/favourite-unchecked-FFFFFF.svg" alt="">
                                    </a>
                                </div>
                            </div><!--sp_event_photo-->
                            <div class="spaces__content__card__content" style="height: 106px;">
                                <h5 v-if="space.space.address">{{ space.space.address.country }}</h5>
                                <h3><a :href="space.url">{{ space.space.name }}</a></h3>
                                <h4>{{ space.space.display_date }}</h4>
                                <p v-if="space.space.address">{{ space.space.address.street + ' ' + space.space.address.postalCode }}</p>
                            </div><!--sp_event_info-->
                            <div class="spaces__content__card__action">
                                <a href="javascript:void(0)" v-for="tag in space.space.tags" :class="tag.class">{{ tag.name }}</a>
                            </div><!--sp_event_bottom-->
                        </div><!--spaces_event_single-->
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>

export default {
    props : [
    ],

    data() {
        return {
          editMode   : false,
          query      : "",
          queryFiled : "name",
          spaces     : [],
        };
    },

    watch: {
      query: function(newQ, old) {
        this.getData();
      }
    },

    mounted() {
      this.getData();
    },

    methods: {
      getData() {
        axios
          .get("/web/my-favourites")
          .then(response => {
              this.spaces = response.data.favourites;
          })
          .catch(e => {
              console.log(e);
          });
      }
    }
};
</script>