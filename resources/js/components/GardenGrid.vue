<template>
    <div class="border border-dark" :style="{ width: (80/garden_width) + 'vw', height: (80/garden_width) + 'vw', backgroundColor: colour}" v-on:dragover="mouseclick" v-on:click="mouseclick" v-if="garden_width >= garden_length">
        <input type="hidden" :name='grid_row + grid_column' :value='{"row":grid_row, "column":grid_column, "colour":colour}' />
    </div>

    <div class="border border-dark" :style="{ width: (80/garden_length) + 'vh', height: (80/garden_length) + 'vh', backgroundColor: colour}" v-on:dragover="mouseclick" v-on:click="mouseclick" v-else>
        <!-- <input type="hidden" name="test" :value='{"row":grid_row, "column":grid_column, "colour":colour}' /> -->
        <input type="hidden" :name="grid_row + '' + grid_column" :value="colour" />
    </div>

</template>

<script>
    export default {
        props:{
            garden_width: Number,
            garden_length: Number,
            grid_row: Number,
            grid_column: Number,
            colour: String
        },
        methods:{
            mouseclick: function(){
                this.colour = this.$store.state.tile.colour;
            }
        }
    }
</script>