const MlBody = class MlBody {
	
	constructor(settings) {
	
		// load properties
		let defaultSettings = { media: 'video', canvas: 'canvas'};
		this.settings = { ...defaultSettings, ...settings};
		this.media = document.getElementById(this.settings.media) || null; 
		this.canvas = document.getElementById(this.settings.canvas) || null;
		this.segmentationImage = document.getElementById('segmentationImage') || null;

		if (this.media && this.canvas && this.media.tagName.toLowerCase() == 'video' && !this.media.src) {
			this.startStream();
   			this.ctx = this.canvas.getContext('2d');
  			
		} else {
			console.log('Media and canvas are needed for this to work')
		}
	}

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

    }
	
	
	/* 
    * Promise that poseNet model and starts detection
    */
    async detect(callback, ...args) {
		
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
    */
	drawFeature(mlpose, posesToDraw) {
	
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
    */
	drawKeypoints(mlpose)  {
	
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
    */	
	drawSkeleton(mlpose) {
	  
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
	
	// Preparing the canva for use
	prepareCanva(mlpose) {
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
	
	
	filter(error, result) {

		// if there's an error return it
		if (error) {
		console.error(error);
		return;
		}
		// console.log(result)
		// set the result to the global segmentation variable
		this.segmentationImage = result;

	}
	
	
	imageDataToCanvas(imageData, w, h) {
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
	
	
}