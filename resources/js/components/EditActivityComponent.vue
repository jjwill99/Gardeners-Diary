<template>

    <div v-show="activity != null">
        <transition name="model">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Edit activity
                                </h4>
                                <button type="button" class="close" @click="closePopup()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form @submit.prevent="updateActivity">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Enter activity name</label>
                                        <input type="text" name="name" class="form-control" v-model="name" />
                                    </div>
                                    <div class="form-group">
                                        <label>Enter activity description</label>
                                        <input type="text" class="form-control" v-model="description" />
                                    </div>
                                    <div class="form-group">
                                        <label>Enter activity time</label>
                                        <input type="datetime-local" class="form-control" v-model="time" />
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
                                        <input type="submit" class="btn btn-primary btn-xs" value="Update" />
                                    </div>
                                </div>
                            </form>
                            <div align="center">
                                <button class="btn btn-danger" v-on:click="deleteActivity()">Delete Activity</button>
                                <button class="btn btn-danger" v-on:click="deleteOccurrences()" v-if="frequencyExists">Delete All Occurrences</button>
                            </div>
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
            activity: {
                required: true
            }
    },
    data(){
        return{
            name: '',
            description: '',
            time: '',
            repeat: false,
            frequencyNumber: 1,
            frequencyUnit: '',
            frequencyExists: false
        }
    },
    methods: {
        closePopup(){
                this.$emit('closePopup');
        },
        updateActivity(){
            var temp = this;
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('description', this.description);
            formData.append('time', this.time);
            formData.append('activityId', this.activity.id);

            if (this.repeat) {
                formData.append('frequency', this.frequencyNumber + " " + this.frequencyUnit);
            } else {
                formData.append('frequency', null);
            }

            axios.post('/api/updateActivity', formData, {headers: {
                'Content-Type': 'multipart/form-data'
            }})
            .then(function(response) {
                temp.$emit('getActivities');
                temp.$emit('closePopup');
            });
        },
        deleteActivity(){
            var temp = this;

            axios.post('/api/deleteActivity', {id:this.activity.id, allOccurrences:false})
            .then(function(response) {
                temp.$emit('getActivities');
                temp.$emit('closePopup');
            });
        },
        deleteOccurrences(){
            var temp = this;

            axios.post('/api/deleteActivity', {id:this.activity.id, allOccurrences:true})
            .then(function(response) {
                temp.$emit('getActivities');
                temp.$emit('closePopup');
            });
        }
    },
    watch: {
        activity: function(){
            if (this.activity != null) {
                this.name = this.activity.name;
                this.description = this.activity.description;
                
                var time = new Date(this.activity.time);

                this.time = time.getFullYear() + "-"
                    + (time.getMonth() < 10 ? "0" + (time.getMonth()+1) : time.getMonth()+1) + "-"
                    + (time.getDate() < 10 ? "0" + time.getDate() : time.getDate()) + "T"
                    + (time.getHours() < 10 ? "0" + time.getHours() : time.getHours()) + ":"
                    + (time.getMinutes() < 10 ? "0" + time.getMinutes() : time.getMinutes());
                
                if (this.activity.frequency != "null") {
                    var frequency = this.activity.frequency.split(" ");
                    
                    this.frequencyExists = true;
                    this.repeat = true;
                    this.frequencyNumber = frequency[0];
                    this.frequencyUnit = frequency[1];
                } else {
                    this.frequencyExists = false;
                    this.repeat = false;
                    this.frequencyNumber = 1;
                    this.frequencyUnit = '';
                }
            }
        },
        repeat: function (){
            if (this.repeat) {
                var frequency = this.activity.frequency.split(" ");

                this.frequencyNumber = frequency[0];
                this.frequencyUnit = frequency[1];
            } else {
                this.frequencyNumber = 1;
                this.frequencyUnit = '';
            }
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