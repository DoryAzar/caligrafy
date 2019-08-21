function load(parameters) {
    var body = document.getElementsByTagName('body').item(0);
    for(i = 0; i < parameters.services.length; i++) {
        var script = document.createElement('script');
        script.setAttribute('src', parameters.environment_variables.service_root + parameters.services[i] + '.js');
        body.appendChild(script);        
    }
    
}
