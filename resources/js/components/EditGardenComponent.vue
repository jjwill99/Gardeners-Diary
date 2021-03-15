<template>
    <div>

        <plant-form :value="this.showPlantForm" :gardenId="this.gardenId" @closePopup="showPlantForm = false" @getPlants="getPlants()"></plant-form>

        <div class="sidebar border border-dark rounded">
            <center>SELECT A TILE:</center>
            <garden-tile colour="green" tile_name="Grass" background_colour="gold"></garden-tile>
            <garden-tile colour="saddleBrown" tile_name="Soil"></garden-tile>
            <garden-tile colour="burlyWood" tile_name="Tile"></garden-tile>
            <garden-tile colour="lightSkyBlue" tile_name="Water"></garden-tile>

            <div v-bind:key="plant.id" v-for="plant in plants">
                <garden-tile :plantId="plant.id" :icon="plant.icon" :tile_name="plant.name" @plantDeleted="getPlantsAndLocations()"></garden-tile>
            </div>

            <center>
                <button class="btn btn-primary rounded-0" style="width: 49%;" v-on:click="openPopup()">Add Plant</button>
                <button class="btn btn-primary rounded-0" style="width: 49%;" v-on:click="saveLayout()">Save Layout</button>

                <router-link class="btn btn-success mt-2" style="width: 100%;" :to="{path: '/garden?id=' + this.gardenId}">Exit Edit Mode</router-link>
            </center>
        </div>
    
        <div class="grid">
            <div v-bind:key="row.index" v-for="row in rows">
                <div class="row justify-content-center">
                    <div v-bind:key="cell.index" v-for="cell in row">
                        <div class="border border-dark" :style="{ width: tile_size + dimensionUnit, height: tile_size + dimensionUnit, backgroundColor: cell.colour}"
                          @mousedown="mousedown(cell.row, cell.column)" @mousemove="updateCell(cell.row, cell.column)" @mouseup="mouseup()">
                            <div style="position: relative;">
                                <div v-bind:key="location.index" v-for="location in locationResults">
                                    <img class="card-img img-fluid" :style=icon :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('1')">
                                    <img class="card-img img-fluid" :style="[icon, {left: '50%'}]" :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('2')">
                                    <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit}]" :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('3')">
                                    <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit, left: '50%'}]" :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('4')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script> //Vue Mixin?
import GardenGrid from './GardenGrid.vue'; //Not need?
export default {
  components: { GardenGrid },
    data: function(){
        return {
            gardenId: '',
            gardenResult: [],
            plantResults: [],
            locationResults: [],
            grid: [],
            mouseActive: false,
            showPlantForm: false
        }
    },
    computed: {
        plants(){
            var newArray = [];

            this.plantResults.forEach(result => {
                newArray.push({
                    id: result.id,
                    name: result.plant_name,
                    icon: result.icon
                });
            });

            return newArray;
        },
        rows(){
            try{
                this.grid = JSON.parse(this.gardenResult.grid);
            } catch {}

            var gridArray = [];
            var rowArray = [{}];
            var lastRow = -1;

            this.grid.forEach(cell => {
                if (lastRow == cell.row) {
                    rowArray.push(cell);
                } else {
                    gridArray[lastRow] = rowArray;

                    rowArray = [];
                    rowArray.push(cell);
                }

                lastRow = cell.row;
            });

            gridArray[lastRow] = rowArray;

            return gridArray;
        },
        isWide() {
            if (this.gardenResult.width >= this.gardenResult.length) {
                return true;
            } else {
                return false;
            }
        },
        tile_size() {
            if (this.isWide) {
                return (80/this.gardenResult.width);
            } else {
                return (80/this.gardenResult.length);
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
    },
    mounted() {
        var params = new URLSearchParams(document.location.search.substring(1));
        this.gardenId = params.get('id');

        this.gardenResult = [];
        var temp = this;
        axios.get('/api/getGarden', {params: {gardenId: temp.gardenId}})
        .then(function(response) {temp.gardenResult = response.data});

        this.getPlantsAndLocations();
    },
    methods: {
        saveLayout(){
            var temp = this;
            let formData = new FormData();

            formData.append('grid', JSON.stringify(this.grid));
            formData.append('gardenId', this.gardenResult.id);

            formData.append('locations', JSON.stringify(this.locationResults));
            
            axios.post('/api/updateGarden', formData)
            .then(function(response) {
                temp.$router.push('/garden?id=' + temp.gardenId);
            });
        },
        mousedown(row, column){
            var temp = this;

            this.mouseActive = true;
            this.updateCell(row, column);

            var removeLocations = [];
            var locationExists = false;

            if(temp.$store.state.tile.colour == "icon"){
                temp.locationResults.forEach(function(location, index) {
                    if (location.row == row && location.column == column && location.icon == temp.$store.state.tile.icon) {
                        if (temp.$store.state.tile.iconPosition == "00000") {
                            removeLocations.push(index);
                        } else {
                            temp.$store.state.tile.iconPosition.split("").forEach(char => {
                                if (!location.icon_location.includes(char)) {
                                    location.icon_location += char;
                                }
                            });
                        }
                        locationExists = true;
                    } else if (location.row == row && location.column == column && location.icon != temp.$store.state.tile.icon){
                        if (temp.$store.state.tile.iconPosition != "00000") {
                            temp.$store.state.tile.iconPosition.split("").forEach(char => {
                                if (location.icon_location.includes(char)) {
                                    location.icon_location = location.icon_location.replace(char, '');
                                }
                            });
                        }
                    }
                });

                removeLocations.forEach(index => {
                    this.locationResults.splice(index, 1);
                });

                if(!locationExists && this.$store.state.tile.iconPosition != "00000") {
                    var newLocation = {};

                    newLocation.row = row;
                    newLocation.column = column;
                    newLocation.icon = this.$store.state.tile.icon;
                    newLocation.icon_location = this.$store.state.tile.iconPosition;
                    newLocation.id = -1;

                    this.locationResults.push(newLocation);
                }
            }
        },
        mouseup(){
            this.mouseActive = false;
        },
        updateCell(row, column){
            if (this.mouseActive) {
                if(this.$store.state.tile.colour != "icon"){
                        this.grid.forEach(cell => {
                            if (cell.row == row && cell.column == column) {
                                cell.colour = this.$store.state.tile.colour;
                            } 
                        });
                }
            }
        },
        openPopup(){
            this.showPlantForm = true;
        },
        getPlants(){
            var temp = this;

            this.plantResults = [];
            axios.get('/api/getPlants', {params: {gardenId: temp.gardenId}})
            .then(function(response) {temp.plantResults = response.data});
        },
        getPlantsAndLocations(){
            var temp = this;

            this.plantResults = [];
            axios.get('/api/getPlants', {params: {gardenId: temp.gardenId}})
            .then(function(response) {temp.plantResults = response.data});

            this.locationResults = [];
            axios.get('/api/getPlantLocations', {params: {gardenId: temp.gardenId}})
            .then(function(response) {temp.locationResults = response.data});
        }
    }
}
</script>

<style>
    .grid {
        margin-left: 15vw;
        margin-right: 5vw;
    }

    .sidebar {
        width: 12vw;
        position: fixed;
        z-index: 1;
        top: 75px;
        left: 10px;
        background: #eee;
        overflow-x: hidden;
    }
</style>