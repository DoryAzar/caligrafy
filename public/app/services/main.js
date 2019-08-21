var app = new Vue({
  el: '#app',
  data () {
    return {
        response: null

    }
  },
  /* Method Definition  */
  methods: {
      
      // example of axios get method with default parameters
      all: function() {
          axios.get('/caligrafy/')
              .then(response => (this.response = response.data))
              .catch(error => (console.log(error)));
      },
      // example of axios method with additional parameters such as headers
      allWithHeaders: function() {
            axios.get('/caligrafy', {async: true, crossDomain: true, headers: {
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
     this.allWithHeaders();
  }

});