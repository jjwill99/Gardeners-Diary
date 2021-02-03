<template>

    <div class="border-top border-dark" :style="{height:'9vh', padding:'1vh', backgroundColor:background}" v-if="icon===''">
        <div class="border border-dark" :style="{width:'7vh', height:'7vh', backgroundColor: colour, float:'left'}" v-on:click="mouseclick"></div>
        <div class="d-flex" style="height:7vh">
            <center class="align-self-center mx-auto">{{tile_name}}</center>
        </div>
    </div>

    <div class="border-top border-dark" :style="{height:'9vh', padding:'1vh', backgroundColor:background}" v-else>
        <div class="border border-dark" :style="{width:'7vh', height:'7vh', backgroundColor: colour, float:'left'}" v-on:click="iconclick">
            <img class="card-img img-fluid" :src=image alt="Plant Icon">
        </div>
        <div class="d-flex" style="height:7vh">
            <center class="align-self-center mx-auto">{{tile_name}}</center>
        </div>
    </div>

</template>

<script>
    export default {
        props:{
            tile_name: String,
            colour: {
                type: String,
                default: "darkseagreen"
            },
            background_colour: {
                type: String,
                default: "#eee"
            },
            icon: {
                type: String,
                default: ""
            }
        },
        computed: {
            background: function () {
                if (this.$store.state.tile.selected == this.tile_name) {
                    return 'gold';
                } else {
                    return '#eee';
                }
            },
            image: function () {
                return "http://localhost/Laravel/Gardeners-Diary/public/Images/" + this.icon + ".png";
            }
        },
        methods:{
            mouseclick: function(){
                this.$store.commit("changeSelected", this.tile_name);
                this.$store.commit("changeColour", this.colour);
            },
            iconclick: function(){
                this.$store.commit("changeSelected", this.tile_name);
                this.$store.commit("changeColour", "icon");
            }
        }
    }
</script>