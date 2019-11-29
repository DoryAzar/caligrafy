var detector = new FaceDetector('video');

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
      
      detect: function(callback, recognition = false) {
            setInterval(() => {
                if (recognition && this.detector.app && this.detector.app.recognitions) {
                    callback(this.detector.app.recognitions);
                } else if(this.detector.app && this.detector.app.detections) {
                    callback(this.detector.app.detections);
                }
            }, 100);
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
               welcome: "Hi there",
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
      this.detect(this.print);
      
      // Print recognitions when recognition engine is used
      this.detect(this.print, true);
      
  }

});