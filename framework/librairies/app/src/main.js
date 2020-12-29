import Vue from 'vue'
import App from '@/App.vue'
import VueRouter from 'vue-router'
import '@/assets/css/styles.css'

// Import Page Components
import HomePage from '@/components/pages/HomePage.vue'

// import Store State Management
import store from '@/common/store'

Vue.use(VueRouter);

Vue.config.productionTip = false

// define the routes
const routes = [
	{ path: '/', component: HomePage, name: 'home' },

];

// initialize the router
const router = new VueRouter({
	routes: routes,
	mode: 'history' // avoiding anchoring
});


// Instantiate the vue instance
new Vue({
	store: store, 
	router: router,
	render: h => h(App),
}).$mount('#app')


