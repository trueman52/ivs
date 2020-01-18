<template>
    <section class="banner">
        <div class="banner__image">
            <img :src="bannerImage" alt="">
        </div><!--banner_photo-->
        <div class="banner__content">
            <h2>Creative <span>spaces</span></h2>
            <h2>Immersive <span>places</span></h2>
            <p>We bring ideas to spaces and spaces to ideas</p>
            <a :href="bannerUrl" class="button button__primary">Browse spaces</a>
        </div><!--banner_content-->
    </section>
</template>

<script>
    export default {
        props : [
            'bannerUrl',
            'bannerImage'
        ],

        name : "HomePageBanner"
    }
</script>