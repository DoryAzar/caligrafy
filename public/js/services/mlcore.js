const MlCore = class MlCore {
	
	constructor(settings) 
	{
	
		// load properties
		let defaultSettings = { media: 'video', canvas: 'canvas', brain: { type: 'neuralnetwork', options: { inputs: 34, outputs: 2, debug:true, task: 'classification'}}};
		this.settings = { ...defaultSettings, ...settings};
		
		// load DOM properties
		this.media = document.getElementById(this.settings.media) || null; 
		this.canvas = document.getElementById(this.settings.canvas) || null;
		this.ctx = this.canvas? this.canvas.getContext('2d') : null;

		// Load brain type
		this.featureextractor = this.settings.brain.type == 'featureextractor'? ml5.featureExtractor('MobileNet')?? null : null; 

		// Load filter properties
		this.segmentationImage = document.getElementById('segmentationImage') || null;

		if (this.media && this.media.tagName.toLowerCase() == 'video' && !this.media.src) {
			this.startStream();
   		} 
		else {
			this.brain = ml5.neuralNetwork(this.settings.brain.options)?? null;
			console.log('Caligrafy MLCore Neural Networks launched');
		}
		
	}

    /* 
    * Method that starts streaming from computer cam 
    */
    async startStream() 
	{

        // Stream from media device returns a promise. Compatible on Safari, Chrome and Firefox
        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
                }
        )
        .then(stream => {
            this.media.srcObject = stream;
            this.media.play();
			this.brain = this.settings.brain.type == 'featureextractor'? this.featureextractor.classification(this.media)?? null : ml5.neuralNetwork(this.settings.brain.options)?? null;
        })
        .catch(error => {
            return Promise.reject(new Error(error));
            
        });

    }
	
	
	/* 
    * Promise that poseNet model and starts detection
	* @callback: callback method that we called through out detections
	* @...arg: any additional arguments passed will be passed to the callback
    */
    async detect(callback, ...args) 
	{
		
		// Create a new poseNet method with a single detection
		this.poseNet = await ml5.poseNet(this.media, this.modelReady);
		this.uNet = await ml5.uNet('face');
		
		
		// This sets up an event that fills the global variable "poses"
		// with an array every time new poses are detected
		this.poseNet.on('pose', (results) => {
			this.poses = results;
			
			if (this.settings.filter) {
				// initial segmentation
  				this.uNet.segment(this.media, (error, result) => this.filter(error, result));
			}

			this.prepareCanva(this);
			
			callback(this, ...args);
		});
		


    }
	
	
	/* 
    * Hook when model is loaded
    */
	modelReady() {
		console.log('model loaded');
	}
	
	/* 
    * function that draws the desired poses 
	* @mlpose: MlBody object is passed to the method
	* @posesToDraw: array of poses that the user wants to draw
    */
	drawFeature(mlpose = this, posesToDraw) 
	{
	
		mlpose.ctx.fillStyle = 'rgb(255, 0, 0)';

		let valid = Array.isArray(posesToDraw) && posesToDraw.length > 0;

		// For one pose only (use a for loop for multiple poses!)
		for (let i = 0; valid && i < mlpose.poses.length; i++) {

			let pose = mlpose.poses[i].pose;

			posesToDraw.forEach(poseToDraw => {
				let feature = pose[poseToDraw]?? null;
				if (feature) {
					mlpose.ctx.beginPath();
					mlpose.ctx.arc(feature.x, feature.y, 7, 0, 2 * Math.PI);
					mlpose.ctx.fill();
				}
			});
		}
	}
	

	/* 
    * A function to draw ellipses over the detected keypoints
	* @mlpose: MlBody object is passed to the method
    */
	drawKeypoints(mlpose = this)  
	{
	
	  mlpose.ctx.fillStyle = 'rgb(255, 0, 0)';
		
	  // Loop through all the poses detected
	  for (let i = 0; i < mlpose.poses.length; i++) {
		  
		// For each pose detected, loop through all the keypoints
		let pose = mlpose.poses[i].pose;
		for (let j = 0; j < pose.keypoints.length; j++) {
			
		  // A keypoint is an object describing a body part (like rightArm or leftShoulder)
		  let keypoint = pose.keypoints[j];

		  // Only draw an ellipse is the pose probability is bigger than 0.2
		  if (keypoint.score > 0.2) {
			mlpose.ctx.beginPath();
			mlpose.ctx.arc(keypoint.position.x, keypoint.position.y, 7, 0, 2 * Math.PI);
			mlpose.ctx.fill();
		  }
		}
	  }
	}

	/* 
    * A function to draw the skeletons
	* @mlpose: MlBody object is passed to the method
    */	
	drawSkeleton(mlpose = this) 
	{
	  
	  mlpose.ctx.strokeStyle = 'rgb(255, 0, 0)';
	  mlpose.ctx.lineWidth = 2;
		
	  // Loop through all the skeletons detected
	  for (let i = 0; i < mlpose.poses.length; i++) {
		let skeleton = mlpose.poses[i].skeleton;
		// For every skeleton, loop through all body connections
		for (let j = 0; j < skeleton.length; j++) {
		  let partA = skeleton[j][0];
		  let partB = skeleton[j][1];
		  mlpose.ctx.beginPath();
		  mlpose.ctx.moveTo(partA.position.x, partA.position.y)
		  mlpose.ctx.lineTo(partB.position.x, partB.position.y);
		  mlpose.ctx.stroke();
		}
	  }
	}
	
	/* 
    * Preparing the canva for use
	* @mlpose: MlBody object is passed to the method
    */	
	prepareCanva(mlpose = this) 
	{
		mlpose.ctx.clearRect(0, 0, mlpose.canvas.width, mlpose.canvas.height);
		
		let im = mlpose.media;
		
		// If filtering mode is on
		if(this.settings.filter && mlpose.segmentationImage.raw){
			// UNET image is 128x128
			im = mlpose.imageDataToCanvas(mlpose.segmentationImage.raw.backgroundMask, 128, 128)
			mlpose.media.setAttribute('class', 'hide');
		} else {
			if (this.settings.hideVideo) { 
				im = mlpose.canvas;
				mlpose.media.setAttribute('class', 'hide') 
			};
		}
		
		// draw the resulting image
		mlpose.ctx.drawImage(im, 0, 0, mlpose.canvas.width, mlpose.canvas.height);

	}
	
	/* 
    * Clearing the canva
	* @canvas: canvas object element
    */	
	clearCanva(canvas) 
	{
		canvas.ctx.clearRect(0, 0, canvas.width, canvas.height);
	}
	
	
	/* 
    * Filtering the video to extract only the person/body
	* @error: error promised triggered
	* @result: success promise triggered 
    */	
	filter(error, result) 
	{

		// if there's an error return it
		if (error) {
		console.error(error);
		return;
		}
		// console.log(result)
		// set the result to the global segmentation variable
		this.segmentationImage = result;

	}
	
	
	/* 
    * Turn the segmented imag to a canva image
	* @imageData: image data to transform to canvas
	* @w: width of canvas to put the image on
	* @h: height of the canvas to put the image on
    */	
	imageDataToCanvas(imageData, w, h) 
	{
		// console.log(raws, x, y)
		const arr = Array.from(imageData)
		const canvas = document.createElement('canvas'); // Consider using offScreenCanvas when it is ready?
		const ctx = canvas.getContext('2d');

		canvas.width = w;
		canvas.height = h;

		const imgData = ctx.createImageData(w, h);
		// console.log(imgData)
		const { data } = imgData;

		for (let i = 0; i < w * h * 4; i += 1 ) data[i] = arr[i];
		ctx.putImageData(imgData, 0, 0);

		return ctx.canvas;
	};
	
	/* 
    * Hands off to p5
	* @mlpose: MlBody object is passed to the method
    */	
	toP5(mlpose = this) 
	{
		let myCanvas = createCanvas(mlpose.canvas.width, mlpose.canvas.height);
		myCanvas.parent('detector');
		myCanvas.id(mlpose.settings.canvas);
		myCanvas.class('show');
		mlpose.canvas.remove();

		mlpose.canvas = myCanvas;
		mlpose.ctx = myCanvas.canvas.getContext('2d');
		return mlpose.canvas;
	}
	
	
	/* 
    * Trains the integrated model
	* @options: defines the options of the neural networks: inputs, outputs, debug mode...
	* @mlpose: MlBody object is passed to the method
    */	
	train(mlpose = this, options = {}) 
	{
		if (mlpose.settings.brain.type == 'featureextractor'){
			mlpose.brain.train((lossValue) => { 
				if (!lossValue) mlpose.finishedTraining(mlpose); 
			});
		} else {
			mlpose.brain.normalizeData();
			mlpose.brain.train(options, () => mlpose.finishedTraining(mlpose));
		}
	}
	
	/*
	 * Method called when training is completed
	 * @mlpose: MlBody object is passed to the method
	 */
	finishedTraining(mlpose = this) {
		mlpose.brain.results = 'Training Finishing...'
		mlpose.classify(mlpose);
	}
	

	
	/* 
    * Classifies detections. Also called after training to start classification automatically upon training completion
	* @mlpose: MlBody object is passed to the method
    */	
	classify(mlpose = this) 
	{
		if (mlpose.settings.brain.type != 'featureextractor' && mlpose.poses.length > 0) {
			let inputs = mlpose.getInputs(mlpose);
			mlpose.brain.classify(inputs, (error, results) => mlpose.getResults(error, results, mlpose));
		} else if (mlpose.settings.brain.type == 'featureextractor') {
			mlpose.brain.classify((error, results) => mlpose.getResults(error, results, mlpose));
		}
	}
	
	/*
	 * Method triggered upon classification results 
	 * @error: when the promise fails
	 * @results: when the promise succeeds, it returns the results
	 * @mlpose: MlBody object is passed to the method
	 */
	getResults(error, results, mlpose = this) 
	{
		mlpose.brain.results = `${results[0].label} (${floor(results[0].confidence * 100)})%`;
		mlpose.classify(mlpose);
		
	}
	
	
	/*
	 * Method that gets the inputs specifically for the body pose
	 * @mlpose: MlBody object is passed to the method
	 */
	getInputs(mlpose = this)
	{
		let inputs = [];
		if (mlpose.hasBrain() && mlpose.poses.length > 0) {
			let keypoints = mlpose.poses[0].pose.keypoints;
			keypoints.forEach(keypoint => {
				inputs.push(keypoint.position.x);
				inputs.push(keypoint.position.y);
			});
		}
		return inputs;
	}
	
	
	
	/* 
    * Add sample data to the model
	* @mlpose: MlBody object is passed to the method
	* @labelInputs: adds the labels to the sampled inputs provided to the brain
    */	
	addData(mlpose = this, labelInputs) 
	{
		if (mlpose.poses && mlpose.poses.length > 0) {
			let inputs = mlpose.getInputs(mlpose);
    		let target = labelInputs;
    		mlpose.brain.addData(inputs, [target]);
		}
	}
	
	/* 
    * Add sample image data to the model
	* @mlpose: MlBody object is passed to the method
	* @labelInputs: adds the labels to the sampled inputs provided to the brain
    */	
	addImage(mlpose = this, labelInputs)
	{
		if (mlpose.hasBrain()) {
			mlpose.brain.addImage(labelInputs);
		}
	}
	
	
	
	
	/* 
    * Checks if the MlBody object has a Neural Network brain already defined
    */	
	hasBrain()
	{
		return this.brain != null;
	}
	
	/* 
	 * Save the entire trained model
	 * @outputName: the name of the output. default is 'model'
	 * @callback: callback method to be called when download completed
	 */
	save(outputName, callback, mlpose = this)
	{
		if (mlpose.settings.brain.type != 'featureextractor') {
			mlpose.brain.save(outputName, callback);
		} else {
			mlpose.brain.save(callback, outputName);
		}
	}
	
	/* 
	 * Save the entire trained model
	 * @fileOrPath: the name of the file or path to JSON. if the name 'model' is given to the JSON, then 'model-meta.json', 'model-weights.bin' will be fetched as well
	 * @callback: callback method to be called when download completed
	 */	
	load(fileOrpath, callback, mlpose= this)
	{
		if (mlpose.hasBrain()) {
			mlpose.brain.load(fileOrpath, callback);
		}
	}
	
}