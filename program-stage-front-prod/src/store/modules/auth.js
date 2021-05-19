import axios from 'axios'

const state = {
    status: '',
    token: localStorage.getItem('token') || '',
    user: localStorage.getItem('user') || '',
    role: localStorage.getItem('role') || '',
}

const getters = {
    isLoggedIn: state => !!state.token,
    authStatus: state => state.status,
    loggedUser: state => state.user,
    role: state => state.role,
}

const actions = {
    login({ commit }, user) {
        
        return new Promise((resolve, reject) => {
            commit('auth_request')
            axios.post('http://128.16.7.38:8022/api/login', user)
                .then(response => {
                    const token = response.data.token
                    const logged_user = response.data.user.name
                    const role = response.data.user.role
                    localStorage.setItem('token', token)
                    localStorage.setItem('user', logged_user)
                    localStorage.setItem('role', role)
                    axios.defaults.headers.common['Authorization'] = token
                    commit('auth_success', response.data)
                    resolve(response)
                })
                .catch(error => {
                    commit('auth_error')
                    localStorage.removeItem('token')
                    localStorage.removeItem('user')
                    localStorage.removeItem('role')
                    reject(error)
                })
        })
    },
    

    logout({ commit }) {
        return new Promise((resolve) => {
            commit('logout')
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            localStorage.removeItem('role')
            delete axios.defaults.headers.common['Authorization']
            resolve()
        })
    }
}

const mutations = {
    
    auth_request(state) {
        state.status = 'loading'
    },
    auth_success(state, data) {
        state.status = 'success'
        state.token = data.token
        state.user = data.user.name
        state.role = data.user.role
    },
    auth_error(state) {
        state.status = 'error'
    },
    logout(state) {
        state.status = ''
        state.token = ''
        state.user = ''
        state.role = ''
    },
}

export default {
    state,
    getters,
    actions,
    mutations
}
