var botui = new BotUI('my-botui-app');
var app = new Vue({
  el: '#app',
  data() {
    return {
        response: null,
        env: env,
        botui: botui,
        botId: env.botSkillId,
        route: env.home + "bot/",
        config: {
            async: true,
            crossDomain: true,
            headers: {
                "Authorization": "Bearer " + apiKey,
                'Content-Type': 'application/json',
                 'Set-Cookie': 'widget_session=caligrafy_bot; SameSite=None; Secure'
                }
        }
    }
  },
  /* Method Definition  */
  methods: {
      
      addMessage: function(message, human = false, type = 'text') {
          this.botui.message.add({
              autoHide: false,
              type: type,
              human: human,
              content: message
            });
      },
      
      addAction: function(action, options = [], context = null) {
          this.botui.action[action]({
              autoHide: false,
              delay: 500,
              action: options
        }).then(function(res){
            var input = { "text": res.value};
            if (context) {
                input.variables = context; 
            }
            app.communicate(app.route, JSON.stringify(input));
          });
      },

      addContext: function(context = null) {
        if (context)
            this.addAction('text',[], context);
      },
      
      promptUser: function(message, action = 'text', options = []) {
          this.addMessage(message);
          this.addAction(action, options);
      },
      
      
    // Connect to the bot
      connect: function(route) {
            axios.post(route + this.botId, [], this.config)
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
                                app.promptUser(element.text, 'text', []);
                                break;
                            case 'option':
                                var options = [];
                                element.options.forEach(function(element) {
                                    options.push({ 'text': element.label, 'value': element.value.input.text});
                                });
                                app.promptUser(element.title,'button', options);
                                break;
                            case 'image':
                                // assume it's an embed
                                var imageInput = element.source;
                                app.addMessage("<a target=_blank href='" + element.source +"'>" + element.title  + "</a>", false, 'html'); 
                                imageInput = "<embed src='" + element.source + "' style='width: 50vw; max-width: 600px; height: auto;'></embed>";
                                type='html';
                                app.addMessage(imageInput, false, type); 
                                app.addAction('text');
                                break;
                            case 'pause':
                                if (response.data.context && response.data.context.action) {
                                    var actionResponse = execute(response.data.context);
                                    if (actionResponse) {
                                        app.addMessage(actionResponse.text);
                                        app.addAction('text', [], actionResponse.variables);
                                    }
                                }
                                break;
                            default:
                                console.log(response.data);
                        }                        
                    });
                } else {
                    app.addMessage("Bye for now. You can close the chat");
                }

            })
            .catch(error => (console.log(error)));          
      }   

  },
  /* upon object load, the following will be executed */
  mounted () {
      this.connect(this.route);
      this.addMessage("Hi there!");
      this.addAction('text', [], { "bot_name": 'Cali' });
  }

});