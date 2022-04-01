var detector = new FaceDetector('detection');

const app = Vue.createApp({
  el: '#app',
  data () {
    return {
        detector: detector,
        env: env

    }
  },
  /* Method Definition  */
  methods: {
      
      // Load App method
      loadApp: function(app) {
          this.detector.loadApp(app);
      },
      
      continuousMethod: function(facedetector) {
          console.log(facedetector.app.detections);
      },
      
      callbackMethod: function(facedetector) {
         
        //<---- do whatever you want here
          console.log('Example of what can be done');
        
        /* use any of the FaceDetect methods
         * facedetector.loadApp(app): load another app
         * (facedetector.detectFaces(app, facedetector))(): self invoking function to start face detection
         * facedetector.detect(callback, recognize = false, fetchRate = 100): starts a parallel stream that captures any detections or recognitions when available
         * facedetector.prepareCanva(options = null): returns a new canvas on top of the media source
         * facedetector.draw(facedetector): draws the detections on the canvas
         * facedetector.loadRecognition({ labels: [], images: [], sampleSize: 100}): load models to recognize by the recognition engine
         * facedetector.recognize(facedetector): runs the recognition engine and draws on canvas. Must make sure that detections is started before
         * facedetector.fetchImage(canvas, media): takes a canvas capture of the media and returns a blob data image (data url)
         * facedetector.display(message, output): displays a message in the infobar and gives it an ID as specified by the 'output' input
         * facedetector.clearDisplay(): clears the infobar display
         */
      }
      
  },
  /* upon object load, the following will be executed */
  mounted () {
      
      // Load general detection
      this.loadApp();
      
      // Load full detection
      this.loadApp({
          name: 'Full Detection',
          method: this.detector.draw,
          options: {
               welcome: "Detect faces, genders, ages and expressions",
               detection: true,
               landmarks: true,
               gender: true,
               expression: true,
               age: true
          }
      });
      
      // Load Model Recognition 
      this.loadApp({
              name: 'Recognize',
              method: this.detector.recognize,
              models: {
                  labels: ['Flash'],
                  sampleSize: 6
              },
              options: {
                welcome: "Flash will be recognized if he is present",
                recognition: true
             },
             algorithm: faceapi.SsdMobilenetv1Options        
      });
      
      // Load puppeteer mode
      this.loadApp({
          name: "Puppeteer",
          method: this.detector.draw,
          options: {
                welcome: "Line Drawing",
                detection: false,
                puppeteer: true 
          }

      });
      
      this.loadApp({
          name: "Custom continuous",
          method: this.continuousMethod,
          custom: false, // set to false if you want the method to be applied continuously at every interval of detection
          options: {
              welcome: "Open the console to see how it is continuously being called at every detection",
              detection: true
          }
          
      });
      
      
    this.loadApp({
          name: "Custom callback",
          method: this.callbackMethod,
          custom: true, // set to true if you want the method to do something else before calling in FaceDetect features
          options: {
              welcome: "Open the console to see how it is executing its content and waiting for more to be done",
              detection: true
          }
          
      });
      
  }

});

// mount the app
app.mount('#app');