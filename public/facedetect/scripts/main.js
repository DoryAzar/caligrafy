var detector = new FaceDetector('detection');

var app = new Vue({
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
      
      print: function(detections) {
          console.log(detections);
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
      
      // Print detections on the console
      this.detector.detect(this.print);
      
      // Print recognitions when recognition engine is used
      //this.detect(this.print, true);
      
  }

});