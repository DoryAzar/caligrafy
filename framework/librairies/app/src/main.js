import { createApp } from 'vue'
import App from '@/App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import '@/assets/css/styles.css'

// Import Page Components
import HomePage from '@/components/pages/HomePage.vue'

// import Store State Management
import store from '@/common/store'


// define the routes
const routes = [
	{ path: '/', component: HomePage, name: 'home' },

];

// initialize the router
const router = createRouter ({
	history: createWebHistory(),
	routes: routes
});


// Instantiate the vue instance
const app  = createApp(App);

// configure app
app.config.productionTip = false;

// use router and store
app.use(router);
app.use(store);

//  mount app
app.mount('#app');



