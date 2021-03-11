<template>
    <div>

        <manage-activities :plantId="plantId" :plantName="plant_name" @closePopup='closePopup()'></manage-activities>

        <div class="sidebar border border-dark rounded">
            <center>
                ACTIONS:

                <button class="btn btn-danger m-1" style="width: 90%; height: 3vw;" v-on:click="openActivities(-1, 'Overdue')">
                    Overdue Activities
                </button>

                <div v-bind:key="plant.id" v-for="plant in plantResults">
                    <button class="btn btn-primary m-1" style="width: 90%; height: 3vw;" v-on:click="openActivities(plant.id, plant.plant_name)">
                        <img style="height: 2.5vw; float: left;" :src="'./storage/images/' + plant.icon">
                        {{ plant.plant_name }} Activities
                    </button>
                </div>

                <router-link class="btn btn-primary m-1" style="width: 90%; height: 3vw;" :to="{path: '/garden?id=' + this.gardenId}">View Garden History</router-link>
                <button class="btn btn-primary m-1" style="width: 90%; height: 3vw;" v-on:click="saveGarden()">Save To History</button>
                <router-link class="btn btn-primary m-1" style="width: 90%; height: 3vw;" :to="{path: '/editgarden?id=' + this.gardenId}">Edit Garden</router-link>
            </center>
        </div>
    
        <div class="grid">
            <div v-bind:key="row.index" v-for="row in rows">
                <div class="row justify-content-center">
                    <div v-bind:key="cell.index" v-for="cell in row">
                        <div :style="{ width: tile_size + dimensionUnit, height: tile_size + dimensionUnit, backgroundColor: cell.colour}">
                            <div style="position: relative;">
                                <div v-bind:key="location.index" v-for="location in locationResults">
                                    <img class="card-img img-fluid" :style=icon :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('1')" v-on:click="openActivities(location.plant_id, location.plant_name)">
                                    <img class="card-img img-fluid" :style="[icon, {left: '50%'}]" :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('2')" v-on:click="openActivities(location.plant_id, location.plant_name)">
                                    <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit}]" :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('3')" v-on:click="openActivities(location.plant_id, location.plant_name)">
                                    <img class="card-img img-fluid" :style="[icon, {top: tile_size/2 + dimensionUnit, left: '50%'}]" :src="'./storage/images/' + location.icon" alt="Plant Icon"
                                        v-if="location.row == cell.row && location.column == cell.column && location.icon_location.includes('4')" v-on:click="openActivities(location.plant_id, location.plant_name)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import GardenGrid from './GardenGrid.vue';
export default {
  components: { GardenGrid },
    data: function(){
        return {
            gardenId: '',
            gardenResult: [],
            locationResults: [],
            plantResults: [],
            grid: [],
            plantId: -1,
            plant_name: ''
        }
    },
    computed: {
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

        this.locationResults = [];
        axios.get('/api/getPlantLocations', {params: {gardenId: temp.gardenId}})
        .then(function(response) {temp.locationResults = response.data});

        this.plantResults = [];
        axios.get('/api/getPlants', {params: {gardenId: temp.gardenId}})
        .then(function(response) {temp.plantResults = response.data});
    },
    methods: {
        saveGarden(){
            //Save state of garden to GardenHistories table
        },
        openActivities(id, name){
            this.plantId = id;
            this.plant_name = name;
        },
        closePopup(){
            this.plantId = -1;
            this.plant_name = "";
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