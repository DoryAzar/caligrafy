var botui = new BotUI('my-botui-app');
var app = new Vue({
  el: '#app',
  data() {
    return {
        response: null,
        env: env,
        botui: botui,
        botId: 'b605e1f8-38c5-4756-aa05-89e9ae6063e9',
        route: 'http://localhost/caligrafy/bot/',
        config: {
            async: true,
            crossDomain: true,
            headers: {
                "Authorization": "Bearer " + apiKey,
                'Content-Type': 'application/json'
                }
        }
    }
  },
  /* Method Definition  */
  methods: {
      
      addMessage: function(message, human = false, type = 'text') {
          this.botui.message.add({
              type: type,
              human: human,
              content: message
            });
      },
      
      addAction: function(action, options = []) {
          this.botui.action[action]({
              delay: 500,
              action: options
        }).then(function(res){
            var input = { "text": res.value};
            app.communicate(app.route, input);
          });
      },
      
      promptUser: function(message, action = 'text', options = []) {
          this.addMessage(message);
          this.addAction(action, options);
      },
      
      
    // Connect to the bot
      connect: function(route) {
            axios.post(route + this.botId, '', this.config)
            .then(response => {
                // if an error occurs then show a generic message
                if(response.data === true) {
                    console.log('Connected');
                } else {
                    console.log('Connection could not be established');
                }
            })
            .catch(error => (console.log(error)));          
      },
      
      // Communicate with the bot
      communicate: function(route, input) {
            axios.post(route + 'communicate', input, this.config)
            .then(response => {
                if (response.data && response.data['action_success'] === true && response.data.response) {
                    response.data.response.forEach((element) => {
                        switch(element['response_type']) {
                            case 'text':
                                app.promptUser(element.text);
                                break;
                            case 'option':
                                var options = [];
                                response.data.response.options.forEach(function(element) {
                                    options.push({ 'text': element.label, 'value': element.value.input.text});
                                });
                                app.promptUser(response.data.response.title,'button', options);
                                break;
                            default:
                                console.log(response.data);
                        }                        
                    });
                } else {
                    console.log('Conversation ended');
                }

            })
            .catch(error => (console.log(error)));          
      }   

  },
  /* upon object load, the following will be executed */
  mounted () {
      this.connect(this.route);
      this.promptUser('Hi there! How can I help you?');
  }

});