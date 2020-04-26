/*
 * Instantiating the ml object with some settings
 * settings:
 * - brain: {...} defines the Neural Network settings
 * - type: either 'neuralnetwork' or 'featureextractor'. In this case we want to build a neural network
 * - options { inputs, outputs, debug, task}:  set debug to true to see the confidence visor. The task can either be classification or regression depending on whether the output is discreent or continuous
 */

var myMl = new MlCore({ brain: { type: 'neuralnetwork', options: { inputs: 3, outputs: 1, debug:true, task: 'classification'}}}); 

// Step 1: load data or create some data 
const data = [
  {r:255, g:0, b:0, color:'red-ish'},
  {r:254, g:0, b:0, color:'red-ish'},
  {r:253, g:0, b:0, color:'red-ish'},
  {r:0, g:0, b:255, color:'blue-ish'},
  {r:0, g:0, b:254, color:'blue-ish'},
  {r:0, g:0, b:253, color:'blue-ish'}
];


// Step 2: add data to the neural network
data.forEach(item => {
  const inputs = {
    r: item.r, 
    g: item.g, 
    b: item.b
  };
  const outputs = {
    color: item.color
  };

  myMl.brain.addData(inputs, outputs);
});

// Step 3: normalize your data;
//myMl.brain.normalizeData();

// Step 4: train your neural network
const trainingOptions = {
  epochs: 32,
  batchSize: 12
}
  myMl.brain.train(trainingOptions, finishedTraining);

// Step 5: use the trained model
function finishedTraining(){
  classify();
}

// Step 6: make a classification
function classify(){
  const input = {
    r: 0, 
    g: 0, 
    b: 180
  }
    myMl.brain.classify(input, handleResults);
}

// Step 7: define a function to handle the results of your classification
function handleResults(error, results) {
    if(error){
      console.error(error);
      return;
    }
    console.log(results); // {label: 'red', confidence: 0.8};
}
