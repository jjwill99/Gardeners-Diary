<template>
    
    <div class="border border-dark" :style="{ width: tile_size + dimensionUnit, height: tile_size + dimensionUnit, backgroundColor: grid_colour}" v-on:dragover="mouseclick" v-on:click="mouseclick" v-if="page_type == 'edit'">
        <input type="hidden" :name="grid_row + ',' + grid_column" :value="grid_colour" />
        <div style="position: relative;">
            <img class="card-img img-fluid" :style=icon :src="icons[0]" alt="Plant Icon" v-if="icons[0]">
            <img class="card-img img-fluid" :style="[icon, {left: '50%'}]" :src="icons[1]" alt="Plant Icon" v-if="icons[1]">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit}]" :src="icons[2]" alt="Plant Icon" v-if="icons[2]">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit, left: '50%'}]" :src="icons[3]" alt="Plant Icon" v-if="icons[3]">

            <input type="hidden" :name="'icons,' + grid_row + ',' + grid_column" :value='icons.join("|")' />
        </div>
    </div>

    <div :style="{ width: tile_size + dimensionUnit, height: tile_size + dimensionUnit, backgroundColor: grid_colour}" v-else>
        <div style="position: relative;">
            <img class="card-img img-fluid" :style=icon :src="icons[0]" alt="Plant Icon" v-if="icons[0]">
            <img class="card-img img-fluid" :style="[icon, {left: '50%'}]" :src="icons[1]" alt="Plant Icon" v-if="icons[1]">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit}]" :src="icons[2]" alt="Plant Icon" v-if="icons[2]">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit, left: '50%'}]" :src="icons[3]" alt="Plant Icon" v-if="icons[3]">
        </div>
    </div>

</template>

<script>
    export default {
        props:{
            page_type: String,
            garden_width: Number,
            garden_length: Number,
            grid_row: Number,
            grid_column: Number,
            colour: String,
            pictures: {
                type: Array,
                validator: (prop) => prop.every(e => typeof e === 'string'),
                default: function(){
                    return []
                },
            }
        },
        data: function(){
            return {
                icons: this.pictures,
                grid_colour: this.colour
            }
        },
        methods:{
            mouseclick: function(){
                if(this.$store.state.tile.colour != "icon"){
                    this.grid_colour = this.$store.state.tile.colour;
                } else {
                    if (this.$store.state.tile.iconPosition.includes('1')) {
                        this.icons[0] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon;
                    }
                    if (this.$store.state.tile.iconPosition.includes('2')) {
                        this.icons[1] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon;
                    }
                    if (this.$store.state.tile.iconPosition.includes('3')) {
                        this.icons[2] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon;
                    }
                    if (this.$store.state.tile.iconPosition.includes('4')) {
                        this.icons[3] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon;
                    }
                    this.icons.push();
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