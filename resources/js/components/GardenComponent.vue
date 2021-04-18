<template>
    <div>

        <manage-activities :plantId="plantId" :plantName="plant_name" @closePopup='closePopup()'></manage-activities>
        <manage-history :value="manageHistoryPopup" :gardenId="gardenId" @closePopup='toggleManageHistory()'></manage-history>
        <create-history :value="createHistoryPopup" :gardenId="gardenId" @closePopup='toggleStoreHistory()'></create-history>

        <div class="sidebar border border-dark rounded">
            <center>
                <div class="sidebar-title">ACTIONS:</div>

                <!-- <button class="btn btn-danger m-1" style="width: 90%; height: 3vw;" @click="openActivities(-1, 'Overdue')"> -->
                <button class="btn btn-danger m-1" @click="openActivities(-1, 'Overdue')">
                    Overdue Activities
                </button>

                <div :key="plant.id" v-for="plant in plantResults">
                    <button class="btn btn-primary m-1" @click="openActivities(plant.id, plant.plant_name)">
                        <img style="height: 2.5vw; float: left;" :src="'./storage/images/' + plant.icon">
                        {{ plant.plant_name }} Activities
                    </button>
                </div>

                <button class="btn btn-primary m-1" v-on:click="toggleManageHistory()">View Garden History</button>
                <button class="btn btn-primary m-1" v-on:click="toggleStoreHistory()">Save To History</button>
                <router-link class="btn btn-primary m-1" :to="{path: '/editgarden?id=' + this.gardenId}">Edit Garden</router-link>
            </center>
        </div>
    
        <div class="grid" id="garden">
            <div :key="row.index" v-for="row in rows">
                <div class="row justify-content-center">
                    <div :key="cell.index" v-for="cell in row">
                        <div :style="{ width: tile_size + dimensionUnit, height: tile_size + dimensionUnit, backgroundColor: cell.colour}">
                            <div style="position: relative;">
                                <div :key="location.index" v-for="location in locationResults">
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
export default {
    data: function(){
        return {
            gardenId: '',
            gardenResult: [],
            locationResults: [],
            plantResults: [],
            grid: [],
            plantId: -1,
            plant_name: '',
            manageHistoryPopup: false,
            createHistoryPopup: false,
            validated: []
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

            this.grid.forEach(function(cell) {
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
            return this.gardenResult.width >= this.gardenResult.length;
        },
        tile_size() { //Terniary
            if (this.isWide) {
                return (80/this.gardenResult.width);
            }
            return (80/this.gardenResult.length);
        },
        dimensionUnit() {
            if(this.isWide){
                return 'vw';
            }
            return 'vh';
        },
        icon() {
            var unit = this.isWide ? 'vw' : 'vh';

            return {
                position: 'absolute',
                width: this.tile_size/2 + unit,
                height: this.tile_size/2 + unit
            }
        }
    },
    mounted() {
        var params = new URLSearchParams(document.location.search.substring(1));
        this.gardenId = params.get('id');

        this.gardenResult = [];
        var temp = this;

        axios.get('/api/checkGardenUser', {params: {gardenId: temp.gardenId}})
        .then(function (response) {
            temp.validated = response.data;

            if (temp.validated.value) {
                axios.get('/api/getGarden', {params: {gardenId: temp.gardenId}})
                .then(function(response) {temp.gardenResult = response.data});

                temp.locationResults = [];
                axios.get('/api/getPlantLocations', {params: {gardenId: temp.gardenId}})
                .then(function(response) {temp.locationResults = response.data});

                temp.plantResults = [];
                axios.get('/api/getPlants', {params: {gardenId: temp.gardenId}})
                .then(function(response) {temp.plantResults = response.data});
            } else {
                temp.$router.push('gardens');
            }
        });
    },
    methods: {
        toggleManageHistory(){
            this.manageHistoryPopup = !this.manageHistoryPopup;
        },
        toggleStoreHistory(){
            this.createHistoryPopup = !this.createHistoryPopup;
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

<style scoped>
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

    .btn, .sidebar-title {
        width: 90%;
        font-size: 1.5vh;
        font-weight: bold;
    }
</style>