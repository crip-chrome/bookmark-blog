import * as mTypes from '../mutations';

const state = {
    current: 1
};

const mutations = {
    [mTypes.curr_change] (state, payload) {
        state.current = payload.page_id;
    }
};

export default {state, mutations};