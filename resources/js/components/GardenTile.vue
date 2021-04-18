<template>

    <div class="border-top border-dark" :style="{height:'9vh', padding:'1vh', backgroundColor:background}" v-if="icon===''">
        <div class="border border-dark" :style="{width:'7vh', height:'7vh', backgroundColor: colour, float:'left'}" v-on:click="mouseclick"></div>
        <div class="d-flex" style="height:7vh">
            <center class="align-self-center mx-auto tilename">{{tile_name}}</center>
        </div>
    </div>

    <div class="border-top border-dark" :style="{height:'9vh', padding:'1vh', backgroundColor:background}" v-else>
        <div class="border border-dark" :style="{width:'7vh', height:'7vh', backgroundColor: colour, float:'left'}" v-on:click="iconclick; iconclick('reset')">
            <div style="position: relative;">
                <img class="card-img img-fluid" :style=iconStyle                                    :src="'./storage/images/' + this.icon" alt="Plant Icon" v-on:click="iconclick('1')" v-if="icon_location.includes('1')">
                <img class="card-img img-fluid" :style="[iconStyle, {left: '50%'}]"                 :src="'./storage/images/' + this.icon" alt="Plant Icon" v-on:click="iconclick('2')" v-if="icon_location.includes('2')">
                <img class="card-img img-fluid" :style="[iconStyle, {top: '3.5vh'}]"                :src="'./storage/images/' + this.icon" alt="Plant Icon" v-on:click="iconclick('3')" v-if="icon_location.includes('3')">
                <img class="card-img img-fluid" :style="[iconStyle, {top: '3.5vh', left: '50%'}]"   :src="'./storage/images/' + this.icon" alt="Plant Icon" v-on:click="iconclick('4')" v-if="icon_location.includes('4')">
            </div>
        </div>
        
        <div class="d-flex" style="height:7vh">
            
            <center class="align-self-center mx-auto tilename">
                {{tile_name}}

                <button class="btn btn-danger mb-2" v-on:click="deletePlant(plantId)">Delete All</button>

                <!-- <form :action=del method="post">
                    <input name="_token" type="hidden" :value=csrf>
                    <input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" type="submit" onclick="return confirm('Deleting this will delete all of this plant in this garden.\nDo you want to continue?');">Delete All</button>
                </form> -->

            </center>
        </div>
    </div>

</template>

<script>
    export default {
        props:{
            plantId: Number,
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
            },
            iconPosition: {
                type:String,
                default: "1234"
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
            iconStyle() {
                return {
                    position: 'absolute',
                    width: '3.5vh',
                    height: '3.5vh'
                }
            }
        },
        data: function(){
            return {
                icon_location: this.iconPosition
            }
        },
        methods:{
            mouseclick: function(){
                this.$store.commit("changeSelected", this.tile_name);
                this.$store.commit("changeColour", this.colour);
                this.$store.commit("changeIcon", "colour");
            },
            iconclick: function(x = "0"){
                if (this.$store.state.tile.selected == this.tile_name) {
                    if (this.icon_location.includes(x)) {
                        this.icon_location = this.icon_location.replace(x, '0');
                    } else if (this.icon_location == "0000") {
                        this.icon_location += '0';
                    } else if(this.icon_location == "00000" && x == "reset"){
                        this.icon_location = "1234";
                    }
                } else {
                    this.$store.commit("changeSelected", this.tile_name);
                    this.$store.commit("changeColour", "icon");
                    this.$store.commit("changeIcon", this.icon);
                }
                this.$store.commit("changeIconPosition", this.icon_location);
            },
            deletePlant(id){
                var temp = this;

                if (confirm('Deleting this will delete all of this plant in this garden.\nDo you want to continue?')) {
                    axios.post('/api/deletePlant', {id:id})
                    .then(function(response) {temp.$emit('plantDeleted')});
                }
            }
        }
    }
</script>

<style scoped>

    .btn, .tilename {
            width: 90%;
            font-size: 1.5vh;
            font-weight: bold;
        }

</style>