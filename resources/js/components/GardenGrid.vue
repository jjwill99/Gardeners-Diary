<template>
    <div class="border border-dark" :style="{ width: (80/garden_width) + 'vw', height: (80/garden_width) + 'vw', backgroundColor: colour}" v-on:dragover="mouseclick" v-on:click="mouseclick" v-if="garden_width >= garden_length">
        <input type="hidden" :name="grid_row + ',' + grid_column" :value="colour" />
        <img class="card-img img-fluid icon" :src=icon1 alt="Plant Icon" v-if="icon1">
        <img class="card-img img-fluid icon" :src=icon2 style="left:50%; bottom:50%;" alt="Plant Icon" v-if="icon2">
        <img class="card-img img-fluid icon" :src=icon3 style="bottom: 50%;" alt="Plant Icon" v-if="icon3">
        <img class="card-img img-fluid icon" :src=icon4 style="left: 50%; bottom: 100%;" alt="Plant Icon" v-if="icon4">
    </div>

    <div class="border border-dark" :style="{ width: (80/garden_length) + 'vh', height: (80/garden_length) + 'vh', backgroundColor: colour}" v-on:dragover="mouseclick" v-on:click="mouseclick" v-else>
        <input type="hidden" :name="grid_row + ',' + grid_column" :value="colour" />
        <img class="card-img img-fluid icon" :src=icon1 alt="Plant Icon" v-if="icon1">
        <img class="card-img img-fluid icon" :src=icon2 style="left:50%; bottom:50%;" alt="Plant Icon" v-if="icon2">
        <img class="card-img img-fluid icon" :src=icon3 style="bottom: 50%;" alt="Plant Icon" v-if="icon3">
        <img class="card-img img-fluid icon" :src=icon4 style="left: 50%; bottom: 100%;" alt="Plant Icon" v-if="icon4">
    </div>

</template>

<script>
    export default {
        props:{
            garden_width: Number,
            garden_length: Number,
            grid_row: Number,
            grid_column: Number,
            colour: String,
            picture1: String,
            picture2: String,
            picture3: String,
            picture4: String
        },
        methods:{
            mouseclick: function(){
                this.colour = this.$store.state.tile.colour;
            }
        },
        computed: {
            icon1() {
                if (this.picture == "http://localhost/Laravel/Gardeners-Diary/public/storage/images") {
                    return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                } else {
                    return this.picture;
                    // return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                }
            },
            icon2() {
                if (this.picture == "http://localhost/Laravel/Gardeners-Diary/public/storage/images") {
                    return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                } else {
                    return this.picture;
                    // return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                }
            },
            icon3() {
                if (this.picture == "http://localhost/Laravel/Gardeners-Diary/public/storage/images") {
                    return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                } else {
                    return this.picture;
                    // return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                }
            },
            icon4() {
                if (this.picture == "http://localhost/Laravel/Gardeners-Diary/public/storage/images") {
                    return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                } else {
                    return this.picture;
                    // return "http://localhost/Laravel/Gardeners-Diary/public/Images/rose.png";
                }
            }
        }
    }
</script>

<style scoped>
    .icon {
        position: relative;
        width: 50%;
        height: 50%;
    }
</style>