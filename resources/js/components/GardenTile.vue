<template>

    <div class="border-top border-dark" :style="{height:'9vh', padding:'1vh', backgroundColor:background}" v-if="icon===''">
        <div class="border border-dark" :style="{width:'7vh', height:'7vh', backgroundColor: colour, float:'left'}" v-on:click="mouseclick"></div>
        <div class="d-flex" style="height:7vh">
            <center class="align-self-center mx-auto">{{tile_name}}</center>
        </div>
    </div>

    <div class="border-top border-dark" :style="{height:'9vh', padding:'1vh', backgroundColor:background}" v-else>
        <div class="border border-dark" :style="{width:'7vh', height:'7vh', backgroundColor: colour, float:'left'}" v-on:click="iconclick; iconclick('reset')">
            <div style="position: relative;">
                <img class="card-img img-fluid" :style=iconStyle                                    :src=image alt="Plant Icon" v-on:click="iconclick('1')" v-if="iconPosition.includes('1')">
                <img class="card-img img-fluid" :style="[iconStyle, {left: '50%'}]"                 :src=image alt="Plant Icon" v-on:click="iconclick('2')" v-if="iconPosition.includes('2')">
                <img class="card-img img-fluid" :style="[iconStyle, {top: '3.5vh'}]"                :src=image alt="Plant Icon" v-on:click="iconclick('3')" v-if="iconPosition.includes('3')">
                <img class="card-img img-fluid" :style="[iconStyle, {top: '3.5vh', left: '50%'}]"   :src=image alt="Plant Icon" v-on:click="iconclick('4')" v-if="iconPosition.includes('4')">
            </div>
        </div>
        
        <div class="d-flex" style="height:7vh">
            
            <center class="align-self-center mx-auto">
                {{tile_name}}

                <form :action=del method="post">
                    <input name="_token" type="hidden" :value=csrf>
                    <input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" type="submit" onclick="noConfirm()">Delete</button>
                </form>

            </center>
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
            },
            del: {
                type: String,
                default: "{{ action('GardenController@destroy', $garden->id) }}"
            },
            csrf: {
                type:String,
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
            image: function () {
                if (this.icon.substring(0, 6) == "custom") {
                    return "http://localhost/Laravel/Gardeners-Diary/public/storage/images/" + this.icon.substring(6);
                } else {
                    return "http://localhost/Laravel/Gardeners-Diary/public/Images/" + this.icon + ".png";
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
        methods:{
            mouseclick: function(){
                this.$store.commit("changeSelected", this.tile_name);
                this.$store.commit("changeColour", this.colour);
                this.$store.commit("changeIcon", "colour");
            },
            iconclick: function(x = "0"){
                if (this.$store.state.tile.selected == this.tile_name) {
                    if (this.iconPosition.includes(x)) {
                        this.iconPosition = this.iconPosition.replace(x, '0');
                    } else if (this.iconPosition == "0000") {
                        this.iconPosition += '0';
                    } else if(this.iconPosition == "00000" && x == "reset"){
                        this.iconPosition = "1234";
                    }
                } else {
                    this.$store.commit("changeSelected", this.tile_name);
                    this.$store.commit("changeColour", "icon");
                    this.$store.commit("changeIcon", this.icon);
                }
                this.$store.commit("changeIconPosition", this.iconPosition);
            }
        }
    }
</script>