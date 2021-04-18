<template>
    <div class="container">

        <garden-form :value="this.showGardenForm" @closePopup="showGardenForm = false" @getGardens="getGardens()"></garden-form>

        <div class="row">
            <div :key="garden.id" v-for="garden in gardens">
                <div class="card ml-2 mr-2" style="width: 20rem;">
                    <router-link :to="{path: '/garden?id=' + garden.id}">
                        <img class="card-img img-fluid" :src="garden.picture" style="height: 14vw" alt="Garden Item Image">

                        <div class="card-img-overlay d-flex">
                            <h1 class="align-self-center mx-auto text-white garden-name">{{ garden.name }}</h1>
                        </div>
                    </router-link>
                </div>
                <center><button class="btn btn-danger mb-2" style="width: 20rem;" @click="deleteGarden(garden.id)">Delete Garden</button></center>
            </div>
        </div>
        <button class="btn btn-primary" @click="openPopup()">Add New Garden</button>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                results: [],
                showGardenForm: false
            }
        },
        computed: {
            gardens: function(){
                var newArray = [];

                this.results.forEach(result => {
                    var picture = result.picture;

                    if (picture === null) {
                        picture = "./Images/gardenItem.jpg"; //Create this as default data variable
                    } else {
                        picture = "./storage/images/" + picture;
                    }

                    newArray.push({
                        id: result.id,
                        name: result.name,
                        picture: picture
                    });
                });

                return newArray;
            }
        },
        mounted(){
                this.getGardens();
        },
        methods: {
            deleteGarden: function(id){
                var temp = this;

                axios.post('/api/deleteGarden', {id:id})
                .then(function(response) {
                    temp.getGardens();
                });
            },
            openPopup(){
                this.showGardenForm = true;
            },
            getGardens(){
                this.results = [];
                var temp = this;

                axios.get('/api/getGardens')
                .then(function(response) {temp.results = response.data});
            }
        }
    }
</script>

<style scoped>

    .garden-name {
        font-weight: bold;
        color: white;
        text-shadow:
            -2px -2px 0 #000,
            2px -2px 0 #000,
            -2px 2px 0 #000,
            2px 2px 0 #000; 
    }

    .btn {
        font-size: 1.5vh;
        font-weight: bold;
    }

</style>