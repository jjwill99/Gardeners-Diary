/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import store from "./store";
import VueRouter from "vue-router";

require('./bootstrap');

window.Vue = require('vue');

window.Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Welcome from './components/WelcomeComponent';
Vue.component('welcome', Welcome);

import GardenTile from './components/GardenTile.vue';
Vue.component('garden-tile', GardenTile);

import GardensComponent from './components/GardensComponent.vue';
Vue.component('gardens', GardensComponent);

import CreateGarden from './components/CreateGardenComponent';
Vue.component('garden-form', CreateGarden);

import CreatePlant from './components/CreatePlantComponent';
Vue.component('plant-form', CreatePlant);

import ManageActivities from './components/ManageActivitiesComponent'
Vue.component('manage-activities', ManageActivities);

import CreateActivity from './components/CreateActivityComponent';
Vue.component('create-activity', CreateActivity);

import EditActivity from './components/EditActivityComponent';
Vue.component('edit-activity', EditActivity);

import CreateHistory from './components/CreateHistoryComponent';
Vue.component('create-history', CreateHistory);

import ManageHistory from './components/ManageHistoryComponent';
Vue.component('manage-history', ManageHistory);

import Gardens from './components/GardensComponent';
import Garden from './components/GardenComponent';
import EditGarden from './components/EditGardenComponent';
import GardenHistory from './components/ViewHistoryComponent';
import Help from './components/HelpComponent';
import Vue from "vue";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'welcome',
            component: Welcome
        },
        {
            path: '/gardens',
            name: 'gardens',
            component: Gardens
        },
        {
            path: '/garden',
            name: 'garden',
            component: Garden
        },
        {
            path: '/editgarden',
            name: 'edit',
            component: EditGarden
        },
        {
            path: '/gardenHistory',
            name: 'gardenHistory',
            component: GardenHistory
        },
        {
            path: '/help',
            name: 'help',
            component: Help
        },
    ]
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    store,
    el: '#app',
    router,
});