<template>
  <q-page class="q-pa-md bg-purple-10">
    <div style="max-width: 300px">
    <q-tabs
        v-model="tab"
        inline-label
        class="text-white"
        indicator-color="yellow"
      >
        <q-tab name="sign_in" label="SIGN IN" />
        <q-tab name="sign_up" label="SIGN UP" />
      </q-tabs>
      <br/>
      <div v-if="tab==='sign_in'">
      <div class="q-gutter-md" >
            <q-input type="text" clearable filled color="yellow" v-model="username" label="Username" /><div v-if="errors && errors.value && errors.value.username">{{ errors.value.username[0] }}</div>
            <q-input type="password" clearable filled color="yellow" v-model="password" label="Password" /><div v-if="errors && errors.value && errors.value.password">{{ errors.value.password[0] }}</div>
      </div>
      <br>
      <q-checkbox class="text-white" color="yellow" v-model="save" label="Keep Me Signed in" />
    <br><br>
    <q-btn class="text-black" color="yellow" label="SIGN IN" style="width: 300px" @click="signIn()"></q-btn>
    <br><br>
    <a class="text-white">Forget Password</a>
    </div>
    <div v-else>
        <!--<div v-if="errors && errors.value && errors.value.msg">{{ errors.value.msg }}</div>-->
        <div class="q-gutter-md" >
            <q-input type="text" clearable filled color="yellow" v-model="username_register" label="Username" /><div v-if="errors && errors.value && errors.value.username">{{ errors.value.username[0] }}</div>
            <q-input type="text" clearable filled color="yellow" v-model="email_register" label="Email" /><div v-if="errors && errors.value && errors.value.email">{{ errors.value.email[0] }}</div>
            <q-input type="password" clearable filled color="yellow" v-model="password_register" label="Password" /><div v-if="errors && errors.value && errors.value.password">{{ errors.value.password[0] }}</div>
            <q-input type="password" clearable filled color="yellow" v-model="password_confirm" label="Confirm Password" />
      </div>
    <br>
    <q-btn class="text-black" color="yellow" label="SIGN UP" style="width: 300px" @click="signUp()"></q-btn>
    </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import axios from 'axios';

export default defineComponent({
  name: 'IndexPage',

  components: {
    //
  },

  setup () {
    const store = useStore();

    let username = ref(null);
    let password = ref(null);

    let username_register = ref(null);
    let email_register = ref(null);
    let password_register = ref(null);
    let password_confirm = ref(null);

    let errors = ref(null);

    onMounted(() => { 
    })

    async function signUp() {
     let promise =  new Promise((resolve, reject) => {
      store.dispatch('register', { username: username_register.value, email: email_register.value, password: password_register.value, password_confirm: password_confirm.value })
        .then(function () {
          resolve('success');
        })
        .catch(function (error) {
          resolve(error);
        });

      });

        let result = await promise;
        if (result === 'success') {
          errors.value = computed(() => store.state.responseErrors);
        }
    }

    async function signIn() {
      let promise =  new Promise((resolve, reject) => {
      store.dispatch('login', { username: username.value, password: password.value })
        .then(function () {
          resolve('success');
        })
        .catch(function (error) {
          resolve(error);
        });

      });

        let result = await promise;
        if (result === 'success') {
          errors.value = computed(() => store.state.responseErrors);
        }
    }

    return {
        tab: ref('sign_in'),
        save: ref(false),
        signIn, signUp, username, password, username_register, email_register, password_register, password_confirm,
        errors,
        responseErrors: computed(() => store.state.responseErrors),
    }
  }
})
</script>
