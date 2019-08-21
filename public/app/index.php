<html>
    <head>
        <!-- production version of VueJS with Axios, optimized for size and speed -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="<?php echo APP_SERVICE_ROOT.'app.js'; ?>"></script>
    </head>
    
    <body>
        <div id="app">
          {{ response }}
        </div>
        <script>
            // Loading the app client framework
            load({
                services: ['main'],
                env: { 
                        app_root: '<?php echo APP_CLIENT; ?>',
                        service_root: '<?php echo APP_SERVICE_ROOT; ?>'
                }
             });
        </script>
    </body>
</html>