import Vue from 'vue';
import * as mTypes from '../mutations';

const state = {
    accessToken: null,
    scopes: []
};

const mutations = {

    [mTypes.access_token_change] (state, payload) {
        Vue.set(state, 'accessToken', payload.accessToken);
    },

    [mTypes.access_scopes_change] (state, payload) {
        Vue.set(state, 'scopes', payload.scopes);
    },

};

export default {state, mutations};