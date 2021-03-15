<template>

    <div v-show="value">
        <transition name="model">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Save state of garden
                                </h4>
                                <button type="button" class="close" @click="closePopup()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <form @submit.prevent="addNewGarden">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Enter garden history name</label>
                                            <input type="text" class="form-control" v-model="historyName" />
                                        </div>
                                        <div class="form-group">
                                            <label>Enter date</label>
                                            <input type="date" class="form-control" v-model="date" />
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
    data(){
        return{
            historyName: '',
            date: ''
        }
    },
    methods: {
        closePopup(){
                this.$emit('closePopup');
            },
        addNewGarden(){
            var temp = this;

            axios.post('/api/storeHistory', {
                gardenId:temp.gardenId,
                historyName:temp.historyName,
                date:temp.date
            })
            .then(function(response){
                temp.historyName = '';
                temp.date = '';
            });
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