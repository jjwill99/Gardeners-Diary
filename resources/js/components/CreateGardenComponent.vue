<template>

    <div v-show="value">
        <transition name="model">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Create a new garden
                                </h4>
                                <button type="button" class="close" @click="closePopup()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <form @submit.prevent="addNewGarden">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Enter garden name</label>
                                            <input type="text" name="name" class="form-control" v-model="gardenName" />
                                        </div>
                                        <div class="form-group">
                                            <label>Enter garden width</label>
                                            <input type="number" min="0" max="100" class="form-control" v-model="width" />
                                        </div>
                                        <div class="form-group">
                                            <label>Enter garden length</label>
                                            <input type="number" min="0" max="100" class="form-control" v-model="length" />
                                        </div>
                                        <div class="form-group">
                                            <label>Choose garden image</label>
                                            <br />
                                            <input type="file" name="picture" ref="file" placeholder="Image file" v-on:change="handleImage()" />
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
            }
    },
    data(){
        return{
            gardenName: '',
            width: '',
            length: '',
            picture: null
        }
    },
    methods: {
        closePopup(){
                this.$emit('closePopup');
            },
        addNewGarden(){
            var temp = this;
            let formData = new FormData();
            formData.append('name', this.gardenName);
            formData.append('width', this.width);
            formData.append('picture', this.picture);
            formData.append('length', this.length);
            
            axios.post('/api/addGarden', formData, {headers: {
                'Content-Type': 'multipart/form-data'
            }})
            .then(function(response) {
                temp.$emit('getGardens');
                temp.gardenName= '';
                temp.width = '';
                temp.length = '';
                temp.picture = null;
            });
        },
        handleImage(){
            this.picture = this.$refs.file.files[0];
        }
    }
}
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