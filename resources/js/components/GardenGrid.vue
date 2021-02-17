<template>
    
    <div class="border border-dark" :style="{ width: tile_size + dimensionUnit, height: tile_size + dimensionUnit, backgroundColor: colour}" v-on:dragover="mouseclick" v-on:click="mouseclick">
        <input type="hidden" :name="grid_row + ',' + grid_column" :value="colour" />
        <div style="position: relative;">
            <img class="card-img img-fluid" :style=icon :src="pictures[0]" alt="Plant Icon" v-if="pictures[0]">
            <img class="card-img img-fluid" :style="[icon, {left: '50%'}]" :src="pictures[1]" alt="Plant Icon" v-if="pictures[1]">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit}]" :src="pictures[2]" alt="Plant Icon" v-if="pictures[2]">
            <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit, left: '50%'}]" :src="pictures[3]" alt="Plant Icon" v-if="pictures[3]">

            <input type="hidden" :name="'icons,' + grid_row + ',' + grid_column" :value='pictures.join("|")' />
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
            pictures: {
                type: Array,
                validator: (prop) => prop.every(e => typeof e === 'string'),
                default: function(){
                    return []
                },
            }
        },
        methods:{
            mouseclick: function(){
                if(this.$store.state.tile.colour != "icon"){
                    this.colour = this.$store.state.tile.colour;
                } else {
                    if (this.$store.state.tile.iconPosition.includes('1')) {
                        this.pictures[0] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon.substring(6);
                    }
                    if (this.$store.state.tile.iconPosition.includes('2')) {
                        this.pictures[1] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon.substring(6);
                    }
                    if (this.$store.state.tile.iconPosition.includes('3')) {
                        this.pictures[2] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon.substring(6);
                    }
                    if (this.$store.state.tile.iconPosition.includes('4')) {
                        this.pictures[3] = "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.$store.state.tile.icon.substring(6);
                    }
                    this.pictures.push();
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