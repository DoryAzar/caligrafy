<?php

/**
 * Validator.php
 * Class that validates inputs
 * @author Dory A.Azar
 * @copyright 2019
 * @version 1.0
 *
*/

/**
 * Class that validates inputs
 * @version 1.0
 *
*/

class Validator extends \GUMP
{
    public function check($data, $rules = array(), $filters = array())
    {
        $output = true;
        if (!empty($rules) && !empty($filters)) {
            $this->validation_rules($rules);
            $this->filter_rules($filters);
            $valid = $this->run($data);
            $output = $valid? $valid : $this->get_errors_array();
        } else if(!empty($rules)) {
            $valid = $this->validate($data, $rules);
            $output = $valid === true? $valid : $this->get_errors_array();
        } else if (!empty($filters)) {
            $valid = $this->filter($data, $filters);
            $output = $valid;
        } 
        return $output;
        
    }

}