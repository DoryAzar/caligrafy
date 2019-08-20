function loadServices(scripts) {
    var body = document.getElementsByTagName('body').item(0);
    var i = 0;
    for(i=0; i < scripts.length; i++) {
        var script = document.createElement('script');
        script.setAttribute('src', 'http://localhost/caligrafy/public/app/services/' + scripts[i] + '.js');
        body.appendChild(script);
    }
}
