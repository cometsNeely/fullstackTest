<template>
  <q-layout view="lhh LpR lff">
    <q-header reveal class="bg-black">
      <q-toolbar>
          <q-toolbar-title>{{currentUser.username}}</q-toolbar-title>
          <q-btn v-if="currentUser && currentUser.username" color="primary" label="Logout" @click="logout" />
        </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default defineComponent({
  name: 'MainLayout',

  components: {
    //
  },

  setup () {
    const store = useStore();
    const router = useRouter()

        onMounted(() => { 
          //console.log(store.state)
        })

    function logout() {
      store.dispatch('logout')
      store.commit('GET_CURRENT_USER', {});
    }

    return {
      currentUser: computed(() => store.state.currentUser), 
      logout,
    }
  }
})
</script>
