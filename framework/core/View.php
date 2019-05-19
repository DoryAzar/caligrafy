<?php

/**
 * View.php
 * View is the view renderer of the application
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class View is the view renderer of the application
 * @author Dory Azar
*/

namespace Caligrafy;

use \Phug;

class View {
    
    public $viewName;
    public $viewData;
    protected $viewPath;
    protected $request;

    public function __construct($viewName, $viewData = array()) 
    {
        $this->request = request();
        $this->viewName = $viewName;
        $this->viewData = $viewData;
        $this->viewPath = VIEW_PATH.$viewName.'.pug';
        
        // If it's an api call then don't render the view
        if ($this->request->wantsJson() || $viewName == null) {
            echo json_encode($viewData);
            exit;
        }
        // Otherwise render view
        Phug::displayFile($this->viewPath, $this->viewData);
    }

}