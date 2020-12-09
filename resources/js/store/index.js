import Vue from "vue";
import Vuex from "vuex";
 
Vue.use(Vuex);
 
export default new Vuex.Store({
 state: {
     tile: {
         selected: 'Grass',
         colour: 'green'
     }
 },
 getters: {},
 mutations: {
     changeSelected (state, payload) {
         state.tile.selected = payload
     },
     changeColour (state, payload) {
         state.tile.colour = payload
     }
 },
 actions: {}
});