/*
 * Instantiating the ml object with some settings
 * settings:
 * - media: provide the mediaId of the video element in the markup
 * - canvas: provide the canvasId of the canvas element in the markup
 * - filter: set to true if you want to extract the body image from the background
 * - hideVideo: hides the video
 */
var myMl = new MlBody({brain: { inputs: 34, outputs: 2, debug:true, task: 'classification'}});

let dataButton;
let dataLabel;
let trainButton;
let classificationP;
let listElement;
let listItem;
let message = 'waiting to train model';

myMl.detect((myMl) => {
	myMl.drawKeypoints(myMl);

});

function setup() {
	myMl.toP5(myMl);
	
	// Add status text
	listElement = createElement('ul');
	listElement.parent('infobar');
	listElement.id('status');
	classificationP = createElement('li', message);
	classificationP.id('message');
	classificationP.parent('status');
	
	
	// ml UI
	listElement = createElement('ul');
	listElement.parent('infobar');
	listElement.id('mlUI');
	
	// add select to mlUI
	dataLabel = createSelect();
	dataLabel.option('Crouch');
	dataLabel.option('Stand');
	listItem = createElement('li')
	listItem.child(dataLabel);
	listItem.parent('mlUI');
	
	// add add data button
	listItem = createElement('li');
	listItem.parent('mlUI');
	dataButton = createButton('add example');
	listItem.child(dataButton);
	dataButton.mousePressed(() => myMl.addData(myMl, dataLabel.value()));
	
	// add train button
	trainButton = createButton('train');
	trainButton.parent('apps');
	trainButton.mousePressed(() => myMl.train({ epochs: 25}, myMl));
	
}


function draw() {
	
	classificationP.html(myMl.brain.results?? message);
}

/*
 * Detect function allows detection of body poses
 * @callback: mlpose offers several callback methods
 * 		- drawFeature(MlPose, Array features to draw)
 *		- drawKeypoints(MlPose): draws the keypoints detected on the entire body
 *		- drawSkeleton(MlPose): draws the lines for arms, shoulders, legs etc...
 *		- any other custom method that takes MlBody object as a first argument
 * @args: several arguments can be appended to the callback if needed to be called directly from detect
 *		- The first argument is always the MlBody object
 */

//myMl.detect((myMl) => {
//	myMl.drawKeypoints(myMl);
//	myMl.drawSkeleton(myMl);
//	
//});


/*
 * Example of calling multiple callback methods
 */

//myMl.detect(myMl.drawFeature, ['nose', 'rightEye']);


/*
 * Example of calling a method from main
 */
//myMl.detect(test); 
//
//function test(ml) {
//	console.log(ml.poses);
//}
//	


/*
 * Example using p5.js
 */

	
//function setup() {
//	
//	myMl.toP5(myMl);
//	myMl.detect((myMl) => {
//		clear();
//		draw();
//		//myMl.drawKeypoints(myMl);
//	});
//
//}
//
//function draw() {
//	if (myMl.poses && myMl.poses.length > 0) {
//		let pose = myMl.poses[0].pose;
//		let nose = pose['nose'];
//		fill(255, 0,0);
//		ellipse(nose.x, nose.y, 50);
//	}
//	
//}