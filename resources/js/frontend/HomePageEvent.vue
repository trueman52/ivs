<template>
    <section class="whatshappening">
        <div class="container small-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="whatshappening__top">
                        <h2>What’s happening?</h2>
                        <p>The hottest events of this season.</p>
                    </div>
                </div>
            </div>

            <div class="row" v-if="topSection">
                <div class="col-md-12">
                    <div class="whatshappening__card">
                        <div class="whatshappening__card__image"><img :src="topSection.photo" alt=""></div>
                        <div class="whatshappening__card__content">
                            <h4>{{ topSection.name }}</h4>
                            <h3><a :href="topSection.spaceUrl">{{ topSection.description }}</a></h3>
                            <p>{{ topSection.spaceDescription }}</p>
                            <div class="whatshappening__card__content__button"><a :href="topSection.spaceUrl" class="btn-link btn-link__primary float-right"><span>View more</span></a></div>
                        </div><!--event_content-->
                    </div><!--event_single-->
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" v-if="leftSection">
                    <div class="whatshappening__card half_block">
                        <div class="whatshappening__card__image"><img :src="leftSection.photo" alt=""></div>
                        <div class="whatshappening__card__content">
                            <h4>{{ leftSection.name }}</h4>
                            <h3><a :href="leftSection.spaceUrl">{{ leftSection.description }}</a></h3>
                            <p>{{ leftSection.spaceDescription }}</p>
                            <div class="whatshappening__card__content__button"><a :href="leftSection.spaceUrl" class="btn-link btn-link__primary float-right"><span>View more</span></a></div>
                        </div><!--event_content-->
                    </div><!--event_single-->
                </div>

                <div class="col-md-6" v-if="rightSection">
                    <div class="whatshappening__card half_block">
                        <div class="whatshappening__card__image"><img :src="rightSection.photo" alt=""></div>
                        <div class="whatshappening__card__content">
                            <h4>{{ rightSection.name }}</h4>
                            <h3><a :href="rightSection.spaceUrl">{{ rightSection.description }}</a></h3>
                            <p>{{ rightSection.spaceDescription }}</p>
                            <div class="whatshappening__card__content__button"><a :href="rightSection.spaceUrl" class="btn-link btn-link__primary float-right"><span>View more</span></a></div>
                        </div><!--event_content-->
                    </div><!--event_single-->
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="load_btn text-center"><a :href="url" class="button button__primary">More events</a></div>
                </div>
            </div>
        </div>
    </section>

    
</template>

<script>
    export default {
        props : [
            'topSection',
            'leftSection',
            'rightSection',
            'url'
        ],

        name : "HomePageEvent"
    }
</script>