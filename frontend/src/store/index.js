import { createStore } from 'vuex';
import createPersistedState from "vuex-persistedstate";
import axios from 'axios';

// root state object.
// each Vuex instance is just a single state tree.
const state = {
  currentUser: {},
  users: {},
  responseErrors: {},
  tasks: {},
  access_token: null,
}

// mutations are operations that actually mutate the state.
// each mutation handler gets the entire state tree as the
// first argument, followed by additional payload arguments.
// mutations must be synchronous and can be recorded by plugins
// for debugging purposes.
const mutations = {
  GET_CURRENT_USER (state, currentUser) {
    return state.currentUser = currentUser;
  },
  SET_RESPONSE_ERRORS (state, errors) {
    return state.responseErrors = errors;
  },
  SET_TASKS (state, tasks) {
    return state.tasks = tasks;
  },
  SET_ACCESS_TOKEN (state, token) {
    return state.access_token = token;
  }
}

const actions = {
    register ({commit}, data={}) {
        axios.post('http://127.0.0.1:8000/api/register', { username: data.username, email: data.email, password: data.password, password_confirmation: data.password_confirm })
            .then(res => {
                  commit('SET_ACCESS_TOKEN', res.data.access_token);
                  commit('GET_CURRENT_USER', data);
                  this.$router.push('/todos');
            }).catch(err => {
              console.log(err)
              commit('SET_RESPONSE_ERRORS', err.response.data.errors);
        })
    },

    login ({commit}, data={}) {
        axios.post('http://127.0.0.1:8000/api/login', { username: data.username, password: data.password })
            .then(res => {
                commit('SET_ACCESS_TOKEN', res.data.access_token);
                    commit('GET_CURRENT_USER', data);
                    this.$router.push('/todos');
            }).catch(err => {
            console.log(err)
            commit('SET_RESPONSE_ERRORS', err.response.data.errors);
        })
    },

    logout ({commit}, data={}) {
      axios.post('http://127.0.0.1:8000/api/logout')
          .then(res => {
            commit('SET_ACCESS_TOKEN', null);
                  commit('GET_CURRENT_USER', data);
                  this.$router.push('/');
          }).catch(err => {
          console.log(err)
          commit('SET_RESPONSE_ERRORS', err.response.data.errors);
      })
  },
}


// getters are functions.
const getters = {
  //currentUser: state => state.currentUser,
}

// A Vuex instance is created by combining the state, mutations, actions,
// and getters.
const store = createStore({
  state,
  getters,
  actions,
  mutations,

  plugins: [createPersistedState()],
})

export default store;
