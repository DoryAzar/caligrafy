var env = null;

function loadEnvironment(environment) {
    env = JSON.parse(environment);
    apiKey = "NHlVZEN6Ri8vR3hXNEVKSTJhTWlIbTE3bFpzcE1KNkRFVmwzTTloMi9FWGRzRzlRZHBMZ3oybGgrVFlsaXpHNjo6s9CfKQ/uyC8ZMVPcqFrB1w==";
}


function loadVue(parameters) {
    var body = document.getElementsByTagName('body').item(0);
    for(i = 0; i < parameters.services.length; i++) {
        var script = document.createElement('script');
        script.setAttribute('src', env.bot_service_root + parameters.services[i] + '.js');
        body.appendChild(script);        
    }
    
}
