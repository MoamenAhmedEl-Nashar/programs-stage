import Vue from 'vue'
import App from './App.vue'
import store from './store'
import vuetify from './plugins/vuetify';
import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Clipboard from 'v-clipboard'
 
Vue.use(Clipboard)
Vue.use(VueRouter)
Vue.use(VueAxios, axios)


Vue.config.productionTip = false

import Programs from "./components/Programs"
import ProgramsList from "./components/ProgramsList"
import AddProgram from "./components/AddProgram"
import EditPrograms from "./components/EditPrograms"
import Login from "./components/Login"

const routes = [
  { path: '/Programs', component: Programs },
  { path: '/', component: ProgramsList },
  { path: '/AddProgram', component: AddProgram },
  { path: '/EditPrograms', component: EditPrograms },
  { path: '/Login', component: Login },
];

const router = new VueRouter({ routes });

new Vue({
  store,
  vuetify,
  router,
  render: h => h(App)
}).$mount('#app')
