/*
 * Instantiating the ml object with some settings
 * settings:
 * - media: provide the mediaId of the video element in the markup
 * - canvas: provide the canvasId of the canvas element in the markup
 * - filter: set to true if you want to extract the body image from the background
 * - hideVideo: hides the video
 * - brain: {...} defines the brain settings (type and options)
 */

var myMl = new MlCore({ brain: {type: 'featureextractor'}}); 

/* 
 * Using the Feature Extractor, objects can be classified
 * Neural Network Methods
 * - @constructor: define the brain by specifying number of inputs, outputs, debug mode and the type of task
 * - @addImage: adds sample image data for training the brain
 * - @train: trains the model and upon results automatically starts classifiying
 * - @classify: classifies the detections
 * - @save: saves the model
 * - @load: loads the saved model
 */


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
	dataLabel.option('Happy');
	dataLabel.option('Sad');
	listItem = createElement('li')
	listItem.child(dataLabel);
	listItem.parent('mlUI');
	
	// add add data button
	listItem = createElement('li');
	listItem.parent('mlUI');
	dataButton = createButton('add example');
	listItem.child(dataButton);
	dataButton.mousePressed(() => myMl.addImage(myMl, dataLabel.value()));
	
	// add train button
	trainButton = createButton('train');
	trainButton.parent('apps');
	trainButton.mousePressed(() => myMl.train(myMl));
	
	// add a save button
	saveButton = createButton('save model');
	saveButton.parent('apps');
	saveButton.mousePressed(() => myMl.save('model-feature', console.log('saved')));
	
	// add a load button
	loadButton = createButton('load model');
	loadButton.parent('apps');
	loadButton.mousePressed(() => myMl.load(appResources + 'model-feature.json', () => {
		console.log('loaded');
		myMl.classify(myMl);
	}));
}


function draw() {
	classificationP.html(myMl.brain && myMl.brain.results? myMl.brain.results : message);
}