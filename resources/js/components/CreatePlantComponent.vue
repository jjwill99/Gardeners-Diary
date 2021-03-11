<template>

    <div v-show="value">
        <transition name="model">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Create a new plant
                                </h4>
                                <button type="button" class="close" @click="closePopup()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <form @submit.prevent="addNewPlant">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Enter plant name</label>
                                            <input type="text" name="name" class="form-control" v-model="plantName" />
                                        </div>
                                        <div class="form-group">
                                            <label>Choose plant icon</label>
                                            <br />
                                            <input type="file" name="icon" ref="file" placeholder="Image file" v-on:change="handleImage()" />
                                        </div>
                                        <br />
                                        <div align="center">
                                            <input type="hidden" />
                                            <input type="submit" class="btn btn-primary btn-xs" value="Submit" @click="closePopup()" />
                                            <input type="reset" class="btn btn-primary" />
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
    
<script>
    export default {
        props: {
            value: {
                required: true
            },
            gardenId: {
                required: true
            }
        },
        data: function(){
            return {
                plantName: '',
                icon: null
            }
        },
        methods: {
            closePopup(){
                this.$emit('closePopup');
            },
            addNewPlant(){
                var temp = this;

                let formData = new FormData();
                formData.append('name', this.plantName);
                formData.append('icon', this.icon);
                formData.append('gardenId', this.gardenId);
                
                axios.post('/api/addPlant', formData, {headers: {
                    'Content-Type': 'multipart/form-data'
                }})
                .then(function(response) {
                    temp.$emit('getPlants');
                    temp.plantName = '';
                    temp.icon = null;
                });
            },
            handleImage(){
                this.icon = this.$refs.file.files[0];
            }
        }
    };
</script>

<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>