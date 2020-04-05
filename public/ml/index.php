<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type", content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width initial-scale=1 maximum-scale=1">
        <meta name='apple-mobile-web-app-capable' content='yes'>
        <meta name='apple-mobile-web-app-status-bar-style' content='black'>
        <title>Machine Learning</title>
		
		<!-- Stylesheet and head scripts go here -->
        <link rel="shortcut icon" href="<?php echo scripts('favicon'); ?>" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo session('public').request()->uriComponents[0].'/css/styles.css';?>">
		
		<!-- Initialization scripts -->
		
		<!-- p5.js -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.9.0/p5.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/0.9.0/addons/p5.dom.min.js"></script>
		
		<!-- ml5 -->
		<script src="https://unpkg.com/ml5@0.5.0/dist/ml5.min.js"></script>
        
    </head>
    
    <body>
        <!-- Beginning of the app -->
        <div id="app">
            <header>
                <h1>Machine Learning</h1>
            </header>
			<section id="infobar"></section>
            <section id="detector">
                
				<canvas id='canvas' width="720" height="560" class="show"></canvas>
				
				<!-- media can be a video -->
                <!--<video id="video"  src="path/to/video" type="video/mp4" width="720" height="560" class="show" autoplay="autoplay" muted playsinline controls></video>-->
				
                <!-- media can be a webcam -->
                <video id='video' width="720" height="560" class="show" autoplay="autoplay" muted playsinline></video>
				
                <img src="" style="display:none" width="720" height="560" alt="segmentation image" id="segmentationImage"         />
            </section>
            <section class="controls">
                <div id="apps"></div>
            </section>
        </div>
		
		
		
		<!-- Scripts -->
		<script src="<?php echo APP_SERVICE_ROOT.'app.js'; ?>"></script>
		<script>loadEnvironment(`<?php echo $env; ?>`);</script>
		<script src="<?php echo APP_SERVICE_ROOT.'mlcore.js'; ?>"></script>
		<script> 
			/* Loading the app client framework
			 * Any environment variables to be passed on from the server can take place in this here
			 */
			loadVue({
				scripts: [
					'main'
				]
			 });
		</script>

		<!-- Additional scripts go here -->
		<script src="<?php echo scripts('bootstrap_jquery'); ?>"></script>
		<script src="<?php echo scripts('bootstrap_script'); ?>"></script>
		<script src="<?php echo scripts('script'); ?>"></script>

		<!--[if lt IE 9] -->
		<script src="<?php echo scripts('fallback_html5shiv'); ?>"></script>
		<script src="<?php echo scripts('fallback_respond'); ?>"></script>
		<!--<![endif]-->
    </body>
</html>