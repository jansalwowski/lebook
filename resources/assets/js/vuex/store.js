import Vue from 'vue'
import Vuex from 'vuex'

// Make vue aware of vuex
Vue.use(Vuex);

// We create an object to hold the initial state when
// the app starts up
const state = {
    posts: [],
    lastPost: 0,
    user: {}
};

// Create an object storing various mutations. We will write the mutation
const mutations = {
    // TODO: set up our mutations
};

// We combine the intial state and the mutations to create a vuex store.
// This store can be linked to our app.
export default new Vuex.Store({
    state,
    mutations
});