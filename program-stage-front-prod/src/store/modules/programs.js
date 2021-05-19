import axios from 'axios'

const state = {
    programs: []
}

const getters = {
    allPrograms: state => state.programs
}

const actions = {
    reloadPrograms({commit})
    {
        axios.get("http://128.16.7.38:8022/api/home").then(response => {
        commit('loadPrograms', response.data);
    });
    }
}

const mutations = {
    loadPrograms(state, programs)
    {
        state.programs = programs;
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
