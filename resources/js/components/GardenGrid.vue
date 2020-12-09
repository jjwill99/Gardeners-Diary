<template>
    <div class="border border-dark" :style="{ width: (80/garden_width) + 'vw', height: (80/garden_width) + 'vw', backgroundColor: colour}" v-on:dragover="mouseclick" v-if="garden_width >= garden_length"></div>

    <div class="border border-dark" :style="{ width: (80/garden_length) + 'vh', height: (80/garden_length) + 'vh', backgroundColor: colour}" v-on:dragover="mouseclick" v-else></div>

</template>

<script>
    export default {
        props:{
            garden_width: Number,
            garden_length: Number,
            colour: {
                type: String,
                default: "green"
            }
        },
        methods:{
            mouseclick: function(){
                this.colour = this.$store.state.tile.colour;
            }
        }
    }
</script>