<html>
    <head>
        <!-- production version of VueJS with Axios, optimized for size and speed -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="<?php echo APP_SERVICE_ROOT.'loadFramework.js'; ?>"></script>
    </head>
    
    <body>
        <div id="app">
          {{ response }}
        </div>
        <script>loadServices(['main'])</script>
    </body>
</html>