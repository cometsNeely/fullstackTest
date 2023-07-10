<template>
  <router-view />
</template>

<script>
import { createApp, defineComponent, computed } from "vue";
import store from "./store";
import axios from 'axios';

createApp().use(store).mount("#app");

//let access_token = computed(() => store.state.access_token)

axios.interceptors.request.use(config => {
        //const isApiUrl = request.url.startsWith(process.env.VUE_APP_API_URL);
        //console.log(store.state.access_token)
        if (store.state.access_token !== null) {
            config.headers.authorization = `Bearer ${store.state.access_token}`;
        }
        return config;
    }, error => { console.log(error.response) });

axios.interceptors.response.use(config => {
        //const isApiUrl = request.url.startsWith(process.env.VUE_APP_API_URL);
        //console.log(store.state.access_token)
        if (store.state.access_token !== null) {
            config.headers.authorization = `Bearer ${store.state.access_token}`;
        }
        return config;
    }, error => { 
      console.log(error)
          if (store.state.access_token !== null) {
          if (error.response.data.message === 'Token has expired') {
            // access token refresh
            axios.post('http://127.0.0.1:8000/api/refresh', {}, {
              headers: {
                'Authorization': `Bearer ${store.state.access_token}`,
              }
            }).then(res => {
              console.log(res.data.access_token)
              store.commit('SET_ACCESS_TOKEN', res.data.access_token)

              error.config.headers.Authorization = `Bearer ${res.data.access_token}`;
              return axios.request(error.config);
            });
          }
         }
          return Promise.reject(error)
      });

export default defineComponent({
  name: 'App',
})
</script>
