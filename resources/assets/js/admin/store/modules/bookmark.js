import * as mTypes from '../mutations';

const state = {
    rootId: 0
};

const mutations = {
    [mTypes.root_bookmark_change] (state, {id}) {
        state.rootId = id;
    }
};

export default {state, mutations};