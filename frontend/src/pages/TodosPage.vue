<template>
  <q-page class="q-pa-md" style="max-width: 700px" v-if="token && token.value">
    <h5>Todos ({{ count }})</h5>
    <q-input outlined v-model="text" label="Enter todo here ...">
        <template v-slot:append>
          <q-btn color="primary" label="Submit" @click="createTask"></q-btn>
        </template>
    </q-input>

    <br />
    <q-list bordered class="rounded-borders">

  <ul v-if="tasks !== null">
    <li v-for="(task, index) in getPaginatedTasks" :key="index">
      <q-item clickable v-ripple>
        <q-item-section avatar>
              <q-checkbox v-model="task.check" @click="changeTaskStatus(task.id, task.check)" />
        </q-item-section>
        <q-item-section>
          <!--<q-item-label lines="1">{{task.description}}</q-item-label>-->
          <q-input v-model="task.description"></q-input>
        </q-item-section>
        <q-item-section side center>
          <div>
            <q-btn color="positive" icon="create" @click="editTask(task.id, task.description)"></q-btn>&nbsp;
            <q-btn color="negative" icon="delete" @click="deleteTask(task.id)"></q-btn>
          </div>
        </q-item-section>
      </q-item>
      <q-separator inset="item" />
    </li>
  </ul>
  <button @click="prevPage">
    Previous
  </button>
  <button @click="nextPage">
    Next
  </button>

    </q-list>
  </q-page>
    <q-page class="q-pa-md" style="max-width: 700px" v-else> 
      This hidden route
    </q-page>
</template>

<script>
import { defineComponent, ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import axios from 'axios';

export default defineComponent({
  name: 'TodosPage',

  components: {
    //
  },

  setup () {
    const store = useStore();

    let count = ref(0);
    let text = ref('');
    let tasks = ref(null);
    let token = ref(null);
    let pageNumber = 0;
    let size = 3;
    let offset = ref(null);
    let pageForPaginationSize = null;

    let start = ref(0);
    let end = ref(3);

    let errors = ref(null);


    const getPaginatedTasks = computed({
      get() {
        return (JSON.parse(JSON.stringify(tasks.value)));
        //return (JSON.parse(JSON.stringify(tasks.value))).slice(start.value, end.value);
      },
      set(val) {
        start.value = val * size
        end.value = start.value + size
      }
    })
    
      onMounted(() => { 
        offset.value = size;
        token.value = computed(() => store.state.access_token)
        getTasks(offset.value, start.value, end.value);
      })

      watch(offset, () => {
          getTasks(offset.value, start.value, end.value);
      });

      function prevPage() {
        if (pageNumber !== 0) {
          getPaginatedTasks.value = --pageNumber;
          offset.value -= size;
        }
      }

      function nextPage() {
        if (pageNumber !== pageForPaginationSize-1) {
          getPaginatedTasks.value = ++pageNumber;
          offset.value += size;
        }
      }

      async function getTasks(offset, start, end) {
      let promise =  new Promise((resolve, reject) => {
        //http://127.0.0.1:8000/api/getAllTasks?offset=offset.value
        axios.post('http://127.0.0.1:8000/api/getAllTasks', { start: start+1, end: end })
        .then(function (res) {
          count.value = res.data.count;
          store.commit('SET_TASKS', res.data.tasks);
          if (store.state.tasks !== {}) {
            resolve('success');
          }
        })
        .catch(function (err) {
          /*if (err && err.response && err.response.data && err.response.data.message) {
          resolve(err.response.data.message);
          }*/
        });

      });

        let result = await promise;
        if (result === 'success') {
            tasks.value = store.state.tasks
            if (tasks.value !== null) {
              pageForPaginationSize = Math.ceil(count.value/size);
            }
        }
        /*if (result === 'Your email address is not verified.') {
          errors.value = result;
          console.log(errors.value)
        } else errors.value = null;*/
      }

      function createTask() {
        axios.post('http://127.0.0.1:8000/api/createTask', { description: text.value, check: false })
            .then(res => {
                store.commit('SET_TASKS', res.data.tasks);
                tasks.value = store.state.tasks
                text.value = '';
                getTasks()
            }).catch(err => {
              console.log(err)
        })
      }

      function deleteTask(id) {
        axios.post('http://127.0.0.1:8000/api/deleteTask', { id: id, })
            .then(res => {
                getTasks()
            }).catch(err => {
              console.log(err)
        })
      }

      function editTask(id, description) {
        axios.post('http://127.0.0.1:8000/api/editTask', { id: id, description: description })
            .then(res => {
                getTasks()
            }).catch(err => {
              console.log(err)
        })
      }

      function changeTaskStatus(id, status) {
        axios.post('http://127.0.0.1:8000/api/changeStatusTask', { id: id, check: status })
            .then(res => {
                getTasks()
            }).catch(err => {
              console.log(err)
        })
      }

    return {
      count, tasks,
      text, token,
      createTask, deleteTask, editTask, changeTaskStatus,
      getPaginatedTasks, prevPage, nextPage,

      currentUser: computed(() => store.state.currentUser),
      errors, start, end, offset
    }
  }
})
</script>

<style>
ul{
  padding: 4px 4px;
  border: 1px solid black;
  
}
li{
  list-style-type:none;
  padding:4px 4px;
}
li:hover{
  background-color:#eee;
}
</style>