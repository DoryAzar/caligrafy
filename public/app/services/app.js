var env = null;

function load(parameters) {
    env = JSON.parse(parameters.env);
    var body = document.getElementsByTagName('body').item(0);
    for(i = 0; i < parameters.services.length; i++) {
        var script = document.createElement('script');
        script.setAttribute('src', env.service_root + parameters.services[i] + '.js');
        body.appendChild(script);        
    }
    
}
