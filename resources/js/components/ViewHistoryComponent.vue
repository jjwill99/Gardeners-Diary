<template>
    <div>

        <div class="sidebar border border-dark rounded">
            <button class="btn btn-primary" style="width: 100%;" v-on:click="goBack()">Back Button</button>
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
            historyId: '',
            gardenResult: [],
            locationResults: [],
            plantResults: [],
            grid: [],
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
        tile_size() {
            return this.isWide ? (80/this.gardenResult.width) : (80/this.gardenResult.length);
        },
        dimensionUnit() {
            return this.isWide ? 'vw' : 'vh';
        },
        icon() {
            return {
                position: 'absolute',
                width: this.tile_size/2 + this.dimensionUnit,
                height: this.tile_size/2 + this.dimensionUnit
            }
        }
    },
    mounted() {
        var params = new URLSearchParams(document.location.search.substring(1));
        this.historyId = params.get('id');

        this.gardenResult = [];
        var temp = this;


        axios.get('/api/checkHistoryUser', {params: {historyId: temp.historyId}})
        .then(function (response) {
            temp.validated = response.data;

            if (temp.validated.value) {
                axios.get('/api/getGardenHistory', {params: {historyId: temp.historyId}})
                .then(function(response) {temp.gardenResult = response.data});

                temp.locationResults = [];
                axios.get('/api/getPlantLocationHistory', {params: {historyId: temp.historyId}})
                .then(function(response) {temp.locationResults = response.data});

                temp.plantResults = [];
                axios.get('/api/getPlantHistory', {params: {historyId: temp.historyId}})
        .then(function(response) {temp.plantResults = response.data});
            } else {
                temp.$router.push('gardens');
            }
        });


        // axios.get('/api/getGardenHistory', {params: {historyId: temp.historyId}})
        // .then(function(response) {temp.gardenResult = response.data});

        // this.locationResults = [];
        // axios.get('/api/getPlantLocationHistory', {params: {historyId: temp.historyId}})
        // .then(function(response) {temp.locationResults = response.data});

        // this.plantResults = [];
        // axios.get('/api/getPlantHistory', {params: {historyId: temp.historyId}})
        // .then(function(response) {temp.plantResults = response.data});
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
        },
        goBack(){
            window.history.back();
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