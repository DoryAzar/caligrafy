import { createApp } from 'vue'
import App from '@/App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import '@/assets/css/styles.css'

// Import Page Components
import HomePage from '@/components/pages/HomePage.vue'

// import Store State Management
import store from '@/common/store'

// Integrate axios for http requests
let axios = require('axios');


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

// integrate http request engine
app.config.globalProperties.http = axios;
app.config.globalProperties.axios = axios; /* for backward compatibility also can use axios */

// app api configuration
app.config.globalProperties.config = {
	apiKey: process.env.VUE_APP_API_KEY,
	apiRoute: '' /* specify api route here or in env file */
};
app.config.globalProperties.apiConfig = {
	
	async: true,
	crossDomain: true,
	headers: {
		"Authorization": "Bearer " + app.config.globalProperties.config.apiKey,
		'Content-Type': 'application/json',
		'Set-Cookie': 'widget_session=caligrafy_app; SameSite=None; Secure'
	}
	
};

// use router and store
app.use(router);
app.use(store);

//  mount app
app.mount('#app');



