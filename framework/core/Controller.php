<?php
/**
 * Controller.php
 * Parent Class that defines the properties and methods of a controller
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Parent Class that defines the properties and methods of a controller
 * @version 1.0
 *
*/


class Controller
{
    protected $loader;
    protected $request;
    protected $model;
    protected $connection;
    protected $validator;
    protected $payment;

    public function __construct($modelname, $table = null)
    {
       return $this($modelname, $table);
    }
    
    public function __invoke($modelname, $table) 
    {

        // instantiate Loader for loading further librairies
        $this->loader = new Loader();
        
        //instantiate the request
        $this->request = $this->request?? new Request();
        
        // instantiate the validator
        $this->validator = $this->validator?? new Validator();
        
        $this->connection = connect()?? null;
        
        // instantiate main connected modules
        $this->payment = $this->payment?? null;
        
        // connect to the model
        if($modelname) {
            $this->model = is_string($modelname)? new $modelname() : $modelname;
            $this->model->associate($table);
        } else {
            $this->model = new Model($table);
        }
        return $this;

    }
    /**
     * Activates a the Stripe Payment module
     */
    public function activatePayment()
    {
        $this->payment = new Payment();
        return $this;
    }

    /**
     * Associates a controller with a model optionally with a table in the database
     */
    public function associate($modelname, $table)
    {
        return $this($modelname, $table);
    }
    
    /**
     * Implied default function that will run when no action has been determined at runtime
     *
    */
    public function index() {}
    

    /**
     * Basic common CRUD function that fetches all data from the associated table
     */
    public function all() {
        return $this->model->all();
    }
    

    /**
     * Basic common CRUD function that finds a particular record
     */
    public function find($id = null) {
        $argument = $id?? $this->request->fetch('id')?? null;
        return $this->model->find($argument);
    }
    
    /**
     * Basic common CRUD function that saves data to the database whether adding a new record or updating an existing one
     */
    public function save($inputs, $id = null)
    {
        $argument = $id?? $this->request->fetch('id')?? null;
        return $argument? $this->model->update($inputs, $argument) : $this->model->create($inputs);

    }
    
    /**
     * Basic common CRUD function that deletes a record from the database
     */
    public function delete($id = null) 
    {
        $argument = $id?? $this->request->fetch('id')?? null;
        return $this->model->delete($argument)?? ''; 
    }

    public function search($scope = null, $keywords = null)
    {
        $scope = $scope?? $this->request->fetch->scope?? null;
        $keywords = $keywords?? $this->request->fetch->keywords?? null;
        return $this->model->search($scope, $keywords);
    }
    
    
    /**
     * Gets the table that is associated with a model
     */
    public function table() 
    {
        return $this->model->table?? '';
    }
    
    /**
     * Filtering and Validation through a controller
     */
    public function check($data, $rules = array(), $filters = array())
    {
        return $this->validator->check($data, $rules, $filters);
    }

    /**
     * Validation through a controller
     */
    public function validate($data, $rules) 
    {
        $valid = $this->validator->validate($data, $rules);
        return $valid === true? $valid : $validator->get_errors_array();
    }
    
    /**
     * Filtering through a controller
     */
    public function filter($data, $filters)
    {
        $valid = $this->validator->filter($data, $filters);
        return $valid;   
    }
  
    /**
     * return a Pug template view with viewData passed to it
     */
    public function view($viewName, $viewData = array())
    {
        return view($viewName, $viewData);
    }

    /**
     * return a view consumable by APIs
     */
    public function api($viewData = array())
    {
        return api($viewData);
    }
    
    
}