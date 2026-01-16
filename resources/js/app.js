import './bootstrap';
import { createApp } from 'vue'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import ItemList from './components/ItemList.vue'
import 'bootstrap-icons/font/bootstrap-icons.css'
import LoginComponent from './components/LoginComponent.vue';
import '../css/app.css';

const token = localStorage.getItem('api_token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const app = createApp({});
app.component('login', LoginComponent);
app.component('items', ItemList);
app.mount('#app');

// const app = createApp(ItemList)
// app.config.globalProperties.$axios = axios
// app.mount('#app')