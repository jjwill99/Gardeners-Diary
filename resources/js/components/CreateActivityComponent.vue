<template>

    <div v-show="value">
        <transition name="model">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Create a new activity
                                </h4>
                                <button type="button" class="close" @click="closePopup()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <form @submit.prevent="addNewActivity">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Enter activity name</label>
                                            <input type="text" name="name" class="form-control" v-model="name" />
                                        </div>
                                        <div class="form-group">
                                            <label>Enter activity description (optional)</label>
                                            <input type="text" class="form-control" v-model="description" />
                                        </div>
                                        <div class="form-group">
                                            <label>Enter activity time</label>
                                            <input type="datetime-local" class="form-control" v-model="time" />
                                        </div>
                                        <div class="form-group" v-if="!repeat">
                                            <input type="checkbox" v-model="completed" />
                                            <label>Already completed</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" v-model="repeat" />
                                            <label>Set To Repeat</label>
                                        </div>

                                        <div v-if="repeat">
                                            <div class="form-group">
                                                <label>Enter Activity Frequency</label>
                                                <br />
                                                <input type="number" min="1" v-model="frequencyNumber" />
                                                <select v-model="frequencyUnit">
                                                    <option value="day">Day/s</option>
                                                    <option value="week">Week/s</option>
                                                    <option value="month">Month/s</option>
                                                </select>
                                            </div>
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
            plantId: {
                required: true
            }
    },
    data(){
        return{
            name: '',
            description: '',
            time: '',
            repeat: false,
            completed: false,
            frequencyNumber: 1,
            frequencyUnit: 'day'
        }
    },
    methods: {
        closePopup(){
                this.$emit('closePopup');
            },
        addNewActivity(){
            var temp = this;
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('description', this.description);
            formData.append('time', this.time);
            formData.append('completed', this.repeat ? false : this.completed);
            formData.append('plantId', this.plantId);
            
            if (this.repeat) {
                formData.append('frequency', this.frequencyNumber + " " + this.frequencyUnit);
            } else {
                formData.append('frequency', null);
            }

            axios.post('/api/addActivity', formData, {headers: {
                'Content-Type': 'multipart/form-data'
            }})
            .then(function(response) {
                temp.$emit('getActivities');
                temp.name = '';
                temp.description = '';
                temp.time = '';
                temp.completed = false;
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