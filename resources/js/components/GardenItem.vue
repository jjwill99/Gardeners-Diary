<template>
    <!-- <a class="btn card" :href=garden_link :style="{ backgroundImage: 'url(' + picture + ')', backgroundRepeat: 'no-repeat', backgroundSize: '100%'}" v-if="picture != 'http://localhost/Laravel/Gardeners-Diary/public/storage/images'"> -->
    <a :href=garden_link class="card m-2" style="width: 20rem;" v-if="picture != 'http://localhost/Laravel/Gardeners-Diary/public/storage/images'">
        <!-- {{garden_name}} -->
        <img class="card-img" :src=picture>
        <div class="card-img-overlay d-flex">
            <h1 class="align-self-center mx-auto text-white">{{ garden_name }}</h1>
        </div>
    </a>

    <a class="btn card m-2" style="width: 20rem;" :href=garden_link v-else>
        <div class="card-body d-flex">
            <p class="align-self-center mx-auto text-black" style="font-size:2vw">{{ garden_name }}</p>
        </div>
    </a>
</template>

<script>
    export default {
        props:{
            garden_name: {
                default: "No Name",
                type: String
            },
            picture: {},
            garden_link: {}
        }
    }
</script>