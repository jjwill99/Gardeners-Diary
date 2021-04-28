<template>

<div>

    <div v-show="value && !showHistory">
        <transition name="model">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    Manage Garden History
                                </h4>
                                <button type="button" class="close" @click="closePopup()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div :key="history.id" v-for="history in historiesResults">
                                            <div style="padding: 10px;">
                                                <div><b>{{ history.name }}</b></div>
                                                <div>Date: {{ history.date }}</div>
                                                <span style="float: right;">
                                                    <router-link class="btn btn-primary" :to="{path: '/gardenHistory?id=' + history.id}">View Garden</router-link>
                                                    <button class="btn btn-danger" v-on:click="deleteHistory(history.id)">Delete</button>
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
export default
{
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
            historiesResults: [],
            showHistory: false
        }
    },
    methods: {
        closePopup(){
            this.$emit('closePopup');
        },
        getHistories(){
            var temp = this;

            axios.get('/api/getHistories', {params: {gardenId:temp.gardenId}})
            .then(function(response) {
                temp.historiesResults = response.data;
                temp.formatDates();
            });
        },
        formatDates(){
            this.historiesResults.forEach(history => {
                var date = new Date(history.date);
                var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                history.date = date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
            });
        },
        deleteHistory(id){
            var temp = this;

            axios.post('/api/deleteHistory', {id:id})
            .then(function(response) {
                temp.getHistories();
            });
        },
        toggleHistory(){
            this.showHistory = !this.showHistory;
        }
    },
    mounted(){
        this.getHistories();
    },
    watch: {
        value: function(){
            this.getHistories();
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