<?php

/**
 * Model.php
 * Model is the base model from which application models can be extended
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class Model is the base model from which the application models can be extended
 * @author Dory Azar
*/

namespace Caligrafy;
use \Exception as Exception;

class Model {
    
    public $table;
    protected $__restricted;
    
    public function __construct($table = null)
    {   
        return $this($table);
    }

    public function __invoke($table = null, $dynamicArgs = array())
    {
        // connect to database
        $this->table = $table != null? $table : $this->table;
        $this->__restricted = ['table', '__restricted'];
        foreach($dynamicArgs as $property => $value) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * Associates a model with a table in the database
     */
    public function associate($table) 
    {
        $this($table);
        return $this;
    }
    
    /**
     * Basic CRUD functions on the associated table
     */

    // CREATE
    public function create($inputs) 
    { 
        // turn the object into a manipulatable array
        $inputArray = !is_array($inputs)? json_decode(json_encode($inputs), true) : $inputs;
        
        //remove all undesired values from object: null and other non related attributes
        $inputArray = array_filter($inputArray, function($value, $key) { return $value != null && !in_array($key, $this->__restricted); }, ARRAY_FILTER_USE_BOTH);
        

        // Format the value binder for PDO
        $binder = [];
        for($i = 0; $i < sizeof($inputArray); $i++) {
            $binder[$i] = "?";
        }
        $binder = implode(",", $binder);

        // Execute the result
        $result = Database::execute("INSERT INTO ". $this->table . " (".implode(',', array_keys($inputArray)).") VALUES (".$binder.")", array_values($inputArray));

        // update the object to insert the id
        $this->id = Database::lastInsertId();
        return $this->find($this->id); 

    }

    // UPDATE
    public function update($inputs, $id)
    { 
        // turn the object into a manipulatable array
        $inputArray = !is_array($inputs)? json_decode(json_encode($inputs), true) : $inputs;

        //remove all undesired values from object: null and other non related attributes
        $inputArray = array_filter($inputArray, function($value, $key) { return $value != null && !in_array($key, $this->__restricted); }, ARRAY_FILTER_USE_BOTH);

        // check if the record exists
        $record = $this->find($id);
        $recordExists = $id? !empty($record)? $record->toArray() : array() : array(); 
        $recordExists = sizeof($recordExists);
        $binder = array_map(function($v) { return $v." = ?"; }, array_keys($inputArray) );
        if ($recordExists != 0) {
            $result = Database::execute("UPDATE ".$this->table." SET ".implode(",", $binder)." WHERE id = ".$id, array_values($inputArray));
        } 

        // return the fetched result from database
        return $this->find($id); 
    }
    
    // DELETE
    public function delete($id)
    { 
      // Create query
        $result = Database::execute("DELETE FROM ". $this->table . " WHERE id= ?", [$id]);
        return $result;
    }
    
    // FIND
    public function find($id)
    { 
        // Create query
         $result = Database::execute('SELECT * FROM ' . $this->table . " WHERE id = ?", [$id]);
         $result = Database::toArray($result);
         
        return !empty($result[0])? $this->arrayToModel($result[0]) : null;
    }
    
    // ALL
    public function all() { 
    
        // Create query
        $result = Database::execute('SELECT * FROM ' . $this->table);
        $renderedResult = array();
        foreach($result as $key => $value) {
            $renderedResult[] = $this->arrayToModel($value);
        }
        return $renderedResult;

    } 

    /**
     * Search functionality that searched records in a table
     * @param string $scope defines the column to search in the database
     * @param string $keywords defines the wildcard pattern to look for
     * @return array $result results found in the database
     * @author Dory A.Azar 
     * @version 1.0
     */  
    public function search($scope, $keywords) {

        // SEARCH DATA
        $result = Database::execute('SELECT * FROM ' . $this->table .  ' WHERE ' .$scope. ' LIKE :keywords', ['keywords' => $keywords]);
        $renderedResult = array();
        foreach($result as $key => $value) {
            $renderedResult[] = $this->arrayToModel($value);
        }
        return $renderedResult;
    } 

    /**
     * Save functionality applied directly to the object that could update or create depending on whether the object has an id or not
     */
    public function save()
    {
        $id = $this->id?? null;
        return $id? $this->update($this, $this->id) : $this->create($this);
    }

    
    /**
     * Defines the hasOne relationship
     * @param string $modelName defines the name of the model to join to
     * @param string $joinedTableName defines the name of the table to join to
     * @param string $localKey defines the identifier key of the joined table
     * @param string $foreignKey defines the identifier key of the key indexing the joined table
     * @return Model object containing the records from the join
     * @author Dory A.Azar 
     * @version 1.0
     */  
    public function hasOne($modelName, $joinedTableName, $foreignKey = null, $localKey = null, $functionName = null)
    {
        $functionName = $functionName?? debug_backtrace()[1]['function'];
        $this->$functionName = null;
        $joinedTableName = strtolower($joinedTableName);
        $oneToOneModel = class_exists($modelName)? new $modelName($joinedTableName): null;
        $outcome = array();
        if ($oneToOneModel instanceof Model) {
                $localId = $localKey?? 'id';
                $localKey = $localKey?  $joinedTableName.".".$localKey : $joinedTableName.".id";
                $foreignKey = $foreignKey? $this->table.".".$foreignKey : $this->table.".".strtolower($modelName)."_id";
                $result = Database::execute('SELECT '.$joinedTableName.'.* FROM '.$this->table.' LEFT JOIN '.$oneToOneModel->table.' ON '.$localKey." = ".$foreignKey." WHERE ".$this->table.".id = ".$this->id);
                $oneToOneModel = $oneToOneModel->arrayToModel(Database::toArray($result)[0]);
                $this->$functionName = $oneToOneModel->id? $oneToOneModel : null ;
                array_push($this->__restricted, $functionName);

            }
        return $oneToOneModel;
    }
    
   
     /**
     * Defines the hasMany relationship
     * @param string $modelName defines the name of the model to join to
     * @param string $joinedTableName defines the name of the table to join to
     * @param string $foreignKey defines the identifier key of the key indexing the joined table
     * @param string $localKey defines the identifier key of the joined table
     * @return Model object containing the records from the join
     * @author Dory A.Azar 
     * @version 1.0
     */    
    public function hasMany($modelName, $joinedTableName, $foreignKey = null, $localKey = null, $functionName = null)
    {
        $functionName = $functionName?? debug_backtrace()[1]['function'];
        $joinedTableName = strtolower($joinedTableName);
        $oneToManyModel = class_exists($modelName)? new $modelName($joinedTableName): null;
        $outcome = array();
        if ($oneToManyModel instanceof Model) {
               $foreignKey = $foreignKey?? strtolower(get_class($this))."_id";
               $localKey = $localKey? $this->$localKey?? $this->id : $this->id;
               $result = Database::execute('SELECT * FROM '.$joinedTableName.' WHERE '.$foreignKey.' = '.$localKey);
               $result = Database::toArray($result);
                foreach($result as $key => $value) {
                    $newModel = new $modelName($joinedTableName);
                    $outcome[] = $newModel->arrayToModel($value);
                }
                $this->$functionName = $outcome;
                array_push($this->__restricted, $functionName);

            }
        return $outcome;
    }
    
    // Defines the inverse relationship
    public function belongsTo($modelName, $joinedTableName, $foreignKey = null, $localKey = null)
    {
        $functionName = debug_backtrace()[1]['function'];
        return $this->hasMany($modelName, $joinedTableName, $foreignKey, $localKey, $functionName);
    }
    
    
    public function belongsToMany($modelName, $joinedTableName, $foreignKeyLocal = null, $foreignKeyJoin = null, $functionName = null)
    {

        // the calling function will become a property of the object
        $functionName = $functionName?? debug_backtrace()[1]['function'];
        
        // get the tables' names, foreign keys,  and create the pivot table name
        $table1 = $this->table;
        $table2 = $joinedTableName;
        $model1 = strtolower(get_class($this));
        $model2 = strtolower($modelName);
        $foreignKeyLocal = $foreignKeyLocal?? 'id';
        $foreignKeyJoin = $foreignKeyJoin?? 'id';
        $fklocal = $model1."_"."id";
        $fkjoin = $model2."_"."id";
        $pivot = !$this->tableExists($table1.'_'.$table2)? $this->tableExists($table2.'_'.$table1)? $table2.'_'.$table1 : $table1.'_'.$table2 : $table1.'_'.$table2;
        $outcome = array();
        $pivotOutcome = array();
        
        
        // create the pivot table
        $result = Database::execute('CREATE TABLE IF NOT EXISTS '.$pivot.' (`id` INT(25) UNSIGNED NOT NULL AUTO_INCREMENT,`'.$fklocal.'` INT(25) NOT NULL, `'.$fkjoin.'` INT(25) NOT NULL, `created_at` DATETIME NOT NULL DEFAULT now(), `modified_at` DATETIME NOT NULL DEFAULT now() ON UPDATE now(), PRIMARY KEY (`id`), CONSTRAINT fk1 FOREIGN KEY (`'.$fklocal.'`) REFERENCES `'.$table1.'` (`'.$foreignKeyLocal.'`) ON UPDATE CASCADE ON DELETE CASCADE, CONSTRAINT fk2 FOREIGN KEY (`'.$fkjoin.'`) REFERENCES `'.$table2.'` (`'.$foreignKeyJoin.'`) ON UPDATE CASCADE ON DELETE CASCADE
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8;');


        $pivotModel = new Model($pivot);

        // build the pivot model and associate to the caller object
        $result = Database::execute('SELECT '.$table2.'.* FROM '.$table1.' LEFT JOIN '.$pivot.' ON '.$table1.'.'.$foreignKeyLocal.'='.$pivot.'.'.$fklocal.' LEFT JOIN '.$table2.' ON '.$table2.'.'.$foreignKeyJoin.'='.$pivot.'.'.$fkjoin.' WHERE '.$table1.'.'.$foreignKeyLocal.' = '.$this->$foreignKeyLocal);
        $result = Database::toArray($result);
        
        // Get all the pivot results
        $pivotResult = Database::execute('SELECT '.$pivot.'.* FROM '.$table1.' LEFT JOIN '.$pivot.' ON '.$table1.'.'.$foreignKeyLocal.'='.$pivot.'.'.$fklocal.' LEFT JOIN '.$table2.' ON '.$table2.'.'.$foreignKeyJoin.'='.$pivot.'.'.$fkjoin.' WHERE '.$table1.'.'.$foreignKeyLocal.' = '.$this->$foreignKeyLocal);
        $pivotResult = Database::toArray($pivotResult);

        
        // Get the categories results
        foreach($result as $key => $value) {
            $newModel = new $modelName($joinedTableName);
            if (isset($value['id'])) {
                $outcome[] = $newModel->arrayToModel($value);
            }
        }

        // Get the categories results
        foreach($pivotResult as $key => $value) {
            $newModel = new $modelName($joinedTableName);
            if (isset($value['id'])) {
                $pivotOutcome[] = $newModel->arrayToModel($value);
            }
        }
        
        // keep track of the keys in the pivot model
        $pivotModel->$fklocal = $this->id?? null;
        $pivotModel->fklocal = $fklocal;
        $pivotModel->fkjoin = $fkjoin;
        
        // create the function results
        $this->$functionName = $outcome;
        
        // create a pivot attribute that saves all the pivot info in a model
        $this->pivot = $this->pivot?? new Model();
        $this->pivot->$joinedTableName = $pivotModel;
        $this->pivot->$joinedTableName->all = $pivotOutcome;
        $this->pivot->$joinedTableName->functionName = $functionName;
        
        // create restricted names to avoid having them transfer upon save
        array_push($this->pivot->$joinedTableName->__restricted, 'fklocal', 'fkjoin', 'all', 'functionName');
        array_push($this->__restricted, $functionName, 'pivot');
    
        
        return $outcome;
    }
    
    
    public function attach($joinedTableName, $id)
    {
        return $this->attachDetach($joinedTableName, $id);
    }
    
    
    public function detach($joinedTableName, $id)
    {
        return $this->attachDetach($joinedTableName, $id, false);
    }
    
    protected function attachDetach($joinedTableName, $id, $attach = true)
    {

        $idLocal = $this->id?? null;
        $idJoin = $id?? null;
        
        if ($idLocal && $idJoin) {
            // if it has a pivot
            if ($this->pivot->$joinedTableName) { 
                // get the pivot ids
                $fklocal = $this->pivot->$joinedTableName->fklocal;
                $fkjoin = $this->pivot->$joinedTableName->fkjoin;
                
                // create a model to add in case of attachment
                $attachModel = new Model($this->pivot->$joinedTableName->table);
                $attachModel->$fklocal = $idLocal;
                $attachModel->$fkjoin = $idJoin;
                
                // find if an entry exists already
                $search = Database::toArray(Database::execute('SELECT id FROM '.$this->pivot->$joinedTableName->table.' WHERE '.$fklocal.' = ? && '.$fkjoin.' = ?', [$idLocal, $idJoin]));

                $idPivot = !empty($search)? isset($search[0]['id'])? $search[0]['id'] : null : null;
                $attachModel->id = $idPivot;
                $attach? $attachModel->save() : $this->pivot->$joinedTableName->delete($idPivot);
                
            }
        }
        $function = $this->pivot->$joinedTableName->functionName;
        return $this->$function();      
    }
    
    /**
     * Turns an array into a Model
     * @param array $array takes the array that needs to be converted to a model
     * @return Model object containing the modified object
     * @author Dory A.Azar 
     * @version 1.0
     */ 
    public function arrayToModel($array) 
    {
        $newModel = new $this($this->table);
        foreach($array as $key => $value) {
            $newModel->$key = $value;
        }
        return $newModel;
    }
      
    /**
     * Turns Model into an array
     * @return array representation of the model
     * @author Dory A.Azar 
     * @version 1.0
     */   
    public function toArray()
    {
        $result = array();
        if($this instanceof Model) {
            $result = (Array)$this;
        }
        return $result;
    }
    
    /**
     * Checks if a table exists
     * @return boolean of the result
     * @author Dory A.Azar 
     * @version 1.0
     */   
    public function tableExists($tablename)
    {
        try {
            $result = Database::execute('SELECT * from '.$tablename);
        } catch (Exception $e) {
            return false;
        }
        return true;
        
    }
    
}