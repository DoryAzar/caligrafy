var app = new Vue({
  el: '#app',
  data () {
    return {
        response: null,
        env: env

    }
  },
  /* Method Definition  */
  methods: {
      
      // example of axios get method with default parameters
      all: function(route) {
          axios.get(route)
              .then(response => (this.response = response.data))
              .catch(error => (console.log(error)));
      },
      // example of axios method with additional parameters such as headers
      allWithHeaders: function(route) {
            axios.get(route, {async: true, crossDomain: true, headers: {
                  'Access-Control-Allow-Origin': '*', 
                  'Accept': 'application/json', 
                  'Content-Type': 'application/json',
                  'Access-Control-Allow-Methods': 'GET, PUT, POST, DELETE, OPTIONS',
                  'Access-Control-Allow-Headers': '*'}}
                  )
              .then(response => (this.response = response.data))
      },
  },
  /* upon object load, the following will be executed */
  mounted () {
      this.allWithHeaders('/caligrafy');
  }

});