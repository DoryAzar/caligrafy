<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type", content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width initial-scale=1 maximum-scale=1">
        <meta name='apple-mobile-web-app-capable' content='yes'>
        <meta name='apple-mobile-web-app-status-bar-style' content='black'>
        <title>Face Detect</title>

        <!-- Stylesheet and head scripts go here -->
        <link rel="stylesheet" href="<?php echo session('public').request()->fetch->uri.'/css/styles.css';?>">

    </head>


    <body>
        <!-- Beginning of the app -->
        <div id="app">
            <header>
                <h1>Face Detect</h1>
            </header>
            <section id="infobar">
                <ul id="message">
                    <li id="status" class="status"></li>
                </ul>
            </section>
            <section id="detector">
                <!-- media can be an image -->
                <!--<img id="detection" class="show" src="">-->
                <video id="detection" width="720" height="560" class="show" autoplay="autoplay" muted playsinline></video>
            </section>

            <section class="controls">
                <div id="apps"></div>
            </section>
        </div>


        
        <!-- Initialization scripts -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="<?php echo APP_SERVICE_ROOT.'app.js'; ?>"></script>
        <script src="<?php echo APP_SERVICE_ROOT.'face-api.min.js'; ?>"></script>
        <script src="<?php echo APP_SERVICE_ROOT.'detect.js'; ?>"></script>
        <script>loadEnvironment(`<?php echo $env; ?>`);</script>
        <script> 
            /* Loading the app client framework
             * Any environment variables to be passed on from the server can take place in this here
             */
            loadVue({
                scripts: ['main']
             });
        </script>
        
        <!-- Additional scripts go here -->
        <script src="<?php echo scripts('bootstrap_jquery'); ?>"></script>
        <script src="<?php echo scripts('bootstrap_script'); ?>"></script>
        
        <!--[if lt IE 9] -->
        <script src="<?php echo scripts('fallback_html5shiv'); ?>"></script>
        <script src="<?php echo scripts('fallback_respond'); ?>"></script>
        <!--<![endif]-->
    </body>

</html>