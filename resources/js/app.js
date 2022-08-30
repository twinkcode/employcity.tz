import './bootstrap';

import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"

import { createApp } from 'vue';
import store from '@/store';
import App from './Page/App.vue'
const app = createApp(App)
app.use(store)

app.mount('#app');
