/********************************************************************************** 
 * Face detection framework built on top of the face-api framework
 * @author: Dory A.Azar
 * @dependencies: face-api.js
 **********************************************************************************/

var FaceDetector = class FaceDetector {
    
    
    constructor(media) {
        
        // load the faceapi models
        this.load()
        .then((loaded) => { 
                // when loaded start the stream
                this.media = document.getElementById(media) || null; 
                if (this.media && this.media.tagName.toLowerCase() == 'video') {
                    this.startStream();
                }
        })
        .catch((error) => { console.log(error); }); 
    };
    
    /* 
    * Promise that loads all the models
    */
    async load() {
        // neural network models url
        const MODEL_URL = env.app_root + 'resources/models';
        
        try {
            var loaded = await Promise.all([
                // face detection algorithms
                faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL), // SsdMobilenetv1Options

                //faceapi.nets.mtcnn.loadFromUri(MODEL_URL), // MtcnnOptions
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL), // TinyFaceDetectorOptions

                // Models for landmarks, age/gender, recognition and expressions
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.ageGenderNet.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
                faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
            ]);
            
        } catch(error) {
            return Promise.reject(new Error(error));

        }

    };
    
    /* 
    * Method that starts streaming from computer cam 
    */
    async startStream() {

        // Stream from media device returns a promise. Compatible on Safari, Chrome and Firefox
        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
                }
        )
        .then(stream => {
            this.media.srcObject = stream;
            this.media.play();
        })
        .catch(error => {
            return Promise.reject(new Error(error));
            
        });

    };
    
    
    /* 
     * Promise that loads the app settings
     * @awaits for recognition models to be loaded for one of the apps
     */
    async loadApp(settings = null) {

        // display welcome message
        this.display("Hi! Let's show you how faces can be powerful", 'status');

        const app = settings? settings : {
                name: 'Detect',
                method: this.draw, // this.recognize
                //algorithm: faceapi.SsdMobilenetv1Options,
                options: {
                    welcome: "Show yourselves and we will detect your faces",
                    detection: true,
                    //age: true,
                    //gender: true,
                    //expression: true,
                    ////puppeteer: true,
                    //landmarks: true,
                    //recognition: true
                }
        };
        
        // load recognition models for use in the "Where is" app
        app.options.recognitionModel = settings && settings.models && app.options.recognition? await this.loadRecognition(settings.models) : null;

        var button = document.createElement('button');
        button.innerHTML = app.name;
        button.id = 'app' + app.id;
        button.addEventListener('click', this.detectFaces(app, this));
        document.getElementById('apps').appendChild(button);

    };
    
    /* 
     * Method that detects faces throughout the life cycle of the video stream
     * @app: object definition of the app and its options
     *
     */
     detectFaces(app, facedetector) {
        return function() {

            // Apply algorithm from app settings
            const algorithm = app.algorithm || faceapi.TinyFaceDetectorOptions;
            

            // Get the canvas ready with the app options
            const canvas = facedetector.prepareCanva(app.options);


            // Match the canva size with the video
            const displaySize = {
                width: facedetector.media.width,
                height: facedetector.media.height
            };
            faceapi.matchDimensions(canvas, displaySize);

            // Run the face detection algorithm every 100ms 
            facedetector.runningEvent = setInterval(async () => {
                
                

                // Detect all the faces present in the stream with Landmarks, Age and Gender, and Facial Expressions
                const detections = await faceapi.detectAllFaces(facedetector.media, new algorithm()).withFaceLandmarks().withFaceDescriptors().withAgeAndGender().withFaceExpressions();

                // resize the detections to fit in the canva
                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                
                // expose the detections to the app
                facedetector.app = app;
                facedetector.app.detections = resizedDetections;
                facedetector.app.canvas = canvas;
                
                // call the defined app method
                app.method(facedetector);
                
            }, 100); // end interval
        } // end callback 
    };
    
    
    /* 
     * Method that creates and prepares the canva for the application selected
     * @options: the application selected options are provided
     */
     prepareCanva(options) {

        // clear any running event
        if (this.runningEvent) {
            clearInterval(this.runningEvent);
        }

        // Show any welcome message specified in the app options
        if (options && options.welcome) {
            this.clearDisplay();
            this.display(options.welcome, 'status');
        }

        // set whether or not the video is to be visible
        if (options && options.puppeteer) {
            this.media.classList.replace('show', 'hide');
        } else {
            this.media.classList.replace('hide', 'show');
        }

        // create a canva from the video media and match its size to the video size
        // If the canva exists then remove it first
        const existingCanva = document.getElementById('detectfaces') || null;
        if (existingCanva) {
            existingCanva.parentNode.removeChild(existingCanva);
        }

        // Create the canva on top of the video and add it to the document
        const canvas = faceapi.createCanvasFromMedia(this.media);
        canvas.id = "detectfaces";
        document.getElementById('detector').append(canvas);

        return canvas;

    };
    
    /* 
     * Method that draws different renderings of the detections on the canvas
     *
     * @detections: face objects detected from the stream
     * @options: Optional. Object defining the different options applied to the rendering
     * @canvas: Optional. The canvas on which the drawings will be rendered
     *
     */
     draw(facedetector = this) {
         
         const detections = facedetector.app.detections || null;
         const options = facedetector.app.options || null;
         const canvas = facedetector.app.canvas || null;

        // Clear the canva so that it doesn't accumulate drawing at every detection
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

        // Draw the Detections for face detect
        if (options && options.detection) {
            faceapi.draw.drawDetections(canvas, detections);
        }


        // Draw face in line of different colors for even and odd indices
        if (options && (options.landmarks || options.puppeteer)) {

            // for each detection get the landmarks from the api and draw them as a full line
            detections.forEach((result, i) => {
                const landmark = result.landmarks;
                const drawPuppeteer = new faceapi.draw.DrawFaceLandmarks(landmark, {
                    drawLines: true,
                    drawPoints: false,
                    lineWidth: 2,
                    lineColor: i % 2 == 1 ? 'rgba(0,0,0,1)' : 'rgba(255,0,0, 1)',
                    pointSize: 2,
                    pointColor: 'rgba(0,0,0,1)'
                });
                drawPuppeteer.draw(canvas);
            });
        }


        // Custom Draw Gender, Age and Expressions
        if (options && (options.age || options.gender || options.expression)) {
            detections.forEach((result, i) => {

                // Get the most probable expression
                var sorted = result.expressions ? result.expressions.asSortedArray() : null;
                var expression = Array.isArray(sorted) ? sorted.filter(function(expr) {
                    return expr.probability > 0.9;
                }) : null;
                var expressionToDisplay = Array.isArray(expression) ? expression[0] ? expression[0].expression : 'neutral' : 'neutral';

                // Compose the array of results to display on the canva
                var resultToDisplay = [];
                if (options && options.expression) {
                    resultToDisplay.push("Expression: " + expressionToDisplay);
                }
                if (options && options.gender) {
                    resultToDisplay.push("Gender: " + result.gender);
                }
                if (options && options.age) {
                    resultToDisplay.push("Age: " + Math.round(result.age));
                }

                // Draw the values at the bottom of the canva
                const box = result.detection.box;
                const anchor = box.bottomLeft;
                const drawTextField = new faceapi.draw.DrawTextField(resultToDisplay, anchor);
                drawTextField.draw(canvas);

            });
        }
    }
    
    /* 
     * Utility method that resets the message display
     */
     clearDisplay() {

        // clear the ordered list
        var messageElement = document.getElementById('message');
        messageElement.innerHTML = '';

        // Add the default list item of id status
        var newStatus = document.createElement('LI');
        newStatus.id = 'status';
        messageElement.appendChild(newStatus);
    }
    
    
    /* 
     * Method that displays messages to the status area in the UI
     *
     * @message: the message to be displayed
     * @output: the HTML id where it should be displayed
     */
     display(message, output) {

        // If the output area where message needs to be placed does not exist then create it
        if (!document.getElementById(output)) {

            // The message bar is an unordered list and list items can be accumulated in it
            var messageElement = document.getElementById('message');
            var separator = document.createElement('LI');
            separator.innerHTML = "|";
            messageElement.appendChild(separator);
            var newStatus = document.createElement('LI');
            newStatus.id = output;
            messageElement.appendChild(newStatus);
        }

        // add message to the area
        var outputElement = document.getElementById(output) || null;
        outputElement.innerHTML = message;

    }
    
    /* 
     * Method that finds the best match for a face based on a model defined
     *
     * @detections: face objects detected from the stream
     * @options: Optional. Object defining the different options applied to the rendering
     * @canvas: Optional. The canvas on which the drawings will be rendered
     *
     */
     recognize(facedetector = this) {
         
         const detections = facedetector.app.detections || null;
         const options = facedetector.app.options || null;
         const canvas = facedetector.app.canvas || null;

        // Clear the canva so that it doesn't accumulate drawing at every detection
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
         
        // initialize the face matcher based on the recognition model loaded
        const labeledFaceDescriptors = options.recognitionModel;
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

        const results = detections.map(d => faceMatcher.findBestMatch(d.descriptor));
        
        // expose the recognitions
        facedetector.app.recognitions = results;

        // For each detection, draw a box. Draw the match in a different color
        results.forEach((result, i) => {
            const box = detections[i].detection.box;

            // Let the box highlight matches in a different box colors
            const drawBox = new faceapi.draw.DrawBox(box, {
                label: result.toString(),
                boxColor: !result.toString().includes("unknown") ? 'rgba(255,0,0, 1)' : 'rgb(192,192,192,1)'
            });
            drawBox.draw(canvas);
        })

    }
    
    /* 
     * Method that loads models for recognition
     *
     * @output: return promise with face descriptors of the models to be recognized
     */
     loadRecognition(models = null) {
         const labels = models.labels || [];
         const images = models.images || [];
         const sampleSize = models.sampleSize || 0;

        // return a promise that loads all the images and fetches their descriptors
        return Promise.all(

            // for each label get the descriptors based on the model image
            labels.map(async label => {

                const descriptors = [];
                var url;

                // iterate through all the model images in the folder (there are 6 right now and this number should be changed if more pics need to be added)
                for (let i = 1; i <= sampleSize; i++) {

                    url =  images.length > 0 ? images[i-1] : env.home + 'public/' + env.request.uri + `/recognition/${label}/${i}.png`;

                    // fetch the images from the model
                    const img = await faceapi.fetchImage(url);


                    // detect the single face from the image
                    const detections = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();

                    // push the descriptiors from each image detection onto the decriptor collections that will be needed
                    if (detections && detections.descriptor) {
                        descriptors.push(detections.descriptor);
                    };
                }

                return new faceapi.LabeledFaceDescriptors(label, descriptors);
            })
        );

    }
    
    
    /* 
     * Utility method that reads the detections to perform something on them
     * @callback: takes a callback method that is defined at method calling
     * @recognize: set to true to get the recognitions rather than the detections if the recognition engine is running
     * @fetchRate: define in ms the fetching rate of the detections. Default is 100ms
     *
     */
    detect(callback, recognize = false, fetchRate = 100) {
        setInterval(() => {
            if (recognize && this.app && this.app.recognitions) {
                    callback(this.app.recognitions);
                } else if(this.app && this.app.detections) {
                    callback(this.app.detections);
                }
        }, fetchRate)
    }
    
    
    /* 
     * Utility method that captures an image from the video and draws to a canva
     * @canvas: the canvas where the image will be drawn
     * @output: the data url of the blob image created
     *
     */
     fetchImage(canvas, media) {
        var context = canvas.getContext('2d');
        context.drawImage(media, 0, 0, media.offsetWidth, media.offsetHeight)
        return canvas.toDataURL();
    }
    
    
    
    
    /* 
     * Method for custom app that doesn't use Face Detections
     * @app: object definition of the app and its options
     * @facdetector: takes this object as a parameter as well
     *
     */
     custom(app, facedetector) {

        return function() {

            // Get the canvas ready with the app options
            const canvas = facedetector.prepareCanva(app.options);


            // Match the canva size with the video
            const displaySize = {
                width: facedetector.media.width,
                height: facedetector.media.height
            };
            faceapi.matchDimensions(canvas, displaySize);
            
            
            // initialize the app in the object
            facedetector.app = app;
            facedetector.app.canvas = canvas;


            // call the app features from here
            app.method(facedetector);
        }

    }
    
    
}