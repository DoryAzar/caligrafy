<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type", content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width initial-scale=1 maximum-scale=1">
        <meta name='apple-mobile-web-app-capable' content='yes'>
        <meta name='apple-mobile-web-app-status-bar-style' content='black'>
        <title>Caligrafy Bot</title>

        <!-- Stylesheet and head scripts go here -->
        <link rel="shortcut icon" href="<?php echo scripts('favicon'); ?>" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo scripts('bootstrap_css'); ?>" />
        
        <link rel="stylesheet" href="https://unpkg.com/botui/build/botui.min.css" />
        <link rel="stylesheet" href="https://unpkg.com/botui/build/botui-theme-default.css" />
        
    </head>
    
    <body>
        <!-- Beginning of the app -->
        <div id="app">

        </div>
        <div id="my-botui-app">
            <bot-ui></bot-ui>
        </div>
    
        <!-- Initialization scripts -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://unpkg.com/botui/build/botui.min.js"></script>
        <script src="<?php echo APP_SERVICE_ROOT.'app.js'; ?>"></script>
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