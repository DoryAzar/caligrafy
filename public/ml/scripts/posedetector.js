/*
 * Instantiating the ml object with some settings
 * settings:
 * - media: provide the mediaId of the video element in the markup
 * - canvas: provide the canvasId of the canvas element in the markup
 * - filter: set to true if you want to extract the body image from the background
 * - hideVideo: hides the video
 * - brain: {...} defines the Neural Network settings
 */



/* 
 * Using Neural Networks to train recognizing body poses
 * Neural Network Methods
 * - @constructor: define the brain by specifying number of inputs, outputs, debug mode and the type of task
 * - @addData: adds sample data for training the brain
 * - @train: trains the model and upon results automatically starts classifiying
 * - @classify: classifies the detections
 */
var myMl = new MlCore(); // { brain: { type: 'neuralnetwork', options: { inputs: 34, outputs: 2, debug:true, task: 'classification'}}}

let dataButton;
let dataLabel;
let trainButton;
let saveButton;
let loadButton;
let classificationP;
let listElement;
let listItem;
let message = 'waiting to train model';
let appResources = env.home + 'public/' + env.request.uriComponents[0] + '/resources/models/';

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
	trainButton.mousePressed(() => myMl.train(myMl, { epochs: 25}));
	
	// add a save button
	saveButton = createButton('save model');
	saveButton.parent('apps');
	saveButton.mousePressed(() => myMl.save('model', console.log('saved')));
	
	// add a load button
	loadButton = createButton('load model');
	loadButton.parent('apps');
	loadButton.mousePressed(() => myMl.load(appResources + 'model.json', () => {
		console.log('loaded');
		myMl.classify(myMl);
	}));
}


function draw() {
	classificationP.html(myMl.brain && myMl.brain.results? myMl.brain.results : message);
}