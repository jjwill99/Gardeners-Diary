<template>
    
    <div class="border border-dark" :style="{ width: tile_size + dimensionUnit, height: tile_size + dimensionUnit, backgroundColor: colour}" v-on:dragover="mouseclick" v-on:click="mouseclick">
        <input type="hidden" :name="grid_row + ',' + grid_column" :value="colour" />
        <div style="position: relative;">
            <img class="card-img img-fluid" :style=icon :src=icon1 alt="Plant Icon" v-if="icon1">
            <img class="card-img img-fluid" :style="[icon, {left: '50%'}]" :src=icon2 alt="Plant Icon" v-if="icon2">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit}]" :src=icon3 alt="Plant Icon" v-if="icon3">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit, left: '50%'}]" :src=icon4 alt="Plant Icon" v-if="icon4">
        </div>
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
                if(this.$store.state.tile.colour != "icon"){
                    this.colour = this.$store.state.tile.colour;
                }
            }
        },
        computed: {
            isWide() {
                if (this.garden_width >= this.garden_length) {
                    return true;
                } else {
                    return false;
                }
            },
            tile_size() {
                if (this.isWide) {
                    return (80/this.garden_width);
                } else {
                    return (80/this.garden_length);
                }
            },
            dimensionUnit() {
                if(this.isWide){
                    return 'vw';
                } else {
                    return 'vh';
                }
            },
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
            },
            icon() {
                if (this.isWide) {
                    return {
                        position: 'absolute',
                        width: this.tile_size/2 + 'vw',
                        height: this.tile_size/2 + 'vw'
                    }
                } else {
                    return {
                        position: 'absolute',
                        width: this.tile_size/2 + 'vh',
                        height: this.tile_size/2 + 'vh'
                    }
                }
            }
        }
    }
</script>