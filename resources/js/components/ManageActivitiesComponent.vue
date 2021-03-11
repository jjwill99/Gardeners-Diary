<template>

<div>

    <create-activity :value="createActivity" :plantId="plantId" @closePopup="closeForm()" @getActivities="getActivities()"></create-activity>
    <edit-activity :activity="activity" @closePopup="closeEdit()" @getActivities="getActivities()"></edit-activity>

    <div v-show="((plantId != -1 && !createActivity) || (plantId == -1 && plantName == 'Overdue')) && activity == null">
        <transition name="model">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Manage {{this.plantName}} Activities
                                </h4>
                                <button type="button" class="close" @click="closePopup()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                    <div class="form-group">

                                        <button class="btn btn-primary mb-2" v-on:click="showForm()" v-if="!showOverdue">Add New Activity</button>
                                        <button class="btn btn-primary mb-2" v-on:click="togglePast()" v-if="showPast == 0 && !showOverdue">View Past Activities</button>
                                        <button class="btn btn-primary mb-2" v-on:click="togglePast()" v-if="showPast == 1">View Current Activities</button>

                                        <div v-bind:key="activity.id" v-for="activity in activities">
                                            <div style="padding: 10px;" v-if="(activity.completed == showPast && !showOverdue) || (showOverdue && activity.due == 'Overdue')">
                                                <div><b>{{ activity.name }}</b></div>
                                                <div>{{ activity.description }}</div>
                                                {{ activity.due }}
                                                <span v-if="activity.frequency != 'null'"><b>Repeats</b></span>
                                                <span style="float: right;">
                                                    <button class="btn btn-primary" v-on:click="setEditActivity(activity)" v-if="showPast == 0">Edit</button>
                                                    <button class="btn btn-success" v-on:click="activityCompleted(activity.id)" v-if="showPast == 0">Done</button>
                                                    <button class="btn btn-danger" v-on:click="deleteActivity(activity.id)" v-if="showPast == 1">Delete</button>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>

</div>

</template>

<script>
export default {
    props: {
            plantId: {
                required: true
            },
            plantName: ''
    },
    data: function(){
        return {
            activitiesResults: [],
            createActivity: false,
            activity: null,
            showPast: 0,
            showOverdue: false
        }
    },
    methods: {
        closePopup(){
            this.$emit('closePopup');
            this.showPast = 0;
        },
        showForm(){
            this.createActivity = true;
        },
        closeForm(){
            this.createActivity = false;
        },
        setEditActivity(activity){
            this.activity = activity;
        },
        closeEdit(){
            this.activity = null;
        },
        getActivities(){
            var temp = this;

            axios.get('/api/getActivities', {params: {id:temp.plantId}})
            .then(function(response) {temp.activitiesResults = response.data});
        },
        activityCompleted(id){
            var temp = this;

            axios.post('/api/completeActivity', {id:id})
            .then(function(response) {
                temp.getActivities();
            });
        },
        togglePast(){
            this.showPast = this.showPast == 0 ? 1 : 0;
        },
        deleteActivity(id){
            var temp = this;

            axios.post('/api/deleteActivity', {id:id, allOccurrences:true})
            .then(function(response) {
                temp.getActivities();
            });
        }
    },
    computed: {
        activities() {
            var newArray = [];
            var today = new Date();
            var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            this.activitiesResults.forEach(activity => {
                var temp = activity;
                temp.time = new Date(temp.time);

                if (temp.time > today) {
                    var minutes = temp.time.getMinutes() < 10 ? "0" + temp.time.getMinutes() : temp.time.getMinutes();

                    temp.due = "Due " + temp.time.getDate() + " " + months[temp.time.getMonth()] + " at " + temp.time.getHours() + ":" + minutes;
                    temp.due = temp.time.getDate() == today.getDate() ? "Due today at " + temp.time.getHours() + ":" + minutes : temp.due;
                    temp.due = temp.time.getDate() == today.getDate()+1 ? "Due tomorrow at " + temp.time.getHours() + ":" + minutes : temp.due;
                } else {
                    temp.due = "Overdue";
                }

                newArray.push(temp);
            });

            return newArray;
        }
    },
    mounted(){
        this.getActivities();
    },
    watch: {
        plantId: function(){
            this.getActivities();
        },
        plantName: function(){
            this.showOverdue = this.plantName == "Overdue" ? true : false;
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

    .modal-body {
        height: 80vh;
        overflow-y: auto;
    }
</style>