<template>
    <section class="favorite" style="background-image: url(/images/frontend/pagebg.png)">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="favorite__empty">
                        <h2>Oh, look! How clean…</h2>
                        <p>Nothing is in your favourites yet! When you find something you like, click the heart icon to save it.</p>
                        <a href="/" class="button button__primary button__goingon">See What’s Going On</a>
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

        name : "EmptyFavourite"
    }
</script>