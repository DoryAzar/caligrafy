var env = null;

function loadEnvironment(environment) {
    env = JSON.parse(environment);
    apiKey = env.apiKey || null;
}


function loadVue(parameters) {
    var body = document.getElementsByTagName('body').item(0);
    for(i = 0; i < parameters.scripts.length; i++) {
        var script = document.createElement('script');
        script.setAttribute('src', env.home + 'public/' + env.request.uri + '/scripts/' + parameters.scripts[i] + '.js');
        body.appendChild(script);        
    }
    
}
