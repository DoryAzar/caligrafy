var app = new Vue({
  el: '#app',
  data () {
    return {
        response: null

    }
  },
  /* example of asynchronous methods are defined here  */
  methods: {

      all: function() {
          axios.get('/caligrafy/')
              .then(response => (this.response = response.data))
              .catch(error => (console.log(error)));
      }
  },
  /* upon page load example showing other headers from axios */
  mounted () {
    axios.get('/caligrafy/', {async: true, crossDomain: true, headers: {
          'Access-Control-Allow-Origin': '*', 
          'Accept': 'application/json', 
          'Content-Type': 'application/json',
          'Access-Control-Allow-Methods': 'GET, PUT, POST, DELETE, OPTIONS',
          'Access-Control-Allow-Headers': '*'}}
          )
      .then(response => (this.response = response.data))
  }
});