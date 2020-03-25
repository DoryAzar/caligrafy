/*
 * Instantiating the ml object with some settings
 * settings:
 * - media: provide the mediaId of the video element in the markup
 * - canvas: provide the canvasId of the canvas element in the markup
 * - filter: set to true if you want to extract the body image from the background
 * - hideVideo: hides the video
 */
var myMl = new MlBody();


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

myMl.detect((myMl) => {
	myMl.drawKeypoints(myMl);
	myMl.drawSkeleton(myMl);
	
});


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