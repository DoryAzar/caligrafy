/* Components Definition */

// Component Registration
//Vue.component('component-name', {
//
//	template: '#template',
//	props: [],
//	methods: {},
//	data() {
//		return {
//			// data definitions
//		}
//	}
//	
//});


Vue.createApp({
    el: '#app',
    data () {
      return {
          response: null,
          env: env,
          config: {
              async: true,
              crossDomain: true,
              headers: {
                  "Authorization": "Bearer " + apiKey,
                  'Content-Type': 'application/json',
                   'Set-Cookie': 'widget_session=caligrafy_app; SameSite=None; Secure'
                  }
          }
  
      }
    },
    /* Method Definition  */
    methods: {
        
        // API get call using axios
        all: function(route) {
            axios.get(route, this.config)
                .then(response => (this.response = response.data))
                .catch(error => (console.log(error)));
        }
        
    },
    /* upon object load, the following will be executed */
    mounted () {
        this.all(this.env.home);
    }
  
  }).mount('#app');