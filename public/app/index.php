<html>
    <head>
        <!-- production version of VueJS with Axios, optimized for size and speed -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="<?php echo APP_SERVICE_ROOT.'app.js'; ?>"></script>
        <script>loadEnvironment(`<?php echo $env; ?>`);</script>

        <!-- Additional scripts go here -->
        <link rel="shortcut icon" href="<?php echo scripts('favicon'); ?>" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="<?php echo scripts('css'); ?>" />
        <link rel="stylesheet" href="<?php echo scripts('bootstrap_css'); ?>" />
        
    </head>
    
    <body>
        <!-- Beginning of the app -->
        <div id="app">
            {{ response }}           
        </div>
        
        <!-- Load scripts -->
        <script> 
            /* Loading the app client framework
             * Any environment variables to be passed on from the server can take place in this here
             */
            loadVue({
                services: ['main'],
             });
        </script>
        
        <!-- Additional scripts go here -->
        <script src="<?php echo scripts('bootstrap_jquery'); ?>"></script>
        <script src="<?php echo scripts('bootstrap_script'); ?>"></script>
        <script src="<?php echo scripts('script'); ?>"></script>
        
        <!--[if lt IE 9] -->
        <script src="<?php scripts('fallback_html5shiv'); ?>"></script>
        <script src="<?php echo scripts('fallback_respond'); ?>"></script>
        <!--<![endif]-->
    </body>
</html>