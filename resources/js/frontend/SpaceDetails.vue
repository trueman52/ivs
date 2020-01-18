<template>
    <section>
        <section class="space-details-banner">
            <div class="owl-carousel" id="space-details-banner__id">
                <div class="item" v-for="sliderImage in sliderImages">
                    <img :src="sliderImage.fullUrl" alt=""/>
                </div>
            </div>
        </section>

        <section class="spaces_details_header" id="inner_header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="spaces_details_header__left">
                            <ul>
                                <li class="active"><a href="#details_block">Details</a></li>
                                <li><a href="#available_space">Available Spaces</a></li>
                                <li><a href="#location_block">Location</a></li>
                            </ul>
                        </div>
                        <div class="spaces_details_header__right">
                            <div class="dropdown d-block d-md-none">
                                <a href="javascript:void(0)"></a>
                                <ul class="d-none d-md-block">
                                    <li><a href="#"><img src="/images/frontend/share-line.svg" alt=""/></a></li>
                                    <li class="rating_star">
                                        <a href="javascript:void(0)" v-if="favourite !== 'null'" :favourite="favourite.id" :id="'space'+space.id" :spaceAttr="space.id">
                                            <img class="unfavourite" src="/images/frontend/favourite-unchecked.svg" alt="">
                                            <img class="favourite active" src="/images/frontend/favourite-checked-fill.svg" alt="">
                                        </a>
                                        <a href="javascript:void(0)" v-else :id="'space'+space.id" :spaceAttr="space.id">
                                            <img class="unfavourite active" src="/images/frontend/favourite-unchecked.svg" alt="">
                                            <img class="favourite" src="/images/frontend/favourite-checked-fill.svg" alt="">
                                        </a>
                                    </li>
                                    <li class="book_btn"><a href="#available_space" class="button button__primary button__book">Book now</a></li>
                                </ul>
                            </div>
                            <ul class="d-none d-md-block">
                                <li><a href="#"><img src="/images/frontend/share-line.svg" alt=""/></a></li>
                                <li class="rating_star">
                                    <a href="javascript:void(0)" v-if="favourite !== 'null'" :favourite="favourite.id" :id="space.id" :spaceAttr="space.id">
                                        <img class="unfavourite" src="/images/frontend/favourite-unchecked.svg" alt="">
                                        <img class="favourite active" src="/images/frontend/favourite-checked-fill.svg" alt="">
                                    </a>
                                    <a href="javascript:void(0)" v-else :id="space.id" :spaceAttr="space.id">
                                        <img class="unfavourite active" src="/images/frontend/favourite-unchecked.svg" alt="">
                                        <img class="favourite" src="/images/frontend/favourite-checked-fill.svg" alt="">
                                    </a>
                                </li>
                                <li class="book_btn"><a href="#available_space" class="button button__primary button__book">Book now</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="details_section" id="details_block">
            <div class="details_section__top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="details_section__top__info">
                                <h3>Highlights</h3>
                                <ul>
                                    <li v-for="highlight in highlights">{{ highlight.text }}</li>
                                </ul>
                            </div><!--highlight_info-->
                        </div>
                        <div class="col-md-7">
                            <div class="details_section__top__eatbox_top">
                                <h3>{{ space.name }}</h3>
                                <h4>{{ space.remarks }}</h4>
                                <p style="white-space: pre-line;">{{ space.description }}</p>
                            </div>
                            <div class="details_section__top__eatbox_bottom">
                                <div class="artbox_logo float-right">
                                    <img src="/images/frontend/artbox_bg.png" alt="">
                                </div>
                                <div class="box_social float-left">
                                    <h3>Learn more</h3>
                                    <ul>
                                        <li v-if="space.spaceUrl"><a :href="space.spaceUrl"><img src="/images/frontend/monitor-circle.svg" alt=""></a></li>
                                        <li v-if="space.facebookUrl"><a :href="space.facebookUrl"><img src="/images/frontend/facebook-circle.svg" alt=""></a></li>
                                        <li v-if="space.instagramUrl"><a :href="space.instagramUrl"><img src="/images/frontend/instagram-circle.svg" alt=""></a></li>
                                    </ul>
                                </div>
                            </div><!--et_box_bottom-->
                        </div>
                    </div>
                </div>
            </div><!--details_top-->
            <div class="details_section__bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="details_section__bottom__block">
                                <h3>Notable Features</h3>
                                <ul>
                                    <li v-for="feature in features">
                                        <div class="media">
                                            <div class="media-left">
                                                <i :class="feature.icon"></i>
                                            </div>
                                            <div class="media-body">
                                                <h4>{{ feature.name }}</h4>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div><!--notable_block-->
                        </div>
                    </div>
                </div>
            </div><!--notable_feature_section-->
        </section>

        <section class="available_space" id="available_space">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="available_space__title">
                            <h2>Available Spaces</h2>
                        </div>
                    </div>
                </div>
                <div class="row gap_30_50" v-if="space.needs_curation">
                    <div class="col-md-4">
                        <div class="available_space__image">
                            <img src="/images/frontend/space_block_bg.png" alt=""/>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="available_space__content">
                            <h3>This event is specially curated</h3>
                            <p>Description and photos of your intended activity are required for review.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="available_space__card" v-for="unit in units">
                            <div class="available_space__card__image">
                                <a href="#" :style="'background-image: url('+unit.photo+');'"></a>
                            </div><!--eatbox_photo-->
                            <div class="available_space__card__content">
                                <div class="available_space__card__content__info">
                                    <h3><a href="#">{{ unit.unit.name }}</a></h3>
                                    <div class="available_space__card__content__info__price">
                                        <h5>From</h5>
                                        <h3>SGD {{ unit.price }}</h3>
                                        <div v-if='unit.published' class="book_now_btn"><a :href="'/spaces/' + space.id + '/bookings?unitId=' + unit.unit.id" class="button button__primary button__book">Book now</a></div>
                                        <h4 v-else>SOLD OUT</h4>
                                    </div>
                                    <h4>What’s included?</h4>
                                    {{ unit.unit.description }}
                                    <div class="sold-out d-block d-md-none" v-if='!unit.published'>SOLD OUT</div>
                                </div><!--eatbox_info_left-->
                            </div>
                        </div><!--eatbox_single-->
                    </div>
                </div>
            </div>
        </section><!--available_space_section-->

        <section class="location" id="location_block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="location__title">
                            <h2>Location</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="location__map">
                            <iframe width="100%" height="400" frameborder="0" style="border:0" :src="mapUrl" allowfullscreen></iframe>
                        </div>
                        <div class="location__info">
                            <p>{{ mapAddress }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--location_section-->

    </section>
</template>

<script>
    export default {
        props : [
            'space',
            'sliderImages',
            'highlights',
            'features',
            'units',
            'mapUrl',
            'mapAddress',
            'favourite',
        ],

        name : "SpaceDetails"
    }
</script>